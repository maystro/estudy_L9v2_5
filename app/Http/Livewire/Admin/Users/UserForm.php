<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserForm extends Component
{
    use WithFileUploads;

    public $form_title='إضافة حساب مستخدم';
    public $form_icon="<i class='bx bx-user'></i>";

    public $form_password=null;
    public $form_password_confirmation='';
    public $form_password_changed=false;
    public $form_user_status=false;

    public $user_photo;
    public $form_name='';
    public $form_level_id=0;
    public $form_email='';
    public $form_job='';
    public $form_phone='';
    public $form_address='';
    public $form_user_role;
    public $form_old_user_role;
    public $form_selected_permissions=[];
    public $form_old_selected_permissions=[];

    public $navsUserBasic=true;
    public $navsUserSecurity=false;
    public $navsUserPermissions=false;
    public $navsUserStatus=false;

    public $recordChanged=false;
    public $storage_directories=[];

    public $user;
    public $frm_file_playlists=[];

    private $form_modal='#user-form-modal';
    private $tableLivewirePage='admin.users.user-table';

    protected $listeners=[
        'editRecord'=>'editRecord_EventHandler'
    ];
    protected $rules = [
        'form_name'=>'required|min:6',
        'form_level_id'=>'required|numeric|gt:0',
        'form_email'=>'email|required|unique:users,email',
        'form_job'=>'min:6',
        'form_address'=>'min:6',
    ];
    protected $messages = [
      'form_name.required'=>'لابد من كتابة الاسم',
      'form_name.min'=>'الاسم لا يقل عن ٦ أحرف',
      'form_level_id.required'=>'لابد من اختيار المستوى',
      'form_level_id.gt'=>'لابد من اختيار المستوى',
      'form_email.email'=>'البريد غير صحيح',
      'form_email.required'=>'لابد من ادخال البريد صحيح',
      'form_email.unique'=>'هذا البربد غير متاح',
      'form_job.min'=>'حروف العمل لا تقل عن ٦ أحرف',
      'form_address.min'=>'حروف العنوان لا تقل عن ٦ أحرف',
    ];

    public function render()
    {
        //sleep(2);
        return view('livewire.admin.users.user-form');
    }

    public function mount(){
        $this->user=null;
    }

    public function updatedUserPhoto()
    {
        $this->validate([
            'user_photo' => 'image|max:200',
        ],[
            'user_photo.max' => 'حجم الصورة لا يزيد عن ٢٠٠ ك/ب'
        ]);
    }

    public function image_reset(){$this->user_photo=null;}

    public function password_changed()
    {
        if($this->user)
        $this->form_password_changed=true;

    }
    public function generate_password()
    {
        $password = Str::random(8);
        $this->form_password = $password;
        $this->form_password_confirmation = $password;
        if($this->user)
        $this->form_password_changed=true;
    }

    public function update_password(){
        $this->resetErrorBag();
        $this->resetValidation();
        $validated = Validator::validate(
            [
                'form_password'=>$this->form_password,
                'form_password_confirmation'=> $this->form_password_confirmation
            ],
            [
                'form_password'=>'required|min:6|confirmed',
                'form_password_confirmation'=>'required'
            ],
            [
                'form_password.required'=>'كلمة العبور مطلوبة',
                'form_password.min'=>'عدد الحروف لا يقل عن ٦ حروف',
                'form_password.confirmed'=>'رمز التأكيد غير مطابق',
            ]
        );
        $this->form_password_changed=false;
    }

    public function TabChanged($target)
    {
        $this->navsUserBasic = false;
        $this->navsUserSecurity = false;
        $this->navsUserPermissions = false;
        $this->navsUserStatus = false;

        switch ($target)
        {
            case 'navsUserBasic':
                $this->navsUserBasic = true;
                break;
            case 'navsUserSecurity':
                $this->navsUserSecurity = true;
                break;
            case 'navsUserPermissions':
                $this->navsUserPermissions = true;
                break;
            case 'navsUserStatus':
                $this->navsUserStatus = true;
                break;
            default:break;
        }
    }

    public function dataChanged()
    {
        $this->recordChanged = true;
    }

    public function editRecord_EventHandler($id)
    {
        $this->user = User::query()->find($id);
        $details = $this->user->details;

        $this->form_title = "بيانات حساب : ".$this->user->name;
        $this->form_name = $this->user->name;
        $this->form_email = $this->user->email;
        $this->form_user_status = $this->user->status=='inactive'?'blocked':$this->user->status;
        $this->form_level_id = $details->level_id;
        $this->form_job = $details->job;
        $this->form_address = $details->address;
        $this->form_phone = $details->phone;

        $permissions = Permission::query()
            ->where('user_id','=',$id);

        $roles = Role::query()
            ->where('user_id','=',$id)->first()->title;

        $this->form_selected_permissions =
            $permissions->get()->pluck('title')->toArray();
        $this->form_user_role = $roles;

        $this->form_old_selected_permissions = $this->form_selected_permissions ;
        $this->form_old_user_role = $this->form_user_role;

    }

    public function save()
    {
        if ($this->user)
        {
           $this->update_user();
        }
        else
        {
            $this->create_user();
        }

        $this->emitTo($this->tableLivewirePage,'refresh');

    }
    private function update_user()
    {
        $details = $this->user->details;

        $this->user->name   = $this->form_name;
        $this->user->email  = $this->form_email;
        $this->user->status = $this->form_user_status;
        If($this->user_photo)
        {
            $name = $this->user_photo->getClientOriginalName();
            $path = $this->user_photo->store(config('app.upload-images-folder'));
            $filename = File::name($path).'.'.File::extension($path);
            $this->user->profile_photo = $filename;
        }
        $this->user->save();

        $details->level_id = $this->form_level_id;
        $details->job      = $this->form_job;
        $details->address  = $this->form_address;
        $details->home_phone    = $this->form_phone ;
        $details->work_phone    = $this->form_phone ;
        $details->save();

        $array_selected_permissions = array_diff($this->form_selected_permissions, $this->form_old_selected_permissions);
        $array_deselected_permissions = array_diff($this->form_old_selected_permissions, $this->form_selected_permissions);
        //delete unselected
        Permission::query()
            ->where('user_id','=',$this->user->id)
            ->whereIn('title',$array_deselected_permissions)->delete();

        //create new selection only
        array_map(function($permission){
            Permission::query()->create([
                'user_id'=>$this->user->id,
                'title'=>$permission,
            ]);
        },$array_selected_permissions);

        if($this->form_user_role != $this->form_old_user_role)
        {
            Role::query()
                ->where('user_id','=',$this->user->id)
                ->update([
                    'title'=>$this->form_user_role,
                ]);
        }
        $this->recordChanged=false;
    }

    private function create_user()
    {
        //validate user basic info
        $this->validate();

        //validate password
        Validator::validate([
            'form_password'=>$this->form_password,
        ],[
            'form_password'=>function($attribute, $value, $fail){
                if(Str::length($value)<6)
                {
                    $fail('كلمة المرور لا تقل عن ٦ أحرف');
                    $this->TabChanged('navsUserSecurity');
                }
                if($value != $this->form_password_confirmation)
                {
                    $fail('تأكيد كلمة المرور غير مطابق');
                    $this->TabChanged('navsUserSecurity');
                }

            }
        ]);

        //validate roles list
        Validator::validate([
            'user_role'=>$this->form_user_role,
        ],[
            'user_role'=>function($attribute, $value, $fail){
                if(Str::length($value)<1)
                    $fail(' لابد من اختيار نوع تسجيل المستخدم');
                $this->TabChanged('navsUserPermissions');
            },
        ]);

        $this->user = User::query()->create([
        'name'     => $this->form_name,
        'email'    => $this->form_email,
        'status'   => $this->form_user_status,
        'password' => Hash::make($this->form_password),
        ]);

        $details = UserDetails::query()->create([
            'user_id'  => $this->user->id,
            'level_id' => $this->form_level_id,
            'job'      => $this->form_job,
            'address'  => $this->form_address,
            'phone'    => $this->form_phone ,
        ]);

        array_map(function($permission){
            Permission::query()->create([
                'user_id'=>$this->user->id,
                'title'=>$permission,
            ]);
        },$this->form_selected_permissions);
            Role::query()->create([
                'user_id'=>$this->user->id,
                'title'=>$this->form_user_role,
            ]);

        session()->flash('message','تم إنشاء مستخدم ، يمكنك استخدام كلمة المرور الإفتراضية');
        $this->TabChanged('navsUserSecurity');

        $id = $this->user->id;
        $this->reset_data();
        $this->editRecord_EventHandler($id);
        $this->recordChanged=false;
    }

    public function reset_data()
    {
        $this->reset();
    }
}

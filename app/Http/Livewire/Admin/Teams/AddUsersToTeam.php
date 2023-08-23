<?php
namespace App\Http\Livewire\Admin\Teams;

use App\Enums\Roles;
use App\Models\Team;
use App\Models\TeamUser as TeamAndUser;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class AddUsersToTeam extends Component
{
    public $teamsList;
    public $team_id=0;
    public $users_ids=[];
    public $modalForm = 'teams-list-modal-form';
    public $form_title = 'إضافة مستخدمين لفريق';
    public $form_icon='bxs-group';

    protected $listeners=[
        'assign_users'=>'assign_users',
    ];
//    protected $rules=[
//        'team_id'=>'required|gt:0'
//    ];
//    protected $messages=[
//        'team_id.required'=>'لا بد من اختيار المجموعة',
//        'team_id.gt'=>'لا بد من اختيار المجموعة',
//    ];

    public function render()
    {
        return view('livewire.admin.teams.add-users-to-team');
    }

    public function mount( $modalForm )
    {
        $this->teamsList=Team::all();
        $this->modalForm = $modalForm;
    }

    public function updatedTeamId()
    {
    }
    private function validateInput()
    {
        Validator::validate([
            'team_id'=>$this->team_id
        ],[
           'team_id'=>'required|gt:0',
        ],[
            'team_id.required'=>'لابد من اختيار اسم المجموعة',
            'team_id.gt'=>'لابد من اختيار اسم المجموعة'
        ]);
    }
    public function assign_users($users_ids)
    {
        $this->users_ids=$users_ids;
    }

    public function close()
    {
    }
    public function save()
    {
        $this->validateInput();
        $created=0;
        foreach ($this->users_ids as $user_id)
        {
            $found = TeamAndUser::query()
                ->where('user_id','=',$user_id)
                ->where('team_id','=',$this->team_id)
                ->count();

            if($found<1)
            {
                $created +=1;
                TeamAndUser::create([
                    'team_id'=> $this->team_id,
                    'user_id'=> $user_id,
                    'role'=> Roles::Student
                ]);
            }
        }
        $this->dispatchBrowserEvent('hideModal',['target'=>'#'.$this->modalForm]);
        $this->dispatchBrowserEvent('show_message',[
            'icon'=>'success',
            'type'=>'primary',
            'title'=>'ادراج مجموعة',
            'text'=>'تم اضافة '.$created.' من '.count($this->users_ids),
            ]);
    }
}

<?php

namespace App\Models;

use App\Enums\Roles;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Role;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasTeams;

    protected $primaryKey='id';
    protected $fillable = [
        'name', 'email', 'password','role','profile_photo','status'
    ];
    protected $guarded=[];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo',
    ];

    const RolesRoute =[
        Roles::Admin=>'admin.dashboard',
        Roles::Author=>'profile',
        Roles::Editor=>'profile',
        Roles::Maintainer=>'profile',
        Roles::Teacher=>'profile',
        Roles::Student=>'profile',
        Roles::Subscriber=>'profile',
    ];
    const Status = [
        'ACTIVE', 'INACTIVE', 'BANNED', 'PENDING'
    ];

    static function allowedRoles(): array
    {
        return Roles::asArray();
    }
    /**
     * The accessors to append to the model's array form.
     *
     * @return HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }
    /**
     * The accessors to append to the model's array form.
     *
     * @return HasMany
     */
    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }
    public function getProfilePhotoAttribute()
    {
        //$image = asset('assets/img/avatars/profile-default.jpg');
        return 'profile_photo';
    }

    public function details(): HasOne
    {
        return $this->hasOne(UserDetails::class);
    }

    public function role():string
    {
        return \App\Models\Role::query()->where('user_id','=',$this->id)->get()[0]->title;
    }

    public function hasPermissionTo($required_permission): bool
    {
        foreach ($this->permissions as $permission)
        {
            if ($permission->title == $required_permission) return true;
        }
        return false;
    }

    public function hasRole($required_role): bool
    {
        foreach ($this->roles as $role)
        {
            if ($role->title == $required_role) return true;
        }
        return false;
    }

    public function isAdmin():bool
    {
        return $this->hasRole(Roles::Admin);
    }

    public function levelId()
    {
        $user = UserDetails::where('user_id',$this->id)->first();
        return $user->level_id;
    }

    public function levelTitle()
    {
        //dd()
        $id = $this->details->level_id;
        return Level::query()->find($id)->title;
    }

    public function getProfilePhoto() :string
    {
        $image = asset('assets/img/avatars/profile-default.jpg');

        if($this->social_driver != null)
        {
            if( $this->profile_photo !=null)
            {
                $isMissing = Storage::missing('public/media/uploads/images/'.$this->profile_photo);
                if(!$isMissing)
                    $image = Storage::url('public/media/uploads/images/'.$this->profile_photo);
            }
        }
        else{ // Social Registered
            if($this->social_avatar !=null)
            {
                $image =  $this->social_avatar;
            }
        }
        return $image;
    }

    public function getName() :string{
        return decrypt($this->name);
    }
    public function setName($name) : void
    {
        $this->name = encrypt($name);
    }

    public function getLectures()
    {
        return Lecture::query()->where('level_id','=',$this->levelId())->get();
    }
    public function getSubjects(): Collection|array
    {
        return Lecture::query()
            ->join('subjects','subjects.id','=','lectures.subject_id')
            ->join('levels_lectures','levels_lectures.lecture_id','=','lectures.id')
            ->where(['levels_lectures.level_id'=>auth()->user()->levelId()])
            ->where('subjects.id','!=',1)
            ->select(['subjects.title', 'subjects.id'])
            ->distinct()->get();
    }
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
        redirect()->back()->with('success',true);
        //dd('password link ssent');
    }
}

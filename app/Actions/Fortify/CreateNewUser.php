<?php

namespace App\Actions\Fortify;

use App\Enums\Roles;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string','min:6', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
//            'inv_code' => $this->invitationCode(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], [
            'name.required'=>'لابد من كتابة الاسم',
            'name.min'=>'الاسم لا يقل عن ٦ أحرف',
            'name.max'=>'الاسم لا يزيد عن ٢٥٥ حرف',
            'email.required'=>'لابد من كتابة بريد صحيح',
            'email.unique'=>'هذا البريد غير متاح',
            'terms.accepted'=>'لابد من الموافقة',
        ])->validate();

        if ($input['inv_code']==config('app.invitation-code'))
            $user_role = Roles::Student;
        else
            $user_role = Roles::Subscriber;

        $user = User::query()->create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password'])
        ]);
        UserDetails::query()->create([
            'user_id'=>$user->id,
            'job'=>'',
            'address'=>'',
            'home_phone'=>'',
            'work_phone'=>'',
            'level_id'=> ($user_role==Roles::Student) ? $input['level'] : 1,
        ]);
        Role::query()->create([
            'user_id'=>$user->id,
            'title'=>$user_role,
        ]);

        return $user;
// Create Team
//        return DB::transaction(function () use ($input) {
//            return tap(User::create([
//                'name' => $input['name'],
//                'email' => $input['email'],
//                'password' => Hash::make($input['password']),
//            ]), function (User $user) {
//                $this->createTeam($user);
//            });
//        });

    }

    /**
     * Create a personal team for the user.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function createTeam(User $user): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }
}

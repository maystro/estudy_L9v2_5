<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Guard;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialLoginController extends Controller
{
    public function SocialRedirect($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    public function SocialLogin($driver)
    {
        $socialUser = Socialite::driver($driver)->user();
        if ($socialUser)
        {
            $user = $this->createOrUpdateUser($socialUser,$driver);
            auth()->login($user);
            return redirect()->route('profile');
        }
        redirect()->back()->withErrors(__('auth.failed'));
    }

    private function createOrUpdateUser($socialUser,$driver) : User
    {
        $user = User::where('email','=',$socialUser->getEmail())->first();
        if ($user)
        {
            $user->name = $socialUser->getName();
            $user->social_avatar = $socialUser->getAvatar();
            $user->save();
        }
        else
        {
                $user = User::create([
                    'name'=>$socialUser->getName(),
                    'email'=>$socialUser->getEmail(),
                    'password'=>Hash::make($socialUser->getId()),
                    'role'=>'student',
                    'social_id' => $socialUser->getId(),
                    'social_email' => $socialUser->getEmail(),
                    'social_avatar' => $socialUser->getAvatar(),
                    'social_provider' => $driver,
                ]);
                UserDetails::create([
                    'user_id'=>$user->id,
                    'level_id'=>1,
                ]);
        }
        return $user;
    }
}

<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Guard;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialLoginControllerBack extends Controller
{
    public function SocialRedirect($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    public function SocialLogin($driver)
    {
        try {
            $user = Socialite::driver($driver)->user();
            $this->LoginOrCreateAccount($user, $driver);
        } catch (Exception $e) {
            return $this->sendFailedResponse($e->getMessage());
        }
        return empty($user->email)
            ? $this->sendFailedResponse("No email id returned from {$driver} provider.")
            : $this->loginOrCreateAccount($user, $driver);
    }

    protected function sendSuccessResponse()
    {
        //dd(Auth::user());
        return redirect()->route('home');
    }

    protected function sendFailedResponse($msg = null)
    {
        dd($msg);
        return redirect()->route('login')
            ->withErrors(['msg' => $msg ?: 'Unable to login, try with another provider to login.']);
    }

    private function LoginOrCreateAccount($providerUser, $driver)
    {
        $user = User::where('email', '=', $providerUser->getEmail())->first();
        auth()->login($user);
        return $this->sendSuccessResponse();
        //dd($user->getAuthIdentifier());
        if ($user) {
            $user = $user->update([
                'social_provider' => $driver,
                'social_id' => $providerUser->getId(),
                'social_avatar' => $providerUser->getAvatar(),
                'access_token' => $providerUser->token,
            ]);
        } else {
            $user = User::Create([
                'name' => $providerUser->getName(),
                'email' => $providerUser->getEmail(),
                'password' => Hash::make($providerUser->id),
                'social_provider' => $driver,
                'social_id' => $providerUser->getId(),
                'social_avatar' => $providerUser->getAvatar(),
                'access_token' => $providerUser->token,
                'role' => 'student'
            ]);
            UserDetails::Create([
                'user_id' => $user->id,
                'level_id' => 1,
            ]);
        }
        Auth::loginUsingId($user->getAuthIdentifier());
        return $this->sendSuccessResponse();
    }
}

<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                return redirect('/');
            }
        });
        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
                return redirect()->route(RouteServiceProvider::HOME);
            }
        });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // ---------- authenticate using email and password
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::query()->where('email', $request->email)->first();
            if ($user &&
                (Hash::check($request->password,$user->password)) ) {
                return $user;
            }
            return false;
        });
        // ---------- authenticate using email and password

        Fortify::loginView(function () {
            return view('v2.auth.login');
        });
        Fortify::registerView(function(){
            return view('v2.auth.register');
        });
        Fortify::requestPasswordResetLinkView(function(){
            return view('v2.auth.forgot-password');
        });
        Fortify::resetPasswordView(function (Request $request){
            return view('v2.auth.reset-password')
                ->with(['token'=>$request->token,'email'=>$request->email]);
        });

        // ---------- End of custom code ---------------------------

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email.$request->ip());
        });
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}

<?php

namespace App\Providers;

use App\Enums\Permissions;
use App\Enums\Roles;
use App\Models\Team;
use App\Policies\TeamPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        foreach (Permissions::asArray() as $permission)
        {
            Gate::define($permission, function () use ($permission) {
                return auth()->user()->hasPermissionTo($permission);
            });
        }
        foreach (Roles::asArray() as $role)
        {
            Gate::define($role, function() use($role){
                return Auth::user()->hasRole($role);
            });
        }
    }
}

<?php

namespace App\Http\Middleware;

use App\Enums\Roles;
use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use phpDocumentor\Reflection\DocBlock\Tags\Author;

class AllowRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param string $role
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
//        if (in_array($role,array_keys(User::RolesRoute)) &&
//            !in_array(auth()->user()->role(), array_keys(User::RolesRoute)) &&
//            auth()->user()->role() !=$role
//        )

        $roles = explode('|',$role);
        if(! in_array( $request->user()->role(), $roles) )
            abort(403);

        return $next($request);
    }
}

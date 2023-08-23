<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function index()
    {
        return view('welcome');
    }

    public function redirect()
    {
        if(in_array(Auth::user()->role(),User::allowedRoles()))
            return redirect()->route(User::RolesRoute[Auth::user()->role()]);
        else
        {
            return redirect()->back();
        }
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use mysql_xdevapi\Exception;

class UserAccountController extends Controller
{
    //
    private function getUserDetails()
    {
        $user = Auth::user();
        return User::query()
            ->join('user_details','users.id','=','user_details.user_id')
            ->where('users.id','=',$user->id)
            ->get([
                'users.id AS id',
                'users.name AS name',
                'users.profile_photo AS image',
                'users.email AS email',
                'user_details.id AS user_details_id',
                'user_details.address AS address',
                'user_details.job AS job',
                'user_details.home_phone AS home_phone',
                'user_details.level_id AS level_id',
            ])->first();
    }
    public function index()
    {
        return view('v2.profile.account.index',
            [
                'basicInfo'=>$this->getUserDetails(),
            ]);
    }

    public function update(Request $request)
    {
        //dd($request->job, $request->input('job'));
        $user_details_id = $this->getUserDetails()->user_details_id;
        $user_details = UserDetails::find($user_details_id);

        //$test = Auth::guard('administrator')->all();
        if($user_details)
        {
            Auth::user()->name = $request->name;
            $user_details->job = $request->input('job');
            $user_details->home_phone = $request->input('home_phone');

            if($request->hasFile('image'))
            {
                $name = $request->file('image')->getClientOriginalName();
                $path = $request->file('image')->store(config('app.upload-images-folder'));
                $filename = File::name($path).'.'.File::extension($path);
                Auth::user()->profile_photo = $filename;
            }
            $user_details->save();
            Auth::user()->save();
            return redirect()->route('profile.account')->with('message','success');
        }
        else
        {
            return redirect()->route('profile.account')->with('message','error');
        }
    }

    public function security()
    {
        return view('v2.profile.account.security');
    }
    public function security_update(Request $request)
    {
        $valid = true;
        $failMessage  = '';

        if (!Hash::check($request->currentPassword,Auth::user()->password))
        {
            $valid = false;
            $failMessage = 'كلمة المرور الحالية غير صحيحة';
        }
        if  ($request->newPassword != $request->confirmPassword)
        {
            $failMessage = 'كلمة المرور الجديدة غير مؤكدة';
            $valid  = false;
        }
        if  (!strlen($request->newPassword) >= 8  )
        {
            $failMessage = 'كلمة المرور الجديدة أقل من ٨ حروف';
            $valid  = false;
        }

        if ( ! $valid )
        {
            return redirect()->route('profile.account.security')
                ->with(['success'=>false,'message'=>$failMessage]);
        }
        else
        {
            Auth::user()->password = Hash::make($request->newPassword);
            Auth::user()->save();
            return redirect()->route('profile.account.security')
                ->with('success',true);
        }
    }
    public function notifications()
    {
        return view('v2.profile.account.index');
    }

}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Export\ExportConfig;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('v2.admin.home');
    }

    public function usersRoleList($role=null)
    {
        return view('v2.admin.users.users-list', ['role'=>$role]);
    }
    public function usersList()
    {
        return view('v2.admin.users.users-list');
    }

    public function export_pdf($data)
    {
        //** convert string to array of int */
        $data = trim($data,'[]');
        $data = array_map('intval', explode(',', $data));
        //** ****************************** */

        $users = User::query()->find($data);
//        $pdf = Pdf::loadHTML('exports.pdf.users-table-template',array('users' =>  $users), [],'utf8');
//        return $pdf->stream(public_path('hello_world.pdf'));
        return view('exports.pdf.users-table-template',['users'=>$users]);
    }
    public function export_print(){
        $export_config = ExportConfig::all()->first();
        $export_data = DB::table('export_data')->get()->toArray();
        $export_data = json_decode(json_encode($export_data),true);
        //dd($export_config->column_labels);
        return view('exports.print',[
            'header'=>$export_config->header,
            'footer'=>$export_config->footer,
            'labels'=>explode(',',$export_config->column_labels) ,
            'data'=>$export_data]);
    }

    /**** Subjects functions *************/
    public function subjects_index()
    {
        return view('v2.admin.subjects.index');
    }

    /**** Levels functions *************/
    public function levels_index()
    {
        return view('v2.admin.levels.index');
    }

    /**** Lectures functions *************/
    public function lectures_index()
    {
        return view('v2.admin.lectures.index');
    }
    public function lectures_table($subject_id=null)
    {
//        if($subject_id)
//            return view('v2.admin.lectures.lecture_table',['subject_id'=>$subject_id]);
//        else
//            return view('v2.admin.lectures.lecture_table');
        if($subject_id)
            return view('v2.admin.lectures.files_table',['subject_id'=>$subject_id]);
        else
            return view('v2.admin.lectures.files_table');

    }
    public function playlists_table()
    {
        return view('v2.admin.playlists.index');
    }

    public function playlists_files()
    {
        return view('v2.admin.playlists.playlistfiles');
    }

    public function teams_manager()
    {
        return view('v2.admin.teams.teams-manager');
    }

    public function teams_users_manager()
    {
        return view('v2.admin.teams.teams-users-manager');
    }

    public function documents_index()
    {
        return view();
    }


}

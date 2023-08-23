<?php

namespace App\Http\Controllers;

use App\Components\Mp3Info\Mp3Info;
use App\Models\Interaction;
use App\Models\Lecture;
use App\Models\Level;
use App\Models\Playlist;
use App\Models\PlaylistFiles;
use App\Models\Subject;
use App\Models\Todo\TodoItem;
use App\Models\Todo\TodoUserScore;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.index');
    }

    static function itrateLevels()
    {
        return Level::query()->where('is_public','=','true')->get();
    }

    public function listSubjects()
    {
        $subjects = auth()->user()->getSubjects();
        return view('v2.profile.subjects',compact('subjects'));
    }
    public function listLectures($subject_id)
    {
        $lectures = Lecture::query()
            ->join('levels_lectures','lectures.id','=','levels_lectures.lecture_id')
            ->where('levels_lectures.level_id','=',auth()->user()->levelId())
            ->where('subject_id','=',$subject_id)
            ->orderBy('lectures.lecture_number','asc')
            ->get([
                'lectures.id AS id',
                'lectures.title AS title',
                'lectures.subject_id AS subject_id',
                'lectures.created_at',
                'lectures.updated_at',
                'lectures.folder',
                'lectures.filename',
                'lectures.lecture_number',
                'levels_lectures.level_id AS level_id',
            ])
            ->groupBy('lecture_number');

        $subject = Subject::find($subject_id);

        return view('v2.profile.lectures',
            [
                'subject_id'=>$subject_id,
                'subject_name'=>$subject->title,
                'lectures'=>$lectures
            ]);
    }
    static function lecturesCount($subject_id)
    {
        $lectures_count = Lecture::query()
                ->join('levels_lectures','levels_lectures.lecture_id','=','lectures.id')
                ->where('subject_id',$subject_id)
                ->where('levels_lectures.level_id','=',auth()->user()->levelId())
                ->count();
        return ($lectures_count);
    }

    public function listLecturesByDay()
    {
        $level=Level::query()->find(auth()->user()->levelId());

        $lectures = Lecture::query()
            ->join('levels_lectures','lectures.id','=','levels_lectures.lecture_id')
            ->join('subjects','subjects.id','=','lectures.subject_id')
            ->where('levels_lectures.level_id','=',auth()->user()->levelId())
            ->orderBy('lectures.lecture_number','asc')
            ->orderBy('subjects.id','asc')
            ->orderBy('file_order','asc')
            ->get(['subjects.id AS subject_id','subjects.title AS subject_title','lecture_number','file_order','filename','folder','lectures.title AS file_title','lectures.id'])
            ->groupBy(['lecture_number','subject_id']);

        $lectures_count = Lecture::query()
            ->join('levels_lectures','lectures.id','=','levels_lectures.lecture_id')
            ->where('levels_lectures.level_id','=',auth()->user()->levelId())
            ->count('lectures.id');

        $subjects = auth()->user()->getSubjects();
        return view('v2.profile.getlecturebyday',[
            'lectures'=>$lectures,
            'subjects'=>$subjects,
            'level'=>$level,
            'count'=>$lectures_count,
        ]);
    }

    public function listLecturesByLevel($level_id)
    {
        $level=Level::query()->find($level_id);

        $lectures = Lecture::query()
            ->join('levels_lectures','lectures.id','=','levels_lectures.lecture_id')
            ->join('subjects','subjects.id','=','lectures.subject_id')
            ->where('levels_lectures.level_id','=',$level_id)
            ->orderBy('lectures.lecture_number','asc')
            ->orderBy('subjects.id','asc')
            ->orderBy('file_order','asc')
            ->get(['subjects.id AS subject_id','subjects.title AS subject_title','lecture_number','file_order','filename','folder','lectures.title AS file_title','lectures.id'])
            ->groupBy(['lecture_number','subject_id']);

        $lectures_count = Lecture::query()
            ->join('levels_lectures','lectures.id','=','levels_lectures.lecture_id')
            ->where('levels_lectures.level_id','=',$level_id)
            ->count('lectures.id');


        $subjects = auth()->user()->getSubjects();
        return view('v2.profile.getlecturebyday',[
            'lectures'=>$lectures,
            'subjects'=>$subjects,
            'level'=>$level,
            'count'=>$lectures_count,
        ]);
    }

    static function getLatestLectures()
    {
        return Lecture::query()
            ->join('levels_lectures','lectures.id','=','levels_lectures.lecture_id')
            ->join('subjects','subjects.id','=','lectures.subject_id')
            ->where('levels_lectures.level_id','=',auth()->user()->levelId())
            ->where('subjects.id','!=',1)
            ->orderBy('lecture_number','desc')
            ->limit(4)
            ->get([
                'subjects.id AS subject_id',
                'subjects.title AS subject_title',
                'levels_lectures.level_id AS level_id',
                'lectures.id AS lecture_id',
                'lectures.lecture_number AS lecture_number',
                'lectures.title AS lecture_title',
                'lectures.visit AS visits',
                'lectures.folder AS folder',
                'lectures.filename AS filename'
            ]);
    }
    static function getLatestBooks()
    {
        return PlaylistFiles::query()
            ->join('playlists','playlists.id','=','playlistfiles.playlist_id')
            ->join('lectures','lectures.id','=','playlistfiles.lecture_id')
            ->where('playlists.list_type','=','book')
            ->orderBy('lectures.file_order','desc')
            ->limit(4)
            ->get([
                'playlists.id AS playlist_id',
                'playlists.title AS playlist_title',
                'playlistfiles.id AS id',
                'playlistfiles.idx AS idx',
                'lectures.title AS title',
                'lectures.filename AS filename',
                'lectures.folder AS folder'
            ]);
    }

    static function pdfPagesCount($file_id)
    {
        $playlistfile = PlaylistFiles::query()->where('id', $file_id)->get()[0];
        $document = Lecture::query()->find($playlistfile->lecture_id);
        $filename = $document->filename;
        $fileLocalPath = Storage::path($document->folder) . "/$filename";
        $num=0;
        if(File::exists($fileLocalPath)) {
            $pdftext = file_get_contents($fileLocalPath);
            $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
        }
        return $num;
    }
    static function getMp3Info($file_id)
    {
        $document = Lecture::query()->where('id', $file_id)->first();
        $filename = $document->filename;
        $fileLocalPath = public_path($document->folder) . "/$filename";
        $filepath = asset($document->folder) . "/$filename";
        //$t = GetId3::fromDiskAndPath('local', $fileLocalPath);
        //$getID3 = new GetId3($filename);

        $duration = 'غير متاح';
        $bitrate = '';

        if(File::exists($fileLocalPath)) {
            $audio = new Mp3Info($fileLocalPath);
            $duration = floor($audio->duration / 60) . ' دقيقة ' . floor($audio->duration % 60) . ' ثانية' . PHP_EOL;
            $bitrate = ($audio->bitRate / 1000) . ' kb/s' . PHP_EOL;
        }
        return ['duration'=>$duration,'bitrate'=>$bitrate];
    }

    public function fileOpen($file_id)
    {
        $document = Lecture::query()->find($file_id);
        $subject  = $document->subject()->first();

        $doc_title = $document->title;
        $doc_index = $document->file_index;
        $subject_title = $subject->title;
        $visit_count = $document->visit;

        $filename = $document->filename ;
        $filepath = Storage::path($document->folder)."/$filename";
        $ext = File::extension($filename);

        $newfilename=$doc_title.'.'.$ext;

        switch( $ext )
        {
            case "pdf": $ctype="application/pdf"; break;
            case "exe": $ctype="application/octet-stream"; break;
            case "zip": $ctype="application/zip"; break;
            case "doc": $ctype="application/msword"; break;
            case "xls": $ctype="application/vnd.ms-excel"; break;
            case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
            case "gif": $ctype="image/gif"; break;
            case "png": $ctype="image/png"; break;
            case "jpeg":
            case "jpg": $ctype="image/jpg"; break;
            default: $ctype="application/octet-stream";
        }

        $headers = array(
            'Content-Type' => "$ctype",
            "Content-Disposition: attachment; filename=\"".$newfilename."\";"
        );

        //update visit field count

        $document->visit = $visit_count + 1;
        $document->addVisit();
        $document->update();

        return response()->file($filepath, [
            'Content-Disposition' => 'inline; filename="'. $newfilename .'"'
          ]);

    }
    public function fileView($file_id){
        $document = Lecture::query()->find($file_id);
        $subject  = $document->subject;

        $doc_title = $document->title;
        $visit_count = $document->visit;

        $filename = $document->filename ;
        $ext = File::extension($filename);

        $newfilename=$doc_title.'.'.$ext;

        switch( $ext )
        {
            case "pdf": $ctype="application/pdf"; break;
            case "exe": $ctype="application/octet-stream"; break;
            case "zip": $ctype="application/zip"; break;
            case "doc": $ctype="application/msword"; break;
            case "xls": $ctype="application/vnd.ms-excel"; break;
            case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
            case "gif": $ctype="image/gif"; break;
            case "png": $ctype="image/png"; break;
            case "jpeg":
            case "jpg": $ctype="image/jpg"; break;
            default: $ctype="application/octet-stream";
        }

        $headers = array(
            'Content-Type' => "$ctype",
            "Content-Disposition: attachment; filename=\"".$newfilename."\";"
        );

        //update visit field count

        $document->visit = $visit_count + 1;
        $document->update();
        $document->addVisit();

        return view('v2.profile.fileview',['document'=>$document,'subject'=>$subject]);
    }
    public function fileDownload($file_id)
    {
        $document = Lecture::where('id',$file_id)->first();
        $subject  = $document->subject()->first();

        $doc_title = $document->title;
        $doc_index = $document->file_index;
        $subject_title = $subject->title;
        $download_count = $document->download;


        $filename = $document->filename ;
        $filepath = public_path($document->folder)."/$filename";
        $ext = File::extension($filename);

        $newfilename=$doc_title.'.'.$ext;

        switch( $ext )
        {
            case "pdf": $ctype="application/pdf"; break;
            case "exe": $ctype="application/octet-stream"; break;
            case "zip": $ctype="application/zip"; break;
            case "doc": $ctype="application/msword"; break;
            case "xls": $ctype="application/vnd.ms-excel"; break;
            case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
            case "gif": $ctype="image/gif"; break;
            case "png": $ctype="image/png"; break;
            case "jpeg":
            case "jpg": $ctype="image/jpg"; break;
            default: $ctype="application/octet-stream";
        }

        $headers = array(
            'Content-Type' => "$ctype",
            // Send Headers: Prevent Caching of File
            'Cache-Control: private',
            'Pragma: private',
            'Expires: 0',
        );

        $document->download = $download_count + 1;
        $document->update();

        return response()->download(
            $filepath,
            $newfilename,
            $headers);
    }
    public function viewMediaList($playlist_id)
    {
        $playlist = Playlist::find($playlist_id);
        $playlistFiles = PlaylistFiles::query()
            ->join('playlists','playlists.id','=','playlistfiles.playlist_id')
            ->join('lectures','lectures.id','=','playlistfiles.lecture_id')
            ->where('playlists.id','=',$playlist_id)
            ->get([
                'playlists.id AS playlist_id',
                'playlists.title AS playlist_title',
                'lectures.id AS file_id',
                'lectures.title AS file_title',
                'lectures.filename AS file_name',
                'lectures.folder AS file_folder',
                'lectures.active AS file_active',
                'playlistfiles.idx AS file_idx',
            ]);

        return view('v2.profile.medialist',['files'=>$playlistFiles,'playlist'=>$playlist]);
    }
    public function openMedia($file_id)
    {
        $current_user_visit_found = Interaction::find_visits_by_current_user($file_id,'playlistfiles');

        if($current_user_visit_found->count() == 0)
        {
            Interaction::add_visit_for_current_user($file_id,'playlistfiles');
        }

        $visits_count = Interaction::get_visits($file_id,'playlistfiles')->count();
        $playlistFile = Playlist::join('playlistfiles','playlists.id','=','playlistfiles.playlist_id')
            ->where('playlistfiles.id','=',$file_id)
            ->get([
                'playlists.id AS playlist_id',
                'playlists.title AS playlist_title',
                'playlistfiles.id AS file_id',
                'playlistfiles.idx AS file_idx',
                'playlistfiles.title AS file_title',
                'playlistfiles.filename AS file_name',
                'playlistfiles.folder AS file_folder',
                'playlistfiles.active AS file_active',
            ])[0];
        return view('v2.profile.playfile',[
            'playlist_title'=>$playlistFile->playlist_title,
            'media_file'=>$playlistFile,
            'visit_count'=>$visits_count
        ]);
    }
    public function viewBookList($playlist_id)
    {
        $playlist = Playlist::find($playlist_id);
        $books = PlaylistFiles::query()
            ->join('playlists','playlists.id','=','playlistfiles.playlist_id')
            ->join('lectures','lectures.id','=','playlistfiles.lecture_id')
            ->where('playlists.id','=',$playlist_id)
            ->get([
                'playlists.id AS playlist_id',
                'playlists.title AS playlist_title',
                'lectures.id AS file_id',
                'lectures.title AS file_title',
                'lectures.filename AS file_name',
                'lectures.folder AS file_folder',
                'lectures.active AS file_active',
                'playlistfiles.idx AS file_idx',
            ]);

        return view('v2.profile.booklist',['playlist'=>$playlist,'books'=>$books]);
    }
    public function bookOpen($file_id)
    {
        $document = Lecture::query()->find($file_id);
        $subject  = $document->subject()->first();

        $doc_title = $document->title;
        $doc_index = $document->file_index;
        $subject_title = $subject->title;
        $visit_count = $document->visit;

        $filename = $document->filename ;
        $filepath = Storage::path($document->folder)."/$filename";
        $ext = File::extension($filename);
        $size = File::size($filepath);

        $newfilename=$doc_title.'.'.$ext;

        switch( $ext )
        {
            case "pdf": $ctype="application/pdf"; break;
            case "exe": $ctype="application/octet-stream"; break;
            case "zip": $ctype="application/zip"; break;
            case "doc": $ctype="application/msword"; break;
            case "xls": $ctype="application/vnd.ms-excel"; break;
            case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
            case "gif": $ctype="image/gif"; break;
            case "png": $ctype="image/png"; break;
            case "jpeg":
            case "jpg": $ctype="image/jpg"; break;
            default: $ctype="application/octet-stream";
        }

        $headers = array(
            'Content-type'=>$ctype,
            'Content-Disposition'=>'inline; filename="' . $newfilename . '"',
            'Content-Transfer-Encoding'=>'binary',
            'Accept-Ranges'=>'bytes',
            'Content-Range'=>"bytes 0-$size/$size",
            'Content-Length' => $size
        );

        //update visit field count

        $document->visit = $visit_count + 1;
        $document->addVisit();
        $document->update();

        return response()->file($filepath,$headers);

    }
    public function bookDownload($file_id)
    {
        $document = PlaylistFiles::where('id',$file_id)->first();

        $doc_title = $document->title;
        $doc_index = $document->file_index;
        $download_count = $document->download;
        $filename = $document->filename ;
        $filepath = public_path($document->folder)."/$filename";
        $ext = File::extension($filename);

        $newfilename=$doc_title.'.'.$ext;

        switch( $ext )
        {
            case "pdf": $ctype="application/pdf"; break;
            case "exe": $ctype="application/octet-stream"; break;
            case "zip": $ctype="application/zip"; break;
            case "doc": $ctype="application/msword"; break;
            case "xls": $ctype="application/vnd.ms-excel"; break;
            case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
            case "gif": $ctype="image/gif"; break;
            case "png": $ctype="image/png"; break;
            case "jpeg":
            case "jpg": $ctype="image/jpg"; break;
            default: $ctype="application/octet-stream";
        }

        $headers = array(
            'Content-Type' => "$ctype",
            // Send Headers: Prevent Caching of File
            'Cache-Control: private',
            'Pragma: private',
            'Expires: 0',
        );

        $document->download = $download_count + 1;
        $document->update();

        return response()->download(
            $filepath,
            $newfilename,
            $headers);
    }

    public function getTodoGroups($level_id)
    {
        return view('user.todo.todo_products');
    }
    public function viewTodoReport()
    {

        return view('user.todo.todo_report',['todo_score'=>[]]);
    }
    public function getTodoReport(Request $request)
    {
        $todo_level = User::todoLevel();
        $todo_level_id = $todo_level->todo_id;
        $todo_level_name = $todo_level->todo_title;

        $rec_count = TodoUserScore::query()
            ->select(DB::raw('COUNT(id) AS rec_count'))
            ->where('user_id',User::id())
            ->first()->rec_count;

        if($rec_count>0)
        {

        $todo_score =  TodoItem::join('todos_products','todos_items.product_id','todos_products.id')
            ->join('todos','todos.id','todos_products.todo_id')
            ->select(
                'todos_items.id AS item_id',
                'todos_products.id AS product_id',
                'todos_items.item_title AS item_title',
                'todos_items.item_idx AS item_idx',
                'todos_items.description AS description',
                'todos_items.max_score AS max_score',
                'todos_products.product_title AS product_title',
            )
            ->orderBy('todos_products.id','asc')
            ->orderBy('todos_items.id','asc')
            ->where('todos.id',$todo_level_id)
            ->get()
            ->groupBy(['product_id']);

        if($request->has('startDate'))
        {
            $startDate = Carbon::createFromFormat('Y-m-d',$request->input('startDate'));
            $endDate = Carbon::createFromFormat('Y-m-d',$request->input('endDate'));

        }
        else
        {
//            $endDate = Carbon::now();
//            $startDate = Carbon::now()->subDays(6);

//            $date = now();
//            $dayOfWeek = $date->dayOfWeek;
//            $day = $date->day;
//            $firstDayOfWeek = $day - $dayOfWeek;
//            $lastDayOfWeek = $firstDayOfWeek + 5;
//
//            $startDate = Carbon::createFromFormat('Y-m-d',$date->year.'-'.$date->month.'-'.$firstDayOfWeek);
//            $endDate = Carbon::createFromFormat('Y-m-d',$date->year.'-'.$date->month.'-'.$lastDayOfWeek);

            $date = now();
            $dayOfWeek = $date->dayOfWeek;
            $day = $date->day;

            $firstDayOfWeek = abs($day - $dayOfWeek);
            $lastDayOfWeek = $firstDayOfWeek + 5;

            //return $firstDayOfWeek;

            $startDate = Carbon::createFromFormat('Y-m-d',$date->year.'-'.$date->month.'-'.$firstDayOfWeek);
            $endDate = Carbon::createFromFormat('Y-m-d',$date->year.'-'.$date->month.'-'.$lastDayOfWeek);


            //$max_todo_count = $todo_items_count * $days;


        }

           // $days = $startDate->diffInDays($endDate);
            $days = $startDate->diffInDays($endDate) + 1;


            return view('user.todo.todo_report',
            [
            'rec_count'=>$rec_count,
            'todo_level_title'=>$todo_level_name,
            'todo_score'=>$todo_score,
            'endDate'=>$endDate->format('Y-m-d'),
            'startDate'=>$startDate->format('Y-m-d'),
            'days'=>$days,
        ]);
        }
        else
        {
            $endDate = Carbon::now();
            $startDate = Carbon::now()->subDays(6);
            $days = $startDate->diffInDays($endDate);

            return view('user.todo.todo_report',
                [
                    'rec_count'=>$rec_count,
                    'todo_level_title'=>$todo_level_name,
                    'todo_score'=>[],
                    'endDate'=>$endDate->format('Y-m-d'),
                    'startDate'=>$startDate->format('Y-m-d'),
                    'days'=>$days
                    ]);
        }
    }

    // ------------- patch utilities
    static function hashPassword()
    {
        $users = User::all();
        foreach ($users as $user)
        {
            $user->forceFill([
                'password2'=>Hash::make($user->password)
            ])->save();
            echo($user->password2).'<br/>';
        }

    }
    static function EncryptNames()
    {
        $users = User::all();
        foreach ($users as $user)
        {
            $user->forceFill([
                'name2'=>substr(Crypt::encryptString($user->name),0,255)
            ])->save();
            echo($user->name2).'<br/>';
        }

    }
}

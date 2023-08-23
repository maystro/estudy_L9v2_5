<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MultipleFileUpload extends Controller
{
    //
    public function index()
    {
        return view('v2.admin.lectures.multiple_file_upload');
    }

    public function upload(Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'required|mimes:csv,txt,xlx,xls,pdf,mp4,mp3,jpg,png,gif,jpeg',
            'level-id'=> 'required',
            'lecture-number' => 'required',
            'upload-folder' => 'required',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file' );

            $path = $file->store(config('app.upload-path').'/'.$request->input('upload-folder'));
            $name = $file->getClientOriginalName();

            //create record
            $record = Lecture::query()->create([
                'title'=>File::name($name),
                'original_filename'=>$name,
                'filename'=>File::name($path).'.'.File::extension($path),
                'folder'=> config('app.upload-path').'/'.$request->input('upload-folder'),
                'subject_id'=>1,
                'level_id'=>$request->input('level-id'),
                'lecture_number'=>$request->input('lecture-number'),
                'file_order'=>1,
                'active'=>true,
            ]);
            return response()->json(
                [
                    'success' => true,
                    'data'=>[
                        'id'=> $record->id,
                        'of'=> File::name($path),   //original filename
                        'f' => File::name($path),    //filename
                        'ex'=> File::extension($path),  //extension
                        't' => File::name($name), //title
                        'ln'=> 1,    //lecture number
                        'fo'=> 1,    //file order
                        'a' => true   //active
                    ]
                ]);
        } else {
            return response()->json([
                'success' => false,
                "message" => "Please try again."
            ]);
        }
    }
}

<?php

namespace App\Http\Livewire\Admin\Lectures;

use App\Components\Utils;
use App\Models\Lecture;
use App\Models\Level;
use App\Models\LevelLecturePivot;
use App\Models\PlaylistFiles;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class LectureFileInfo extends Component
{
    public $frm_file_title='';
    public $frm_title='';
    public $frm_lecture_number=0;
    public $frm_file_id=0;
    public $frm_level_title='';

    public $frm_selected_levels=[];
    public $frm_old_selected_levels=[];
    public $frm_un_selected_levels=[];

    public $frm_subject_title='';
    public $frm_subject_id=0;
    public $frm_filename='';
    public $frm_original_filename='';
    public $frm_folder='';
    public $frm_folder_path='';
    public $frm_filesize='';
    public $frm_file_levels=[];
    public $frm_file_icon='';
    public $frm_created_at='';
    public array $frm_file_media_info=[];
    public $frm_selected_playlists=[];
    public $frm_old_selected_playlists=[];
    public $frm_un_selected_playlists=[];

    public $frm_file_ext;
    public $frm_file_size;
    public $frm_file_path;
    public $frm_file_url;
    public $upload_folder_changed=false;

    public $navsPillsBasic=true;
    public $navsPillsSecondary=false;
    public $navsPillsLevels=false;
    public $navsPillsPlaylists=false;

    public $recordChanged=false;
    public $storage_directories=[];

    public $record;

    public $frm_file_playlists=[];

    protected $listeners=[
        'editRecord'=>'editRecord_EventHandler',
        'close'=>'close'
    ];

    public function render()
    {
        return view('livewire.admin.lectures.lecture-file-info');
    }

    public function editRecord_EventHandler($id)
    {
        $this->reset();
        $this->storage_directories  = Utils::getStorageDirectories(config('app.upload-folder'));

        $record = Lecture::query()
            ->join('subjects','subjects.id','=','lectures.subject_id')
            ->join('levels_lectures','lectures.id','=','levels_lectures.lecture_id')
            ->join('levels','levels.id','=','levels_lectures.level_id')
            ->where('lectures.id','=',$id)
            ->get([
                'lectures.id AS lecture_id',
                'lectures.title AS lecture_title',
                'lectures.lecture_number AS lecture_number',
                'lectures.filename AS filename',
                'lectures.folder AS folder',
                'lectures.file_order AS file_order',
                'lectures.original_filename AS original_filename',
                'levels.id AS level_id',
                'levels.title AS level_title',
                'subjects.id AS subject_id',
                'subjects.title AS subject_title',
            ])->first();

        $this->frm_file_title = $record->lecture_title;
        $this->frm_title = $record->lecture_title;
        $this->frm_file_id = $record->lecture_id;
        $this->frm_lecture_number = $record->lecture_number;
        $this->frm_filename = $record->filename;
        $this->frm_folder = $record->folder;
        $this->frm_original_filename = $record->original_filename;
        $this->frm_level_title =
            Arr::join(Lecture::levels($record->lecture_id)
                ->values()
                ->toArray(),',');
        $this->frm_subject_id = $record->subject_id;
        $this->frm_subject_title = $record->subject_title;
        $date = Carbon::parse($record->updated_at);
        $this->frm_created_at= $date->isoFormat('Do MMMM YYYY, h:mm a');

        $filesize = File::size(Storage::path($this->frm_folder.'/'.$this->frm_filename));
        $this->frm_filesize = Utils::readableFileSize($filesize);
        $this->frm_file_icon = Utils::getFileIcon($this->frm_filename,'bx-lg');

        $this->frm_folder_path =Str::afterLast($record->folder,config('app.upload-folder').'/');

        $this->frm_file_path = $record->folder.'/'.$record->filename;
        $this->frm_file_url = Storage::url($record->folder.'/'.$record->filename);

        $filepath = Storage::path($record->folder.'/'.$record->filename);
        $filesize = File::size($filepath);

        $this->frm_file_size = Utils::readableFileSize($filesize);
        if($this->frm_file_ext=='mp3' || $this->frm_file_ext=='mp4')
            $this->frm_file_media_info = Utils::Mp3Info($filepath);
        else
            $this->frm_file_media_info['duration'] = '';

        $playlist_ids= PlaylistFiles::query()
            ->where('lecture_id','=',$record->lecture_id)
            ->get()
            ->pluck('playlist_id')
            ->toArray();

        $selected_levels = LevelLecturePivot::query()
            ->where('lecture_id',$record->lecture_id)
            ->get()
            ->pluck('level_id')
            ->toArray();

        $this->frm_selected_levels = $selected_levels;
        $this->frm_old_selected_levels = $selected_levels;

        $this->frm_selected_playlists=$playlist_ids;
        $this->frm_old_selected_playlists=$playlist_ids;
    }

    public function TabChanged($target)
    {
        $this->navsPillsBasic = false;
        $this->navsPillsSecondary = false;
        $this->navsPillsLevels = false;
        $this->navsPillsPlaylists = false;

        if($target=='navsPillsBasic')
        {
            $this->navsPillsBasic = true;
        }

        if($target=='navsPillsSecondary')
        {
            $this->navsPillsSecondary = true;
        }

        if($target=='navsPillsLevels')
        {
            $this->navsPillsLevels = true;
        }

        if($target=='navsPillsPlaylists')
        {
            $this->navsPillsPlaylists = true;
        }
    }

    public function dataChanged()
    {
        $this->recordChanged = true;
    }

    public function updatedFrmFolderPath()
    {
        $this->upload_folder_changed=true;
    }
    public function updatedFrmSelectedPlaylists()
    {
        $this->recordChanged = true;
    }

    public function save()
    {

        $array_levels_selected = array_diff($this->frm_selected_levels, $this->frm_old_selected_levels);
        $array_levels_deselected = array_diff($this->frm_old_selected_levels, $this->frm_selected_levels);

        $array_selected = array_diff($this->frm_selected_playlists, $this->frm_old_selected_playlists);
        $array_deselected = array_diff($this->frm_old_selected_playlists, $this->frm_selected_playlists);

        //delete unselected
        PlaylistFiles::query()
            ->where('lecture_id','=',$this->frm_file_id)
            ->whereIn('playlist_id',$array_deselected)->delete();

        //delete unselected levels
        LevelLecturePivot::query()
            ->where('lecture_id','=',$this->frm_file_id)
            ->whereIn('level_id',$array_levels_deselected)->delete();

        //create unselected
        foreach ($array_selected as $k=>$value)
        {
            PlaylistFiles::query()->create(
                [
                    'playlist_id'=>$value,
                    'lecture_id'=>$this->frm_file_id,
                    'idx'=>1,
                ]
            );
        }

        //update or create level lecture
        foreach ($array_levels_selected as $k=>$level_id)
        {
            LevelLecturePivot::query()->updateOrCreate(
                ['lecture_id'=>$this->frm_file_id, 'level_id'=>$level_id],
                [],
            );
        }

        // update lecture data
        $lecture = Lecture::query()
            ->find($this->frm_file_id);

        if ($this->upload_folder_changed)
        {
            //dd($this->frm_folder_path);
            Storage::move(
                $lecture->folder.'/'.$lecture->filename,
                config('app.upload-path').'/'.$this->frm_folder_path.'/'.$lecture->filename);
            $lecture->folder = config('app.upload-path').'/'.$this->frm_folder_path;
        }

        $lecture->update([
            'title'=>$this->frm_file_title,
            'subject_id'=>$this->frm_subject_id,
            'lecture_number'=>$this->frm_lecture_number,
        ]);

        $this->recordChanged = false;
        $this->upload_folder_changed=false;
        $this->emitTo('admin.lectures.files-table','refresh');
    }

    public function close(){
        $this->dispatchBrowserEvent('hideModal',['target'=>'#fileInfoModal']);
    }
}

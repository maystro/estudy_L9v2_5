<?php

namespace App\Http\Livewire\Admin\Lectures;

use App\Components\Utils;
use App\Models\Lecture;
use App\Models\Level;
use App\Models\Subject;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use phpDocumentor\Reflection\PseudoTypes\NonEmptyLowercaseString;

class LectureForm extends Component
{
    //form variables
    public $frm_level_id;
    public $frm_subject_id;
    public $frm_lecture_id;
    public $frm_lecture;
    public $frm_lecture_title;
    public $frm_lecture_number;
    public $frm_file_ext;
    public $frm_file_icon;
    public $frm_file_size;
    public $frm_file_path;
    public $frm_file_url;
    public $upload_folder_changed=false;
    /**
     * @var boolean
     */
    public $frm_lecture_active;

    public $level_subjects=[];
    public $storage_directories=[];
    public $upload_folder='';

//    public $frm_selected_level_ids=[];
    public $form_title;
    public $form_mode='update';
    public $auto_index=0;

    protected $rules = [
        'frm_level_id' => 'required|numeric|gt:0',
        'frm_subject_id' => 'required|numeric|gt:0',
        'frm_lecture_title' => 'required|min:3',
    ];

    protected $messages = [
        'frm_lecture_title.min' => 'هذا الحقل لا يقل عن ٣ حروف',
        'frm_lecture_title.required' => 'هذا الحقل مطلوب ولا يقل عن ٣ حروف',
        'frm_level_id.required' => 'لابد من اختيار المادة',
        'frm_subject_id.required' => 'لابد من اختيار المادة',
        'frm_subject_id.gt' => 'لابد من اختيار المادة',
        'frm_level_id.gt' => 'لابد من اختيار المادة',
    ];

    protected $listeners=[
        'editRecord'=>'editRecord_eventHandler'
    ];
    /**
     * @var string[]
     */
    public array $frm_file_media_info;

    public function render()
    {
        return view('livewire.admin.lectures.lecture-form');
    }

    public function updatedUploadFolder()
    {
        $this->upload_folder_changed=true;
    }

    public function editRecord_eventHandler($lecture_id)
    {
        $this->storage_directories  = Utils::getStorageDirectories(config('app.upload-root-folder'));

        $lecture = Lecture::query()->find($lecture_id);
        if($lecture)
        {
            $this->frm_file_ext  = File::extension($lecture->filename);
            $this->frm_file_icon = '';
            switch ($this->frm_file_ext)
            {
                case 'jpg':
                    $this->frm_file_icon="bxs-file-jpg";
                    break;
                case 'pdf':
                    $this->frm_file_icon="bxs-file-pdf";
                    break;
                case 'mp3':
                    $this->frm_file_icon="bxs-user-voice";
                    break;
                case 'mp4':
                    $this->frm_file_icon="bxs-video";
                    break;
                default;break;
            }
//            dd(Str::remove(config('app.'upload-root-folder'').'/',$lecture->frm_folder));

            $this->frm_lecture = $lecture;
            $this->form_title = "تعديل "." ملف : ".$lecture->title;
            $this->upload_folder =Str::remove(config('app.upload-root-folder').'/',$lecture->folder);
            $this->frm_file_path = $lecture->folder.'/'.$lecture->filename;
            $this->frm_file_url = Storage::url($lecture->folder.'/'.$lecture->filename);

            $filepath = Storage::path($lecture->folder.'/'.$lecture->filename);
            $filesize = File::size($filepath);

            $this->frm_file_size = Utils::readableFileSize($filesize);
            if($this->frm_file_ext=='mp3' || $this->frm_file_ext=='mp4')
                $this->frm_file_media_info = Utils::Mp3Info($filepath);
            else
                $this->frm_file_media_info['duration'] = $this->frm_file_ext;

            $this->frm_lecture_title = $lecture->title;
            $this->frm_lecture_id = $lecture_id;
            $this->frm_level_id = $lecture->level_id;
            $this->frm_subject_id = $lecture->subject_id;
            $this->frm_lecture_number = $lecture->lecture_number;
            $this->frm_lecture_active = $lecture->active;
//          $this->frm_selected_level_ids = explode(',',$lecture->levels);
        }
        else
        {
            $this->level_subjects = [];
            $this->frm_subject_id = 0;
        }
    }

    public function save()
    {
        $this->validate();

        $lecture = Lecture::query()->find($this->frm_lecture_id);

        if ($this->upload_folder_changed)
        {
            Storage::move(
                $lecture->folder.'/'.$lecture->filename,
                config('app.upload-root-folder').'/'.$this->upload_folder.'/'.$lecture->filename
            );
            $lecture->folder = config('app.upload-root-folder').'/'.$this->upload_folder;
        }

        $lecture->title = Str::of($this->frm_lecture_title)->trim();
        $lecture->subject_id = $this->frm_subject_id;
        $lecture->level_id = $this->frm_level_id;
        $lecture->lecture_number = $this->frm_lecture_number;
        $lecture->active = $this->frm_lecture_active;
        $lecture->save();
        $this->dispatchBrowserEvent('hideModal',['target'=>'#editLectureModal']);
        $this->emitTo('admin.lectures.lecture-table','recordChanged');
    }

}

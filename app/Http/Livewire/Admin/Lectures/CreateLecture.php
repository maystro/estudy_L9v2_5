<?php

namespace App\Http\Livewire\Admin\Lectures;

use App\Components\Utils;
use App\Models\Lecture;
use App\Models\Level;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Livewire;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class CreateLecture extends Component
{
    use WithFileUploads;

    public $file;
    public $upload_finished=false;

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
    public $iteration;
    /**
     * @var boolean
     */
    public $frm_lecture_active=false;

    public $original_filename;
    public $uploaded_filename;

    public $level_subjects=[];
//    public $frm_selected_level_ids=[];
    public $form_title;
    public $form_mode='update';
    public $auto_index=0;
    /**
     * @var string[]
     */
    public array $frm_file_media_info=[];

    protected $listeners = [
        'save'=>'save',
        'upload_finish','upload_finish'
    ];

    protected $rules = [
        'frm_lecture_title' => 'required|min:3',
        'frm_subject_id' => 'required|numeric|gt:0',
    ];

    protected $messages = [
        'frm_lecture_title.min' => 'هذا الحقل لا يقل عن ٣ حروف',
        'frm_lecture_title.required' => 'هذا الحقل مطلوب ولا يقل عن ٣ حروف',
        'frm_subject_id.required' => 'لابد من اختيار المادة',
        'frm_subject_id.gt' => 'لابد من اختيار المادة',
    ];

    public function render()
    {
        return view('livewire.admin.lectures.create-lecture');
    }

    public function mount()
    {

        $default_level_id = 1;
        $default_lecture_number = 1;

        $this->frm_level_id=$default_level_id;
        $this->frm_lecture_number=$default_lecture_number;

        $this->updatedFrmLevelId();
    }

    public function updatedFrmLevelId()
    {

    }


    public function upload_finish()
    {
        $this->upload_finished = true;
        $this->dispatchBrowserEvent('upload-finish');
        $this->getFileInfo();
    }

    private function getFileInfo()
    {
        if($this->file)
        {
            $this->frm_file_ext = File::extension($this->file->getClientOriginalName());
            $this->frm_file_icon='';
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

            $this->frm_file_path = $this->file->getRealPath();
            $filesize = File::size($this->frm_file_path);
            $this->frm_file_size = Utils::readableFileSize($filesize);
            if($this->frm_file_ext=='mp3' || $this->frm_file_ext=='mp4')
                $this->frm_file_media_info = Utils::Mp3Info($this->frm_file_path);
            else
                $this->frm_file_media_info['duration'] = $this->frm_file_ext;
            $this->frm_lecture_title = File::name($this->file->getClientOriginalName());

            //$this->frm_file_url = Storage::temporaryUrl($this->file,now()->addMinutes(5));

        }
        else
        {
            $this->level_subjects = [];
            $this->frm_subject_id = 0;
        }

    }

    public function createRecord()
    {
        $this->validate();
        $this->uploaded_filename  = $this->file->getFilename();
        //-------- generate now code for file naming
        $now = Carbon::now();
        $now = str_replace('-','',$now->toDateString()).'-'.str_replace(':','',$now->toTimeString());
        //------------------------------------------

        //-------------- File naming ---------------
        $new_filename = File::name($this->uploaded_filename).'-'.$now.'.'.File::extension($this->uploaded_filename);
        $lecture_folder = config('app.upload-public-frm_folder').'/'.config('app.upload-lectures-folder');
        File::move($this->file->getRealPath(),Storage::path($lecture_folder.'/'. $new_filename));
        $this->uploaded_filename = $new_filename;

        //-------------- Save Record ---------------
        $lecture = Lecture::create([
            'subject_id'=>$this->frm_subject_id,
            'lecture_number'=>$this->frm_lecture_number,
            'file_order'=> $this->frm_lecture_number,
            'filename'=>$new_filename,
            'frm_folder'=>config('app.upload-lectures-folder'),
            'title'=>$this->frm_lecture_title,
            'active'=>$this->frm_lecture_active,
            'visit'=>0,
            'download'=>0,
        ]);

        $this->dispatchBrowserEvent('hideModal',['target'=>'#createLectureModal']);
        $this->emitTo('admin.lectures.lecture-table','recordChanged');

        $this->upload_finished = false;
        $this->dispatchBrowserEvent('livewire-upload-reset');
        $this->file=null;
        $this->iteration++;
        $this->reset();

    }

    public function close()
    {
        $this->upload_finished = false;
        $this->dispatchBrowserEvent('livewire-upload-reset');
        $this->file=null;
        $this->iteration++;
        $this->reset();
    }

}

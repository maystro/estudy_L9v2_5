<?php

namespace App\Http\Livewire\Admin\Lectures;

use App\Components\Utils;
use App\Models\Lecture;
use Livewire\Component;

class UploadLectures extends Component
{
    public $upload_finished=false;
    public $upload_started=false;

    public $level_id=0;
    public $lecture_number=0;

    public $storage_directories=[];
    public $upload_folder='';

    protected $listeners = [
        'upload_started'=>'upload_started_handler',
        'upload_finished'=>'upload_finished_handler'
    ];

    public function render()
    {
        return view('livewire.admin.lectures.upload-lectures');
    }
    public function mount()
    {
        $this->storage_directories  = Utils::getStorageDirectories(config('app.upload-folder'));
        $this->lecture_number = Lecture::query()->max('lecture_number')+1;
    }

    public function upload_started_handler()
    {
        $this->upload_started=true;
        $this->upload_finished=false;
    }

    public function upload_finished_handler()
    {
        $this->upload_started=false;
        $this->upload_finished=true;
        $this->emitTo('admin.lectures.files-table','refresh');
    }

}

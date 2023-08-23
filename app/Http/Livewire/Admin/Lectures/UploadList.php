<?php

namespace App\Http\Livewire\Admin\Lectures;

use App\Components\HtmlElement;
use App\Components\Utils;
use App\Models\Lecture;
use App\Models\Level;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadList extends Component
{
    use WithFileUploads;

    public $keys                =[];
    public $files               =[];
    public $original_filename   =[];
    public $uploaded_filename   =[];

    public $selected_level_ids=[];
    public $default_level_id=1;
    public $default_lecture_number=1;

    public $uploads_count = 0;
    public $progressValue = 0;
    public $isUploading = false;

    public $upload_finished=false;
    public $upload_folder='';
    public $storage_directories=[];

    public $uploaded_message;

    private $numberOfUploadedFiles=0;

    protected $listeners =[
        'card-deleted'=>'cardDeletedEventHandler',
        'card-closed'=>'cardClosedEventHandler'
    ];
    public function render()
    {
        return view('livewire.admin.lectures.upload-list',['files'=>$this->files]);
    }
    public function mount()
    {
        $this->storage_directories  = Utils::getStorageDirectories(config('app.upload-folder'));
        $this->upload_folder=$this->storage_directories[0];

        $lectures = Lecture::all();
        if(count($lectures)>0)
        {
            $this->default_lecture_number =
                intval(Lecture::query()->orderByDesc('lecture_number')->first()->lecture_number)+1;
        }
        else
            $this->default_lecture_number = 1;

    }
    public function updatedFiles()
    {
        $this->uploads_count = sizeof($this->files);
        $this->uploaded_message="تم تحميل ".HtmlElement::renderBadge('primary',$this->uploads_count)." ملفات".' , ';
        $this->uploaded_message.="المحاضرة رقم : " . HtmlElement::renderBadge('info',$this->default_lecture_number).' , ';
        $this->uploaded_message.="في المجلد : " . HtmlElement::renderBadge('info',$this->upload_folder).' , ';

        $this->numberOfUploadedFiles = (sizeof($this->files));

        foreach ($this->files as $key=>$file)
        {
            $this->original_filename[]=($file->getClientOriginalName());
            $this->uploaded_filename[]=$file->store('public/media/uploads/tmp');
        }
        $this->upload_finished = true;
        $this->dispatchBrowserEvent('upload_finished');
        $this->toast('info', 'تحميل الملفات', 'تم تحميل الملفات');
    }

    public function saveAll()
    {
        $this->emitTo('admin.lectures.upload-single-lecture','save');
    }

    public function toast($class, $title, $message)
    {
        $this->dispatchBrowserEvent('show_toast',
            [
                'class'=>$class,
                'title'=>$title,
                'message'=>$message
            ]);
    }

    public function cardDeletedEventHandler()
    {
        if($this->uploads_count>0)
        {
            $this->uploads_count --;
            if($this->uploads_count==0)
            {
                $this->uploads_count =0;
                $this->message="لا يوجد ملفات";
                $this->reset();
                $this->reset($this->files);
                $this->files = null;
                $this->dispatchBrowserEvent('reset-card');
            }
            else $this->message="تم تحميل ".$this->uploads_count." ملف / ملفات ";
        }
    }
}

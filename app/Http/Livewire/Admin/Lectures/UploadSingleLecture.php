<?php

namespace App\Http\Livewire\Admin\Lectures;

use App\Components\Utils;
use App\Models\Lecture;
use App\Models\Level;
use App\Models\LevelLecturePivot;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Livewire;

class UploadSingleLecture extends Component
{
    public $lecture_title;
    public $level_id;

    public $selected_level_ids;
    public $old_selected_levels=[];
    public $un_selected_levels=[];

    public $lecture_number;
    public $subject_id ;

    public $original_filename;
    public $uploaded_filename;
    public $file_idx=1;
    public $level_subjects;
    public $file_type='';

    public $instanceId;
    public $recordId;
    public $lectureSaved=false;
    public $currentSaveMode='new';
    public $card_is_active=true;
    public $upload_folder='';
    public $storage_directories=[];
    protected $listeners = [
        'save'=>'save'
    ];

    protected $rules = [
        'subject_id' => 'required|numeric|gt:0',
        'lecture_title' => 'required|min:3',
    ];

    protected $messages = [
        'lecture_title.min' => 'هذا الحقل لا يقل عن ٣ حروف',
        'lecture_title.required' => 'هذا الحقل مطلوب ولا يقل عن ٣ حروف',
        'level_id.required' => 'لابد من اختيار المادة',
        'subject_id.required' => 'لابد من اختيار المادة',
        'subject_id.gt' => 'لابد من اختيار المادة',
    ];

    public function render(): Factory|View|Application
    {
        return view('livewire.admin.lectures.upload-single-lecture');
    }

    public function mount()
    {
        $this->storage_directories  = Utils::getStorageDirectories(config('app.upload-folder'));
        $this->file_type=File::extension($this->uploaded_filename);
    }

    public function hideFlashMessage()
    {
        $this->lectureSaved=false;
        $this->dispatchBrowserEvent('hideFlashMessage');
    }

    public function save ()
    {
        if($this->card_is_active)
        {
            if($this->currentSaveMode=='new')
            {
                $this->recordId = $this->createRecord();
                $this->currentSaveMode='edit';
            }
            else
                $this->updateRecord($this->recordId);
        }
    }
    private function createRecord()
    {
        $this->validate();
        // for storage pah add public/media/....
        $now = Carbon::now();
        $now = str_replace('-','',$now->toDateString()).'-'.str_replace(':','',$now->toTimeString());
        $new_filename = File::name($this->uploaded_filename).'-'.$now.'.'.File::extension($this->uploaded_filename);

        $file_folder = config('app.upload-path').'/'.$this->upload_folder;
        File::move(Storage::path($this->uploaded_filename),Storage::path($file_folder.'/'. $new_filename));

        $this->file_type=File::extension($this->uploaded_filename);
        $this->uploaded_filename = $new_filename;

        $lecture = Lecture::create([
            'subject_id'=>$this->subject_id,
            'level_id'=>$this->level_id,
            'lecture_number'=>$this->lecture_number,
            'file_order'=> $this->file_idx,
            'original_filename'=> $this->original_filename,
            'filename'=>$new_filename,
            'folder'=>$file_folder,
            'title'=>$this->lecture_title,
            'active'=>true,
            'visit'=>0,
            'download'=>0,
        ]);
        $this->lectureSaved=false;

        //update or create level lecture
        foreach ($this->selected_level_ids as $k=>$level_id)
        {
            LevelLecturePivot::query()->updateOrCreate(
                ['lecture_id'=>$lecture->id, 'level_id'=>$level_id],
                [],
            );
        }
        $this->old_selected_levels=$this->selected_level_ids;

        session()->flash('message',['status'=>'success','text'=>'تم الحفظ بنجاح']);
        return $lecture->id;
    }
    private function updateRecord($id)
    {

        if(!$this->lectureSaved) {
            $this->validate();

            $array_levels_selected = array_diff($this->selected_level_ids, $this->old_selected_levels);
            $array_levels_deselected = array_diff($this->old_selected_levels, $this->selected_level_ids);

            $lecture = Lecture::query()->find($id);

            if($lecture)
            {
                $lecture->subject_id=$this->subject_id;
                $lecture->level_id=$this->level_id;
                $lecture->lecture_number=$this->lecture_number;
                $lecture->file_order= $this->file_idx;
                $lecture->title=$this->lecture_title;
                $lecture->active=true;
                $lecture->save();
            }

            //delete unselected levels
            LevelLecturePivot::query()
                ->where('lecture_id','=',$id)
                ->whereIn('level_id',$array_levels_deselected)->delete();

            //update or create level lecture
            foreach ($array_levels_selected as $k=>$level_id)
            {
                LevelLecturePivot::query()->updateOrCreate(
                    ['lecture_id'=>$id, 'level_id'=>$level_id],
                    [],
                );
            }

            $this->lectureSaved=false;
            session()->flash('message',['status'=>'success','text'=>'تم الحفظ بنجاح']);
        }
    }

    public function delete()
    {
        if($this->currentSaveMode=='edit')
        {
            $lecture = Lecture::query()->find($this->recordId);
            if($lecture)
            {
                $filename = Storage::path($lecture->folder.'/'.$lecture->filename);
                unlink($filename);
                $lecture->delete();
            }
        }
        else
            unlink(Storage::path($this->uploaded_filename));
        $this->card_is_active = false;
        $this->emitUp('card-deleted',['file'=>$this->uploaded_filename]);
    }

    public function close()
    {
        if($this->currentSaveMode=='edit')
        {
            $lecture = Lecture::query()->find($this->recordId);
            if($lecture)
            {
                $this->card_is_active = false;
                $this->emitUp('card-deleted');
            }
        }

    }

}

<?php

namespace App\Http\Livewire\Admin\Lectures;

use App\Components\Utils;
use App\Models\Lecture;
use App\Models\Level;
use App\Models\PlaylistFiles;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use function PHPUnit\Framework\returnArgument;

class LectureTable extends Component
{
    private $lectures;
    public $searchStr='';
    public $pagesCount=50;

    public $lecture_number;
    public $subject_id;
    public $level_id;
    public $level_subjects;
    public $file_created_at;
    public $file_type='all';
    public $selectAll = false;
    public $selected_items=[];
    public $filter_text='';
    /**
     * @var array|mixed
     */
    public $filter_info;

    protected $listeners=[
        'recordChanged'=>'recordChanged',
        'playlist_selected'=>'playlist_selected',
        'delete_record'=>'delete_record'
    ];

    use withPagination;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $this->lectures = $this->getLectureBuilder();
        return view('livewire.admin.lectures.lecture-table',['lectures'=>$this->lectures]);
    }

    public function mount($subject_id)
    {
        $this->selected_items=[];
        $this->searchStr='';
        $this->level_id = 0 ;
        $this->subject_id = 0;
        $this->lectures = $this->getLectureBuilder();
    }
     /**
     * @return LengthAwarePaginator
      */
    private function getLectureBuilder(): LengthAwarePaginator
    {
        $this->filter_text = '';
        $this->filter_info = [] ;

        return Lecture::query()
            ->join('subjects','subjects.id','=','subject_id')
            ->join('levels','levels.id','=','level_id')
            ->where(function ($query) {
                if ($this->searchStr != '')
                {
                    $query->where('lectures.title', 'like', '%'.$this->searchStr.'%');
                    $this->filter_info[] = ['k' => 'كلمة البحث', 'v' => $this->searchStr];
                }
                if ($this->lecture_number > 0)
                {
                    $query->where('lecture_number', '=', $this->lecture_number);
                    $this->filter_info[] = ['k' => 'محاضرة رقم :', 'v' => $this->lecture_number];
                }
                if ($this->level_id > 0)
                {
                    $query->where('level_id', '=', $this->level_id);
                    $this->filter_info[] = ['k' => 'فئة', 'v' => Level::query()->find($this->level_id)->title];
                }
                if ($this->subject_id > 0)
                {
                    $query->where('lectures.subject_id', '=', $this->subject_id);
                    $this->filter_info[] = ['k' => 'مادة', 'v' => Subject::query()->find($this->subject_id)->title];
                }
                if ($this->file_created_at !='' && Utils::isDate($this->file_created_at) )
                {
                    $date = Carbon::parse($this->file_created_at)->toDateString();
                    $query->whereDate('lectures.created_at','=', $date );
                    $this->filter_info[] = ['k' => 'تاريخ الإضافة', 'v' => $date];
                }
                if ($this->file_type !='all')
                {
                    $values = [];
                    switch ($this->file_type)
                    {
                        case 'mp3':$values = ['mp3'];break;
                        case 'mp4':$values = ['mp4'];break;
                        case 'pdf':$values = ['pdf'];break;
                        case 'ebook':$values = ['pdf','doc','docx','xls','xlsx'];break;
                        case 'media':$values = ['mp3','mp4'];break;
                        case 'images':$values = ['jpg','png','gif'];break;
                    }
                    foreach ($values as $ext)
                    {
                        $query->where('lectures.filename', 'like', '%'.".$ext");
                    }
                    $this->filter_info[] = ['k' => 'نوع الملف', 'v' => $this->file_type];
                }
            })
            ->orderByDesc('lectures.id')
            ->orderByDesc('lecture_number')
            ->orderByDesc('file_order')
            ->paginate($this->pagesCount,
                [
                'lectures.id',
                'lectures.title AS lecture_title',
                'lectures.lecture_number',
                'lectures.file_order',
                'lectures.filename',
                'lectures.created_at AS lecture_created_at',
                'subjects.title AS subject_title',
                'levels.title AS level_title'
            ]);
    }

    public function editLecture($lecture_id)
    {
        $this->emitTo('admin.lectures.lecture-form','editRecord',$lecture_id);
        $this->dispatchBrowserEvent('showModal',['target'=>'editLectureModal']);
    }

    public function recordChanged()
    {
        $this->lectures = $this->getLectureBuilder();
        $this->dispatchBrowserEvent('show_toast',['message'=>'تم حفظ بيانات الملف','title'=>'تعديل','class'=>'bg-success']);
    }

    public function updatedSelectAll($checked)
    {
        $this->selected_items = [];
        if($checked)
        {
            $this->selected_items = $this->getLectureBuilder()->collect()->values()->pluck('id');
        }
    }

    public function updateLectureOrder($orderIds)
    {
        foreach ($orderIds as $item)
        {
            $lecture = Lecture::query()->find($item['value']);
            $lecture->lecture_number = $item['order'];
            $lecture->save();
        }
    }

    public function playlist_selected($playlist_ids)
    {
        if(($this->selected_items))
        {
            foreach ($this->selected_items as $key=>$lecture_id)
            {
                foreach ($playlist_ids as $playlist_id)
                {
                    $list_files = PlaylistFiles::query()
                        ->where('playlist_id',$playlist_id)
                        ->where('lecture_id',$lecture_id)
                        ->get();
                    if( $list_files->count()==0 )
                    {
                        PlaylistFiles::query()->create([
                            'playlist_id'=>$playlist_id,
                            'lecture_id'=>$lecture_id,
                            'idx'=>PlaylistFiles::query()->where('playlist_id',$playlist_id)->max('idx')+1
                        ]);
                    }
                    else
                    {
                    }
                }
            }

        }
    }

    //------ Show Message to confirm delete
    public function confirm_delete($id)
    {

        $this->dispatchBrowserEvent('confirm_delete',['id'=>$id]);
    }
    //------ Delete record action
    public function delete_record($id)
    {
        $lecture = Lecture::query()->find($id);
        if($lecture)
        {
            $lecture->delete();
                $this->dispatchBrowserEvent('show_toast',
                    [
                        'class'=>'bg-warning',
                        'title'=>'حذف',
                        'message'=>'تم حذف عدد ١ سجل / لات'
                    ]);
        }
    }


}

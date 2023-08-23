<?php

namespace App\Http\Livewire\Admin\Lectures;

use App\Components\Utils;
use App\Enums\Roles;
use App\Models\Export\ExportConfig;
use App\Models\Lecture;
use App\Models\Level;
use App\Models\LevelLecturePivot;
use App\Models\Permission;
use App\Models\Playlist;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetails;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\ErrorHandler\Debug;

class FilesTable extends Component
{
    /*********************************************
     ** DATA TABLE TEMPLATE CODE 1.6
     ** DYNAMIC PROPERTIES , ACTIONS and EVENTS
     * Added : Join tables and custom column names and selection
     * Added : custom field ex name start with custom.field_name
     * Added : Reset sort or reorder due to row index of table
     * Added : String var $LivewireTemplate for dynamic Render call
     * Added : Clickable Cell <td> attached to Click Event
     * Added : navigation menu
     * Added : List sources from database
     * Added : array List sources from code
     * Added : Permissions for actions add , delete , edit , .... in template
     * Fix : optimization joining tables
     * Fix : optimization Config control array and more control
     * Fix : optimization for ->Where query
     * Fix : local key for single table operation
     * Fix : update getCellContent function with $key var to get column $key direct
     ** 9 Sun Oct. 2022 @work
     **********************************************/

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Config = Control for every thing
    private $config = [
        'table' => 'lectures', // 'lecture_level'
        'join' => [
            'levels_lectures' => ['key' => 'lectures.id', 'foreign_key' => 'levels_lectures.lecture_id'],
            'levels' => ['key' => 'levels.id', 'foreign_key' => 'levels_lectures.level_id'],
            'subjects' => ['key' => 'subjects.id', 'foreign_key' => 'lectures.subject_id'],
        ],
        'localPrimaryKey' => 'id',
        'localOrderKey' => 'file_order',
        'primaryKey' => 'lecture_id',
        'orderKey' => 'lecture_order',
        'columns' => [
            'id' => [
                'name' => 'lectures.id',
                'nameAs' => 'lecture_id',
                'label' => 'ID',
                'type' => 'hidden',
                'visible' => false,
                'filtered' => false,
                'sortable' => false,
            ],
            'title' => [
                'name' => 'lectures.title',
                'nameAs' => 'lecture_title',
                'label' => 'المحاضرة',
                'type' => 'string',
                'visible' => true,
                'filtered' => true,
                'sortable' => true,
                'clickable' => true,
            ],
            'lecture_number' => [
                'name' => 'lectures.lecture_number',
                'nameAs' => 'lecture_number',
                'label' => 'رقم',
                'type' => 'numeric',
                'visible' => true,
                'filtered' => true,
                'sortable' => true,
                'clickable' => false,
            ],
            'created_at' => [
                'name' => 'lectures.created_at',
                'nameAs' => 'lecture_created_at',
                'label' => 'تاريخ الإضافة',
                'type' => 'datetime',
                'visible' => true,
                'filtered' => true,
                'sortable' => false,
                'clickable' => false,
            ],
            'subject' => [
                'name' => 'subjects.title',
                'nameAs' => 'subject_title',
                'label' => 'المادة',
                'type' => 'list',
                'visible' => true,
                'datalist' => ['table' => 'subjects', 'key' => 'id', 'text' => 'title'],
                'filtered' => true,
                'sortable' => true,
            ],
            'levels' => [
                'name' => 'levels.title',
                'nameAs' => 'level_title',
                'label' => 'المستوى',
                'type' => 'list',
                'visible' => true,
                'datalist' => ['table' => 'levels', 'key' => 'id', 'text' => 'title'],
                'filtered' => true,
                'sortable' => true,
            ],
            'file_order' => [
                'name' => 'lectures.file_order',
                'nameAs' => 'lecture_order',
                'label' => 'الترتيب',
                'type' => 'string',
                'visible' => true,
                'filtered' => false,
                'sortable' => false,
            ],
            'active' => [
                'name' => 'lectures.active',
                'nameAs' => 'lecture_active',
                'label' => 'تفعيل',
                'type' => 'boolean',
                'visible' => true,
                'filtered' => false,
                'sortable' => false,
                'clickable' => true,
            ],
        ],
        'group_by'=> ['lectures.id'],
        'rows' => [],
        'features' => [
            'enable_create' => true,
            'enable_filter' => true,
            'enable_select' => true,
            'enable_select_all' => true,
            'enable_data_action' => true, //enable for delete and edit
            'enable_edit' => true,
            'enable_delete' => true,
            'enable_reset_sort' => true,
            'enable_paginate' => true,
            'enable_inline_edit' => false, // soon
            'sortable' => true,
            'enable_print' => false,
            'enable_export' => false,
        ],
        'paginate' => [
            'per_page' => 20,
            'pages' => []
        ],
        'page_title' => 'جدول المواد',
    ];
    // Navigation menu operations
    public $navMenu = [
        ['label'=>'نقل إلى مادة أخرى', 'action'=>'assign_to_subject'],
        ['label'=>'نقل إلى قائمة تشغيل', 'action'=>'assign_to_playlist'],
        ['label'=>'divider'],
        ['label'=>'حذف', 'action'=>'delete'],
        ['label'=>'تجميع', 'action'=>'addlevels'],
    ];
    // public prop.
    public $filter = [];
    public $pagesCount = 20;
    public $select_all = false;
    public $sortColumn = null;
    public $sortDirection = 'asc';
    public $selectedItems = [];
    public $filter_info = [];

    protected $LivewireTemplate = 'livewire.admin.lectures.files-table';
    protected $LivewireCreateView = 'admin.lectures.upload-lectures';
    protected $LivewireEditView = 'admin.lectures.lecture-file-info';
    protected $LivewireAssignPlaylistView = 'admin.playlists.assign-to-playlist';

    public $editFormModal = '#fileInfoModal';
    public $createFormModal = '#uploadLecturesModal';

    public $uploadFormModal = '#uploadLecturesModal';
    public $fileInfoFormModal = '#fileInfoModal';

    public $assignToPlaylistFormModal = '#assignToPlaylistFormModal';

    // event declarations
    protected $listeners = [
        'rowOrderChanged' => 'rowOrderChanged_eventHandler',
        'delete_record' => 'deleteRecord_eventHandler',
        'delete_selected' => 'deleteSelected_eventHandler',
        'recordChanged' => 'recordChanged_eventHandler',
        'reset_sort' => 'resetSort_eventHandler',
        'refresh' => '$refresh',
    ];
    // ---------------------------------------

    // --------------- Methods ---------------
    private function dataBuilder($sort_column = null, $direction = 'asc')
    {
        $this->config = Utils::arrayToObject($this->config);
        $this->config->paginate->per_page = $this->pagesCount;

        $this->filter_info = [];

        $table = DB::table($this->config->table);

        /* create joins */
        foreach ($this->config->join as $sub_table => $value) {
            $table = $table->join($sub_table, $value->key, '=', $value->foreign_key);
        }

        $select = Arr::pluck($this->config->columns, 'nameAs', 'name');
        $select_column = [];
        foreach ($select as $key => $item) {
            if( ! Str::startsWith($key, 'custom.'))
                $select_column[] = $key . ' AS ' . $item;
        }

        //dd($select_column);
        if (count($select_column) > 0)
            $table = $table->select($select_column)->distinct();

        if(count($this->config->group_by)>0)
            $table = $table->groupBy($this->config->group_by);

        /* Generate Filter */
        foreach ($this->config->columns as $column) {
            if (isset($this->filter[$column->nameAs])) {
                if ($this->filter[$column->nameAs] != '')
                    switch ($column->type) {
                        case 'string' :
                            $this->filter_info[] = ['k' => $column->label.' يحتوي على ', 'v' => $this->filter[$column->nameAs]];
                            $table = $table->where($column->name, 'LIKE',"%".$this->filter[$column->nameAs]."%");
                            break;
                        case 'array':
                        case 'list':
                            $this->filter_info[] = ['k' => $column->label .' : ', 'v' => $this->filter[$column->nameAs]];
                            $table = $table->where($column->name, '=',$this->filter[$column->nameAs]);
                            break;
                        case 'datetime':
                            $this->filter_info[] = ['k' => $column->label .' : ', 'v' => $this->filter[$column->nameAs]];
                            $date = Carbon::parse($this->filter[$column->nameAs]);
                            $table = $table->whereDate($column->name, '=',$date);
                        case 'numeric':
                            $this->filter_info[] = ['k' => $column->label .' : ', 'v' => $this->filter[$column->nameAs]];
                            $table = $table->where($column->name, '=',$this->filter[$column->nameAs]);
                    }
            }
        }

        if ($sort_column != null)
            $builder = $table
                ->orderBy($sort_column, $direction)
                ->paginate($this->config->paginate->per_page);
        else
            $builder = $table
                ->orderBy($this->config->orderKey, 'asc')
                ->paginate($this->config->paginate->per_page);


        $this->config->rows = json_decode(json_encode($builder->toArray()['data'], true), true);
        $this->config->paginate->pages = $builder;
    }

    public function updatedPagesCount()
    {
        $this->dataBuilder($this->sortColumn, $this->sortDirection);
    }
    public function updatedSelectAll($checked)
    {

        if ($checked) {
            $this->dataBuilder();
            $this->selectedItems = Arr::pluck($this->config->rows, $this->config->primaryKey);
        } else {
            $this->selectedItems = [];
        }
    }
    public function getSelectedItems(): array
    {
        return $this->selectedItems;
    }
    public function sortByColumn($column)
    {
        switch ($column) {
            case 'custom.lectures.count':
                $this->rows = Arr::sort($this->rows, function ($value){
                    return $value['lectures_level_id'];
                });
                break;
            default:
                $this->sortColumn = $column;
                $this->sortDirection = ($this->sortDirection == 'asc') ? 'desc' : 'asc';
                $this->dataBuilder($column, $this->sortDirection);
                break;
        }
    }
    public function resetSort_eventHandler($evt)
    {
        $this->dispatchBrowserEvent('status-message', [
            'state' => 'show',
            'message' => 'جاري إعادة ترتيب السجلات'
        ]);

        foreach (array_values($evt) as $key=>$item)
        {
            $table = DB::table($this->config['table'])
                ->where($this->config['localPrimaryKey'],'=',$item['id'])
                ->update([$this->config['localOrderKey']=>$item['order']]);
        }
//        $this->dispatchBrowserEvent('show_toast', [
//            'message' => 'تم إعادة ترتيب السجلات',
//            'title' => 'ترتيب',
//            'class' => 'bg-success']);

        $this->dispatchBrowserEvent('status-message', [
            'state' => 'hide',
            'message' => 'من فضلك انتظر'
        ]);

    }
    // -----------------Print-------------------------
    public function getPrintHeader(): string
    {
        return '';
    }
    public function getPrintFooter(): string
    {
        return '';
    }
    public function print(): RedirectResponse
    {
        $this->dispatchBrowserEvent('status-message', [
            'state' => 'show',
            'message' => 'من فضلك انتظر'
        ]);
        $this->dataBuilder($this->sortColumn, $this->sortDirection);
        //create header and footer
        $export_config = ExportConfig::query()->first();

        if ($export_config != null) {
            $export_config->update([
                'header' => $this->getPrintHeader(),
                'footer' => $this->getPrintFooter(),
                'column_labels' => implode(',', Arr::pluck($this->config->columns, 'label'))
            ]);
        } else {
            ExportConfig::query()->create([
                'header' => $this->getPrintHeader(),
                'footer' => $this->getPrintFooter(),
                'column_labels' => implode(',', Arr::pluck($this->config->columns, 'label'))
            ]);
        }

        Schema::dropIfExists('export_data');
        Schema::create('export_data', function (Blueprint $table) {
            $table->id();
            foreach ($this->config->columns as $column) {
                $table->string($column->name);
            }
        });

        $exportDataTable = DB::table('export_data');
        $keys = [];
        $values = [];
        foreach ($this->config->rows as $row) {
            foreach ($this->config->columns as $column) {
                $keys[] = $column->name;
                $values[] = $row[$column->name];
            }
            $exportDataTable->insert(array_combine($keys, $values));
            $keys = [];
            $values = [];
        }

        $this->dispatchBrowserEvent('status-message', [
            'state' => 'hide',
            'message' => 'من فضلك انتظر'
        ]);
        return redirect()->route('admin.print');
    }
    // -----------------Print-------------------------
    // ------ Customize Data Render Handler -----------------
    public function getCellContent($row, $column, $key): string
    {
        switch ($key) {
            case 'title':
                $icon = Utils::getFileIcon(
                    Lecture::query()->find($row['lecture_id'])->filename,
                    'bx-md');
                return "<span><span class='text-warning'>$icon</span>".$row[$column->nameAs]."</span>";
            case 'created_at':
                $date = Carbon::parse($row[$column->nameAs]);
                return $date->isoFormat('Do MMMM YYYY, h:mm a');
            case 'active':
                if($row[$column->nameAs]=true)
                    return "<i class='text-success bx bx-check bx-md' ></i>";
                else
                    return "<i class='text-danger bx bx-x bx-md' ></i>";
            case 'levels':
                $levels = Lecture::levels($row['lecture_id']);
                $el = '';
                foreach ($levels as $key=>$value) {
                    $el .= "<small class='badge bg-secondary me-1'>$value</small>";
                }
                return $el;
                default:
                return $row[$column->nameAs]==null?"NULL":$row[$column->nameAs];
        }

    }
    public function getHeaderLabel($column)
    {
        return $column->label;
    }
    // ------------------------------------------------------

    // -----------    Emit Event Handlers     ---------------
    public function recordChanged_eventHandler()
    {
        $this->dispatchBrowserEvent('hideModal', ['target' => '#createPlaylistModal']);
        $this->dispatchBrowserEvent('show_toast', [
            'title' => 'قوائم التشغيل',
            'message' => 'تم حفظ أو تعديل بيانات بنجاح',
            'class' => 'bg-primary',
        ]);
    }
    public function deleteRecord_eventHandler($id)
    {
        /* Manual operation to delete parent row*/
        UserDetails::query()
            ->where('user_id','=',$id)->delete();
        /*--------------------------------------*/

        DB::table($this->config['table'])
            ->where($this->config['localPrimaryKey'], $id)
            ->delete();

        $this->dispatchBrowserEvent('show_toast', [
            'message' => 'تم حذف البيان المطلوب',
            'title' => 'حذف',
            'class' => 'bg-success']);
    }
    public function rowOrderChanged_eventHandler($e)
    {
        //dd($e);
        DB::table($this->config['table'])
            ->where($this->config['localPrimaryKey'], $e['new_rec_id'])
            ->update([$this->config['localOrderKey'] => $e['new_rec_order']]);

        DB::table($this->config['table'])
            ->where($this->config['localPrimaryKey'], $e['old_rec_id'])
            ->update([$this->config['localOrderKey'] => $e['old_rec_order']]);
    }
    public function deleteSelected_eventHandler($ids){
        $this->dispatchBrowserEvent('status-message', [
            'state' => 'show',
            'message' => 'جاري إتمام عملية الحذف ...'
        ]);

        // manual delete related tables
        UserDetails::query()->whereIn('user_id',explode(',',$ids))->delete();

        //default delete
        $rows = DB::table($this->config['table'])->whereIn($this->config['localPrimaryKey'],explode(',',$ids));
        $rows->delete();

        $this->dispatchBrowserEvent('status-message', [
            'state' => 'hide',
        ]);
    }

    // -----------    Local Event Handlers     --------------
    public function actionButtonClicked($action, $id)
    {
        if ($action == 'delete') {
            $this->dispatchBrowserEvent('confirm_delete', ['id' => $id]);
        }
        if ($action == 'edit') {
            $this->emitTo($this->LivewireEditView, 'editRecord', $id);
        }
    }
    public function navMenuItemClicked($menuAction)
    {
        switch ($menuAction)
        {
            case 'addlevels':
                $lectures = Lecture::all();
                foreach ($lectures as $lecture)
                    LevelLecturePivot::query()->updateOrCreate(
                        ['level_id'=>$lecture->level_id,'lecture_id'=>$lecture->id],
                        ['level_id'=>$lecture->level_id,'lecture_id'=>$lecture->id]
                    );
                break;
            case 'assign_to_subject':
                echo ('Download files');
                break;
            case 'delete':
                $this->dispatchBrowserEvent('confirm_delete', [
                    'id' => implode(',',$this->selectedItems),
                    'event' => 'delete_selected'
                ]);
                break;
            case 'assign_to_playlist':
                $this->dispatchBrowserEvent('showModal', [
                    'target'=>$this->assignToPlaylistFormModal
                ]);
                $this->emitTo($this->LivewireAssignPlaylistView,'before_items_added',$this->selectedItems);
                break;

            default:break;
        }
    }
    public function cellClicked($columnName, $id)
    {
        switch ($columnName)
        {
            case 'lectures.title':
                dd("clicked : $columnName of Id : $id");
                break;
            case 'lectures.active':
                // ToDo toggle active field for lecture.id
                break;
            default:break;
        }
    }
    // ------------------------------------------------------

    public function render()
    {
        $this->dataBuilder($this->sortColumn, $this->sortDirection);
        return view($this->LivewireTemplate,
            [
                'data' => $this->config,
                'paginate' => $this->config->paginate->pages
            ]);
    }
}

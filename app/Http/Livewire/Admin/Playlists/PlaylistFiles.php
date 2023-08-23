<?php

namespace App\Http\Livewire\Admin\Playlists;

use App\Components\Utils;
use App\Models\Export\ExportConfig;
use App\Models\Lecture;
use App\Models\Level;
use App\Models\Playlist;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithPagination;

class PlaylistFiles extends Component
{
    /*********************************************
     ** DATA TABLE TEMPLATE CODE 1.2 .. SATISFY 85%
     ** DYNAMIC PROPERTIES , ACTIONS and EVENTS
     * Added : Join tables and custom column names and selection
     * Added : Reset sort or reorder due to row index of table
     * Fix : optimization Config control array and more control
     * Fix : optimization for ->Where query
     * Fix : local key for single table operation
     ** 2 Sun Oct. 2:30pm-5:51pm 2022 @work
     **********************************************/

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Config = Control for every thing
    private $config = [
        'table' => 'playlistfiles',
        'join' => [
            'playlists' => ['key' => 'id', 'foreign_key' => 'playlist_id'],
            'lectures' => ['key' => 'id', 'foreign_key' => 'lecture_id']
        ],
        'localPrimaryKey' => 'id',
        'localOrderKey' => 'idx',
        'primaryKey' => 'playlistfiles_id',
        'orderKey' => 'playlistfiles_id',
        'columns' => [
            'id' => [
                'name' => 'playlistfiles.id',
                'nameAs' => 'playlistfiles_id',
                'label' => 'المحاضرة',
                'type' => 'hidden',
                'filtered' => false,
                'sortable' => false,
            ],
            'idx' => [
                'name' => 'playlistfiles.idx',
                'nameAs' => 'playlistfiles_idx',
                'label' => 'المحاضرة',
                'type' => 'hidden',
                'filtered' => false,
                'sortable' => false,
            ],
            'lecture_id' => [
                'name' => 'lectures.id',
                'nameAs' => 'lecture_id',
                'label' => 'lecture_id',
                'type' => 'hidden',
                'filtered' => false,
                'sortable' => false,
            ],
            'title' => [
                'name' => 'lectures.title',
                'nameAs' => 'lecture_title',
                'label' => 'المحاضرة',
                'type' => 'string',
                'filtered' => true,
                'sortable' => true,
                'clickable' => true,
            ],
            'playlist' => [
                'name' => 'playlists.title',
                'nameAs' => 'playlist_title',
                'label' => 'قائمة',
                'type' => 'list',
                'datalist' => ['table' => 'playlists', 'key' => 'id', 'text' => 'title'],
                'filtered' => true,
                'sortable' => true,
            ],
            'visible_to' => [
                'name' => 'visible_to',
                'nameAs' => 'visible_to',
                'label' => 'مجموعات',
                'type' => 'string',
                'filtered' => false,
                'sortable' => false,
            ],
            'user_role' => [
                'name' => 'user_role',
                'nameAs' => 'user_role',
                'label' => 'المستخدمين',
                'type' => 'string',
                'filtered' => false,
                'sortable' => false,
            ],
        ],
        'rows' => [],
        'features' => [
            'enable_create' => false,
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
            'enable_print' => true,
            'enable_export' => false,
        ],
        'paginate' => [
            'per_page' => 20,
            'pages' => []
        ],
        'page_title' => 'ملفات القوائم',
    ];
    // Navigation menu operations
    public $navMenu = [
        ['label'=>'تحميل', 'action'=>'download'],
        ['label'=>'حذف', 'action'=>'delete'],
    ];
    // public prop.
    public $filter = [];
    public $pagesCount = 20;
    public $select_all = false;
    public $sortColumn = null;
    public $sortDirection = 'asc';
    public $selectedItems = [];
    public $filter_info = [];

    // event declarations
    protected $listeners = [
        'rowOrderChanged' => 'rowOrderChanged_eventHandler',
        'delete_record' => 'deleteRecord_eventHandler',
        'recordChanged' => 'recordChanged_eventHandler',
        'reset_sort' => 'resetSort_eventHandler',
        'refresh' => '$refresh',
    ];
    // ---------------------------------------

    // --------------- Methods ---------------
    public function updatedPagesCount()
    {
        $this->dataBuilder($this->sortColumn, $this->sortDirection);
    }
    private function dataBuilder($sort_column = null, $direction = 'asc')
    {
        $this->config = json_decode(json_encode($this->config, true), false);
        $this->config->paginate->per_page = $this->pagesCount;

        $this->filter_info = [];

        $table = DB::table($this->config->table);

        foreach ($this->config->join as $key => $value) {
            $table = $table->join($key, "$key.id", '=', $this->config->table . "." . $value->foreign_key);
        }

        $select = Arr::pluck($this->config->columns, 'nameAs', 'name');
        $select_column = [];
        foreach ($select as $key => $item) {
            $select_column[] = $key . ' AS ' . $item;
        }

        if (count($select_column) > 0)
            $table = $table->select($select_column);


        foreach ($this->config->columns as $column) {
            if (isset($this->filter[$column->nameAs])) {
                if ($this->filter[$column->nameAs] != '')
                    switch ($column->type) {
                        case 'string' :
                            $this->filter_info[] = ['k' => $column->label.' يحتوي على ', 'v' => $this->filter[$column->nameAs]];
                            $table = $table->where($column->name, 'LIKE',"%".$this->filter[$column->nameAs]."%");
                            break;
                        case 'list':
                            $this->filter_info[] = ['k' => $column->label .' : ', 'v' => $this->filter[$column->nameAs]];
                            $table = $table->where($column->name, '=',$this->filter[$column->nameAs]);
                            break;
                    }
            }
        }

        if ($sort_column != null)
            $builder = $table
                ->orderBy($sort_column, $direction)
                ->paginate($this->config->paginate->per_page);
        else
            $builder = $table
                ->paginate($this->config->paginate->per_page);

        $this->config->rows = json_decode(json_encode($builder->toArray()['data'], true), true);
        $this->config->paginate->pages = $builder;

        //dd($this->config->rows);
    }
    public function updatedFilter()
    {
        //$this->dataBuilder($this->sortColumn, $this->sortDirection);
        //dd($this->filter);
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
        $this->sortColumn = $column;
        $this->sortDirection = ($this->sortDirection == 'asc') ? 'desc' : 'asc';
        $this->dataBuilder($column, $this->sortDirection);
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
    public function getCellContent($row, $column): string
    {
        switch ($column->nameAs) {
            case 'lecture_title':
                $icon = Utils::getFileIcon(
                    Lecture::query()->find($row['lecture_id'])->filename,
                    'bx-md');
                return "<span><span class='text-warning'>$icon</span>".$row[$column->nameAs]."</span>";
                break;
            case 'visible_to':
                $IDs = explode(',', $row['visible_to']);
                $levels = Level::query()->find($IDs);
                $el = '';
                foreach ($levels as $level) {
                    $el .= "<span class='badge bg-info me-1 arFont'>$level->title</span>";
                }
                return $el;
            case 'user_role':
                $roles = explode(',', $row['user_role']);
                $el = '';
                foreach ($roles as $role) {
                    $el .= "<span class='badge bg-secondary me-1'>$role</span>";
                }
                return $el;
                //return "<span class='badge bg-warning'>".$row[$column->name]."</span>";
                break;
            case 'list_type':
                return Playlist::LIST_TYPE[$row[$column->name]];
                break;
            default:
                return $row[$column->nameAs];
                break;
        }
    }
    public function getHeaderLabel($column)
    {
        return $column->label;
    }
    // ------------------------------------------------------

    // ---------------     Event Handlers     ---------------
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
        DB::table($this->config['table'])
            ->where($this->config['localPrimaryKey'], $id)
            ->delete();
        $this->dispatchBrowserEvent('show_toast', [
            'message' => 'تم حذف البيان المطلوب',
            'title' => 'حذف',
            'class' => 'bg-success']);
    }
    public function actionButtonClicked($action, $id)
    {
        if ($action == 'delete') {
            $this->dispatchBrowserEvent('confirm_delete', ['id' => $id]);
        }
        if ($action == 'edit') {
            $lecture_id = \App\Models\PlaylistFiles::query()->find($id)->lecture_id;
            $this->emitTo('admin.lectures.lecture-file-info', 'editRecord', $lecture_id);
        }
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
    public function navMenuClicked($menuAction)
    {
        switch ($menuAction)
        {
            case 'download':
                echo ('Download files');
                break;
            case 'delete':
                echo ('Delete selected files');
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
        return view('livewire.admin.playlists.playlist-files',
            [
                'data' => $this->config,
                'paginate' => $this->config->paginate->pages
            ]);
    }


}

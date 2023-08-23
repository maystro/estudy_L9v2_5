<?php

namespace App\Http\Livewire\Admin\Playlists;

use App\Models\Export\ExportConfig;
use App\Models\Level;
use App\Models\Playlist;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithPagination;

class SelectPlaylistModal extends Component
{
    /*********************************************
     ** DATA TABLE TEMPLATE CODE 1.0 .. SATISFY 85%
     ** DYNAMIC PROPERTIES , ACTIONS and EVENTS
     ** 30 Fri Sept. 7:13am 2022
     **********************************************/

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Config = Key for every thing
    private $config = [
        'table' => 'playlists',
        'primaryKey' => 'id',
        'orderKey' => 'idx',
        'columns' => [
            'title' => [
                'name' => 'title',
                'label' => 'اسم القائمة',
                'type' => 'string',
                'filtered' => true,
                'sortable' => true,
            ],
            'list_type' => [
                'name' => 'list_type',
                'label' => 'نوع القائمة',
                'type' => 'string',
                'filtered' => false,
                'sortable' => false,
            ],
        ],
        'rows' => [],
        'features' => [
            'enable_filter' => true,
            'enable_select' => true,
            'enable_select_all' => true,
            'enable_data_action' => false, //enable for delete and edit
            'enable_edit' => false,
            'enable_delete' => false,
            'enable_paginate' => false,
            'enable_inline_edit' => false, // soon
            'sortable' => false,
            'enable_print' => false,
            'enable_export' => false,
        ],
        'paginate' => [
            'per_page' => 20,
            'pages' => []
        ],
    ];

    // public prop.
    public $filter = [];
    public $pagesCount = 20;
    public $select_all = false;
    public $sortColumn = null;
    public $sortDirection = 'asc';
    public $selectedItems = [];

    public $form_title='قوائم التشغيل';
    // event declarations
    protected $listeners = [
        'rowOrderChanged' => 'rowOrderChanged_eventHandler',
        'delete_record' => 'deleteRecord_eventHandler',
        'recordChanged' => 'recordChanged_eventHandler'
    ];

    // ------------------------------

    // ------ Methods ---------------
    public function updatedPagesCount()
    {
        $this->dataBuilder($this->sortColumn, $this->sortDirection);
    }

    private function dataBuilder($sort_column = null, $direction = 'asc')
    {
        $this->config = json_decode(json_encode($this->config, true), false);
        $this->config->paginate->per_page = $this->pagesCount;

        $table = DB::table($this->config->table)->where(function ($query) {
            foreach ($this->config->columns as $column) {
                if ($column->filtered) {
                    if (isset($this->filter[$column->name])) {
                        if ($column->type == 'string' && $this->filter[$column->name] != '') {
                            return $query->where($column->name, 'like', '%' . $this->filter[$column->name] . '%');
                        }
                    }
                }
            }
            return $query;
        });

        if ($sort_column != null)
            $builder = $table
                ->orderBy($sort_column, $direction)
                ->paginate($this->config->paginate->per_page);
        else
            $builder = $table
                ->paginate($this->config->paginate->per_page);

        $this->config->rows = json_decode(json_encode($builder->toArray()['data'], true), true);
        $this->config->paginate->pages = $builder;

    }

    public function updatedFilter()
    {
        //$this->dataBuilder();
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
        switch ($column->name) {
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
                return $row[$column->name];
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
            ->where($this->config['primaryKey'], $id)
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
            $this->emitTo('admin.playlists.create-playlist', 'editPlaylist', $id);
        }
    }

    public function rowOrderChanged_eventHandler($e)
    {
        DB::table($this->config['table'])
            ->where($this->config['primaryKey'], $e['new_rec_id'])
            ->update([$this->config['orderKey'] => $e['new_rec_order']]);

        DB::table($this->config['table'])
            ->where($this->config['primaryKey'], $e['old_rec_id'])
            ->update([$this->config['orderKey'] => $e['old_rec_order']]);
    }
    // ------------------------------------------------------

    // Modal Action
    public function Accept()
    {
        $this->emit('playlist_selected',$this->selectedItems);
    }

    public function render()
    {
        $this->dataBuilder($this->sortColumn, $this->sortDirection);
        return view('livewire.admin.playlists.select-playlist-modal',
            [
                'data' => $this->config,
                'paginate' => $this->config->paginate->pages
            ]);
    }
}

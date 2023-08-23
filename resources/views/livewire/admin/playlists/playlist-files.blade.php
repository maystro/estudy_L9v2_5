@php
    use Illuminate\Support\Facades\DB;
@endphp
<div>
    <div class="card">

        {{--              Header & Toolbar              --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bx bx-building bx-md"></i>
                {{$data->page_title}}
            </h5>
            <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                @if($data->features->enable_create)
                    <button type="button" class="btn btn-primary me-2"
                            data-bs-toggle="modal"
                            data-bs-target="#createPlaylistModal">
                    <span>
                        <i class="bx bx-plus me-0 me-sm-0"></i>
                        <span class="d-none d-lg-inline-block">إضافة</span>
                    </span>
                    </button>
                @endif
                @if($data->features->enable_print)
                    <button type="button"
                            data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" title="طباعة ما تم اختياره"
                            class="btn btn-label-primary me-2"
                            wire:click="print">
                        <i class="bx bx-printer me-0 me-sm-0"></i>
                    </button>
                @endif
                @if($data->features->enable_export)
                    <button type="button" class="btn btn-label-primary me-0"
                            data-bs-toggle="modal"
                            data-bs-target="#uploadLecturesModal">
                        <i class="bx bx-file-export me-0 me-sm-0"></i>
                    </button>
                @endif
                @if($data->features->enable_reset_sort)
                    <button type="button" class="btn btn-label-primary me-0"
                            data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" title="إعادة ترتيب"
                            onclick="reset_sort()">
                        <i class="bx bx-sort-down me-0 me-sm-0"></i>
                    </button>
                @endif
            </div>
        </div>
        {{-- ****************************************** --}}

        {{--         Generate Filter           --}}
        @if($data->features->enable_filter)
            <div class="card-header pb-0">
                <div class="row">
                    @foreach($data->columns as $column)
                        @if($column->filtered)
                            <div class="col-md-3">
                                @switch($column->type)
                                    @case('string')
                                    <label>تصفية بـ: {{$column->label}}</label>
                                    <input class="form-control"
                                           wire:model="filter.{{$column->nameAs}}"
                                           type="text" placeholder="{{$column->label}}"/>
                                    @break

                                    @case('list')

                                    @php
                                        //get data list
                                        $dataset=[];
                                        $dataset= DB::table($column->datalist->table)->get();
                                        $dataset= json_decode(json_encode($dataset), true);
                                    @endphp
                                    <label>تصفية بـ: {{$column->label}}</label>
                                    <select id="{{$column->nameAs}}"
                                            class="form-select"
                                            wire:model="filter.{{$column->nameAs}}">
                                        <option value="">اختر {{$column->label}}</option>
                                        @foreach($dataset as $item)
                                            <option value="{{$item[$column->datalist->text]}}">
                                                {{$item[$column->datalist->text]}}</option>
                                        @endforeach
                                    </select>
                                    @break
                                @endswitch
                            </div>
                        @endif
                    @endforeach
                </div>
                @if(count($filter_info) > 0)
                    <div class="alert alert-warning d-flex mb-0 mt-2" role="alert">
                        <span class="badge badge-center rounded-pill bg-warning border-label-warning p-3 me-2">
                            <i class="fa fa-filter"></i>
                        </span>
                        <div class="d-flex flex-row justify-content-center align-items-center">
                            @foreach($filter_info as $info)
                                <div class="me-2 text-black">
                                    <strong class="">{{$info['k']}}</strong>
                                    <label>{{$info['v']}}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif
        {{-- *******************************   --}}

        {{--         Navigation menu           --}}
        @if(count($selectedItems)>0)
            <div class="card-header mt-0 mb-0 pt-0 pb-0">
                <nav class="navbar navbar-expand-lg navbar-dark bg-label-secondary mb-2">
                    <div class="container-fluid">
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item dropdown">
                                    <a class="nav-link invert-text-dark dropdown-toggle" href="javascript:void(0)"
                                       id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        إجراءات
                                    </a>
                                    <ul class="dropdown-menu rtl" aria-labelledby="navbarDropdown">
                                        @foreach($this->navMenu as $key=>$menuItem)
                                            @if($menuItem['label']=='divider')
                                                <li><hr class="dropdown-divider"></li>
                                            @else
                                                <li><a class="dropdown-item"
                                                       wire:click="navMenuClicked('{{$menuItem['action']}}')"
                                                       href="javascript:void(0)">{{$menuItem['label']}}</a></li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        @endif
        {{-- *******************************   --}}

        {{--          Generate Table           --}}
        <div class="card-body">
            <div class="table-responsive">
                <table class="table card-table table-hover">
                    <thead>
                    <tr>
                        @if($data->features->enable_select_all)
                            <td>
                                @if($data->features->sortable)
                                    <i class='drag-handle text-muted bx bx-dots-vertical-rounded'></i>
                                @endif
                                <input type="checkbox"
                                       wire:model="select_all" value="all"
                                       class="dt-checkboxes form-check-input">
                            </td>
                        @endif

                        @foreach($data->columns as $column)
                            @if($column->sortable)
                                <th><span wire:click="sortByColumn('{{$column->name}}')" class="cursor-pointer">
                                        <i class='bx bxs-sort-alt bx-xs'></i>
                                        {!! $this->getHeaderLabel($column) !!}
                                        </span>
                                </th>
                            @elseif($column->type!='hidden')
                                <th>{!! $this->getHeaderLabel($column) !!}</th>
                            @endif
                        @endforeach

                        @if($data->features->enable_data_action)
                            <td class="text-center"><i class='bx bxs-bolt'></i></td>
                        @endif

                    </tr>
                    </thead>
                    <tbody id="dataTableBody">
                    @foreach($data->rows as $row)
                        <tr data-id="{{$row[$data->primaryKey]}}" data-order="{{$row[$data->orderKey]}}">
                            @if($data->features->enable_select)
                                <td>
                                    @if($data->features->sortable)
                                        <i class='drag-handle text-muted bx bx-dots-vertical-rounded'></i>
                                    @endif
                                    <input type="checkbox"
                                           wire:model="selectedItems"
                                           value="{{$row[$data->primaryKey]}}"
                                           class="dt-checkboxes form-check-input">
                                </td>
                            @endif

                            @foreach($data->columns as $column)
                                @if($column->type!='hidden')
                                        @if($column->clickable??null)
                                            <td class="cursor-pointer" wire:click="cellClicked('{{$column->name}}','{{$row[$data->primaryKey]}}')">{!! $this->getCellContent($row, $column) !!}</td>
                                        @else
                                            <td>{!! $this->getCellContent($row, $column) !!}</td>
                                        @endif

                                @endif
                            @endforeach

                            @if($data->features->enable_data_action)
                                <td class="text-center">
                                    @if($data->features->enable_edit)
                                        <button type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editPlaylistFile"
                                                wire:click="actionButtonClicked('edit',{{$row[$data->primaryKey]}})"
                                                class="btn btn-icon me-2 btn-primary">
                                            <span class="tf-icons bx bx-pencil"></span>
                                        </button>
                                    @endif
                                    @if($data->features->enable_delete)
                                        <button type="button"
                                                wire:click="actionButtonClicked('delete',{{$row[$data->primaryKey]}})"
                                                class="btn btn-icon me-2 btn-danger">
                                            <span class="tf-icons bx bx-x"></span>
                                        </button>
                                    @endif
                                </td>
                            @endif

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- *******************************   --}}

        {{--       Generate Pagination         --}}
        @if($data->features->enable_paginate)
            <div class="d-flex card-footer justify-content-between align-items-center">

                <div class="d-flex align-items-center">

                    <label class="arFont"> إجمالي السجلات : <span
                            class="badge bg-warning">{{($paginate->total())}}</span></label>

                    <div class="d-flex align-items-center">
                        <label class="text-nowrap me-1 ms-3">إظهار </label>
                        <select id="pages_id"
                                wire:model="pagesCount"
                                class="form-select text-capitalize">
                            <option value="2">2</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label class="text-nowrap me-1 ms-1">لكل صفحة</label>
                    </div>
                </div>

                <div class="align-self-center">
                    {{$paginate->links()}}

                </div>
            </div>
        @endif
        {{-- *******************************   --}}

    </div>
</div>

@include('livewire.partials.data-table-script')

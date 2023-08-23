<div>
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bx bx-building bx-md"></i>
                {{$data->page_title}}
            </h5>
            <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                <button type="button" class="btn btn-primary me-2"
                        data-bs-toggle="modal"
                        wire:click="newPlaylist"
                        data-bs-target="#createPlaylistModal">
                <span>
                    <i class="bx bx-plus me-0 me-sm-0"></i>
                    <span class="d-none d-lg-inline-block">إضافة</span>
                </span>
                </button>
                @if($data->features->enable_print)
                    <button type="button"
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
            </div>
        </div>

        {{--         Generate Filter           --}}
        @if($data->features->enable_filter)
            <div class="card-header">
                <div class="row">
                    @foreach($data->columns as $column)
                        @if($column->filtered)
                            <div class="col-md-3">
                                @switch($column->type)
                                    @case('string')
                                    <label>تصفية بـ: {{$column->label}}</label>
                                    <input class="form-control"
                                           wire:model="filter.{{$column->name}}"
                                           type="text" placeholder="{{$column->label}}"/>
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
        {{--       End Generate Filter         --}}

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
                            @else
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
                                <td>{!! $this->getCellContent($row, $column) !!}</td>
                            @endforeach

                            @if($data->features->enable_data_action)
                                <td class="text-center">
                                    @if($data->features->enable_edit)
                                        <button type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#createPlaylistModal"
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
        {{--        End Generate Table         --}}

        {{-- *******************************   --}}

        {{--       Generate Pagination         --}}
        @if($data->features->enable_paginate)
            <div class="d-flex card-footer justify-content-between align-items-center">

                <div class="d-flex align-items-center">

                    <label class="arFont"> إجمالي السجلات :<span
                            class="badge bg-warning">{{($paginate->total())}}</span></label>

                    <div class="d-flex align-items-center">
                        <label class="text-nowrap me-1 ms-3">إظهار</label>
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
        {{--     End Generate Pagination       --}}

    </div>
</div>

@include('livewire.partials.data-table-script')

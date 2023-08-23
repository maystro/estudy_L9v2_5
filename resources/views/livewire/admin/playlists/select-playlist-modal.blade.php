<div class="modal-content">

    <div class="modal-header">
        <h5 class="modal-title">
            <i class='text-warning bx bx-lg'></i>
            {{$form_title}}
        </h5>
    </div>

    <div class="modal-body">
        @if($data->features->enable_filter)
            <div class="d-flex mb-3 gap-2 justify-content-between align-items-center">
                @foreach($data->columns as $column)
                    @if($column->filtered)
                        @switch($column->type)
                            @case('string')
                            <div class="input-group d-block border-0">
                                <label class="d-block">{{$column->label}}
                                    <input class="form-control d-block"
                                           wire:model="filter.{{$column->name}}"
                                           type="text" placeholder="{{$column->label}}"/>
                                </label>

                            </div>

                            @break
                        @endswitch
                    @endif
                @endforeach
            </div>
        @endif

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
                <tbody id="PlaylistTable">
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

    @if($data->features->enable_paginate)
        <div class="modal-footer justify-content-start">
            <div class="d-flex justify-content-between align-items-center">

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
        </div>
    @endif

    <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إغلاق</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="Accept">موافق</button>
    </div>

</div>

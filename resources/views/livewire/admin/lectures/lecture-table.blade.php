<div>
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">قائمة ملفات المحاضرات</h5>
            <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                <button type="button" class="btn btn-primary me-0"
                        data-bs-toggle="modal"
                        data-bs-target="#uploadLecturesModal">
                    <span>
                        <i class="bx bx-select-multiple me-0 me-sm-0"></i>
                        <span class="d-none d-lg-inline-block">إضافة محاضرات</span>
                    </span>
                </button>
                <button type="button" class="btn btn-primary me-0 disappear"
                        data-bs-toggle="modal"
                        data-bs-target="#createLectureModal">
                    <span>
                        <i class="bx bx-plus me-0 me-sm-0"></i>
                        <span class="d-none d-lg-inline-block">إضافة ملف / محاضرة</span>
                    </span>
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center row py-2 gap-2 gap-md-0">

                <div class="col-md-3">
                    <input class="form-control" wire:model="searchStr" type="text" placeholder="بحث عن ..."/>
                </div>
                <div class="col-md-2 enFont">
                    <input type="text" class="form-control enFont" wire:model="file_created_at" placeholder="YYYY-MM-DD"
                           id="flatpickr-date"/>
                </div>
                <div class="col-md-1">
                    <select id="lecture-number"
                            name="lecture-number"
                            required
                            wire:model="lecture_number"
                            class="form-select d-block">
                        <option value="">محاضرة رقم</option>
                        @for($l=1; $l<=config('app.max-lecture-number'); $l++)
                            <option value="{{$l}}">{{$l}}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2 user_role">
                    <select id="UserRole" wire:model="level_id" class="form-select text-capitalize">
                        <option value="0">كل الفئات</option>
                        @foreach(\App\Models\Level::all() as $level)
                            <option value="{{$level->id}}">{{$level->title}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 user_role">
                    <select id="subject_id"
                            wire:model="subject_id"
                            class="form-select text-capitalize">
                        <option value="0">كل المواد</option>
                        @foreach(\App\Models\Subject::all() as $subject)
                            <option value="{{$subject->id}}">{{$subject->title}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 user_role">
                    <select id="extension"
                            wire:model="file_type"
                            class="form-select">
                        <option value="all">كل الملفات</option>
                        <option value="pdf">pdf</option>
                        <option value="mp3">mp3</option>
                        <option value="mp4">mp4</option>
                        <option value="ebook">ebooks</option>
                        <option value="media">media</option>
                        <option value="images">images</option>
                    </select>
                </div>
            </div>

            @if(count($filter_info) > 0)
                <div class="alert alert-warning d-flex" role="alert">
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
                                    <li><a class="dropdown-item" href="javascript:void(0)">نقل إلى مادة أخرى</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)"
                                           data-bs-toggle="modal"
                                           data-bs-target="#selectPlaylistModal">نقل إلى قائمة تشغيل</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:void(0)"
                                           wire:click="confirm_delete_selected">حذف</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th><input wire:model="selectAll" value="all" type="checkbox"
                                   class="dt-checkboxes form-check-input"></th>
                        <th>المحاضرة</th>
                        <th>تاريخ الإضافة</th>
                        <th>رقم المحاضرة</th>
                        <th>المادة</th>
                        <th>المستوى</th>
                        <th>إجراءات</th>
                    </tr>
                    </thead>
                    <tbody wire:sortable="updateLectureOrder">
                    @foreach($lectures as $lecture)
                        @php
                            $ext = \Illuminate\Support\Facades\File::extension($lecture->filename);
                            $icon='';
                            switch ($ext)
                            {
                                case 'jpg':
                                    $icon="bxs-file-jpg";
                                    break;
                                case 'pdf':
                                    $icon="bxs-file-pdf";
                                    break;
                                case 'mp3':
                                    $icon="bxs-user-voice";
                                    break;
                                default;break;
                            }

                            $date = \Carbon\Carbon::parse($lecture->lecture_created_at)
                        @endphp

                        <tr wire:sortable.item="{{ $lecture->id }}" wire:key="lecture-{{ $lecture->id }}">
                            <h4 wire:sortable.handle></h4>
                            <td>
                                <input type="checkbox"
                                       wire:model="selected_items"
                                       value="{{$lecture->id}}"
                                       class="dt-checkboxes form-check-input">
                            </td>
                            <td>
                                <div class="d-flex flex-row">
                                    <small class="badge bg-label-info me-2"><i class='bx {{$icon}}'></i> {{$ext}}
                                    </small>
                                    <span>{{Str::limit($lecture->lecture_title,20)}}</span>
                                </div>
                            </td>
                            <td>
                                {{$date->isoFormat('Do MMMM YYYY, h:mm a')}}
                            </td>
                            <td>{{$lecture->lecture_number}}</td>
                            <td>{{$lecture->subject_title}}</td>
                            <td>{{$lecture->level_title}}</td>
                            <td>
                                <button type="button" wire:click="editLecture({{$lecture->id}})"
                                        class="btn btn-icon me-2 btn-primary">
                                    <span class="tf-icons bx bx-pencil"></span>
                                </button>
                                @can('delete')
                                    <button type="button"
                                            wire:click="confirm_delete({{$lecture->id}})"
                                            class="btn btn-icon me-2 btn-danger">
                                        <span class="tf-icons bx bx-x"></span>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex card-footer justify-content-between align-items-center">

            <div class="d-flex align-items-center">

                <h4 class="mb-0">
                    <label class="badge bg-warning arFont"> إجمالي السجلات :{{$lectures->total()}}</label>
                </h4>
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
                {{$lectures->links()}}</div>
        </div>

    </div>
</div>

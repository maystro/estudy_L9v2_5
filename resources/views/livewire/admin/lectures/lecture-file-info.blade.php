<div class="modal-content">

    <button type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"></button>
    <div class="modal-header">
        <h5 class="modal-title">
                    <span class="badge bg-label-info me-2">
                        {!! $this->frm_file_icon !!}
                    </span>
            تعديل :
            {{$this->frm_title}}
        </h5>
    </div>

    <div class="modal-body border-bottom">
        <div class="nav-align-top">
            <ul class="nav nav-pills mb-3" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link {{$navsPillsBasic?'active':''}}" role="tab"
                            data-bs-toggle="tab"
                            wire:click="TabChanged('navsPillsBasic')"
                            data-bs-target="#navs-pills-basic" aria-controls="navs-pills-basic"
                            aria-selected="true">معلومات
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link {{$navsPillsSecondary?'active':''}}" role="tab"
                            data-bs-toggle="tab"
                            wire:click="TabChanged('navsPillsSecondary')"
                            data-bs-target="#navs-pills-secondary" aria-controls="navs-pills-secondary"
                            aria-selected="false">بيانات المحاضرة
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link {{$navsPillsLevels?'active':''}}" role="tab"
                            data-bs-toggle="tab"
                            wire:click="TabChanged('navsPillsLevels')"
                            data-bs-target="#navs-pills-levels" aria-controls="navs-pills-levels"
                            aria-selected="false">المستوي
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link {{$navsPillsPlaylists?'active':''}}" role="tab"
                            data-bs-toggle="tab"
                            wire:click="TabChanged('navsPillsPlaylists')"
                            data-bs-target="#navs-pills-playlists" aria-controls="avs-pills-playlists"
                            aria-selected="false"> قوائم تشغيل
                    </button>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade {{$navsPillsBasic?'show active':''}}" id="navs-pills-basic" role="tabpanel">
                    <ul class="list-group list-group-timeline">
                        <li class="list-group-item list-group-timeline-primary">
                            <strong>عنوان المحاضرة : </strong>{{$this->frm_file_title}}
                            <a class="badge bg-label-info rounded ms-2 text-lowercase" href="{{$frm_file_url}}" target="_blank"><i class="bx bx-play-circle"></i> معاينة </a>
                        </li>
                        <li class="list-group-item list-group-timeline-warning">
                            <strong>تاريخ الإضافة : </strong>{{$this->frm_created_at}}
                        </li>
                        <li class="list-group-item list-group-timeline-success"><strong>المستوى
                                : </strong>{{$this->frm_level_title}}</li>
                        <li class="list-group-item list-group-timeline-warning"><strong>المادة
                                : </strong>{{$this->frm_subject_title}}</li>
                        <li class="list-group-item list-group-timeline-info"><strong>اسم الملف
                                : </strong>{{$this->frm_filename}}</li>
                        <li class="list-group-item list-group-timeline-danger"><strong>اسم الملف الأصلي
                                : </strong>{{$this->frm_original_filename}}</li>
                        <li class="list-group-item list-group-timeline-primary"><strong>حجم الملف
                                : </strong>{{$this->frm_filesize}}</li>
                        @if(($frm_file_media_info['duration']??'') !='')
                            <li class="list-group-item list-group-timeline-primary"><strong>مدة التشغيل
                                    : </strong>{{$frm_file_media_info['duration']??''}}</li>
                        @endif
                        <li class="list-group-item list-group-timeline-success"><strong>المسار
                                : </strong>{{$this->frm_folder}}</li>
                    </ul>
                </div>
                <div class="tab-pane fade {{$navsPillsSecondary?'show active':''}}" id="navs-pills-secondary"
                     role="tabpanel">
                    <div class="row g-2">
                        <div class="col-9 mb-3">
                            <label for="nameBasic" class="form-label">عنوان المحاضرة</label>
                            <input type="text"
                                   wire:model="frm_file_title"
                                   wire:change.debounce="dataChanged"
                                   id="nameBasic"
                                   class="form-control" placeholder="ادخل عنوان المحاضرة">
                            @error('frm_lecture_title') <span class="error">{{$message}}</span> @enderror
                        </div>
                        <div class="col-3 mb-3">
                            <label for="nameBasic" class="form-label">رقم المحاضرة</label>
                            <input type="number" min="1" max="100"
                                   class="form-control"
                                   wire:model="frm_lecture_number"
                                   wire:change.debounce="dataChanged"
                                   placeholder="رقم"/>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="defaultFormControlInput" class="form-label">المادة</label>
                                <div class="input-group input-group-merge">
                                    <select id="subject_id"
                                            wire:model="frm_subject_id"
                                            wire:change="dataChanged"
                                            class="form-select text-capitalize">
                                        <option value="0">اختر المادة</option>
                                        @foreach(\App\Models\Subject::all() as $subject)
                                            <option value="{{$subject->id}}">{{$subject->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('frm_subject_id')<span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <label for="defaultFormControlInput" class="form-label">المجلد</label>
                        <div class="col-12 mb-3">
                            <select id="upload-folder"
                                    wire:model="frm_folder_path"
                                    wire:change="dataChanged"
                                    class="form-select">
                                @foreach($storage_directories as $dir)
                                    <option value="{{$dir}}">{{$dir}}</option>
                                @endforeach
                            </select>
                            @error('frm_folder')<span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade {{$navsPillsLevels?'show active':''}}" id="navs-pills-levels"
                     role="tabpanel">
                    <div class="row mt-3">
                        <div class="col">
                            <label for="nameBasic" class="form-label">إدراج في :</label>
                            <div class="list-group">
                                @foreach(\App\Models\Level::all() as $level)
                                    <label class="list-group-item">
                                        <input class="form-check-input me-1" type="checkbox"
                                               wire:model="frm_selected_levels"
                                               wire:change="dataChanged"
                                               value="{{$level->id}}"
                                        >
                                        {{$level->title}}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade {{$navsPillsPlaylists?'show active':''}}" id="navs-pills-playlists"
                     role="tabpanel">
                    <div class="row mt-3">
                        <div class="col">
                            <label for="nameBasic" class="form-label">إدراج في :</label>
                            <div class="list-group">
                                @foreach(\App\Models\Playlist::all() as $playlist)
                                    <label class="list-group-item">
                                        <input class="form-check-input me-1" type="checkbox"
                                               wire:model="frm_selected_playlists"
                                               value="{{$playlist->id}}"
                                        >
                                        {{$playlist->title}}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">

    @if($this->recordChanged)
            <button type="button" class="btn btn-primary" wire:click="save">حفظ التغيرات</button>
        @endif
            <button type="button" class="btn btn-label-secondary" wire:click="close">إغلاق</button>
    </div>

</div>

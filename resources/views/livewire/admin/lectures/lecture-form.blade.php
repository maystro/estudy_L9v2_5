<div class="modal-content">

    <button type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"></button>

    <div class="modal-header">
        <h5 class="modal-title">
            <i class='text-warning bx {{$frm_file_icon}} bx-lg'></i>
            {{$form_title}}
        </h5>
    </div>


    <form>
        <div class="modal-body border-bottom">
            <div class="row g-2">
                <div class="col-9 mb-3">
                    <label for="nameBasic" class="form-label">عنوان المحاضرة</label>
                    <input type="text"
                           wire:model="frm_lecture_title"
                           id="nameBasic"
                           class="form-control" placeholder="ادخل عنوان المحاضرة">
                    @error('frm_lecture_title') <span class="error">{{$message}}</span> @enderror
                </div>
                <div class="col-3 mb-3">
                    <label for="nameBasic" class="form-label">ترتيب المحاضرة</label>
                    <input type="number" min="1" max="100"
                           class="form-control"
                           wire:model="frm_lecture_number"
                           placeholder="رقم"/>
                </div>
            </div>
            <div class="row g-2">
                <div class="col mb-3">
                    <label for="nameBasic" class="form-label">المستوى</label>
                    <select id="frm_level_id"
                            wire:model="frm_level_id"
                            class="form-select">
                        <option value="0">اختر المستوى</option>
                        @foreach(\App\Models\Level::all() as $level)
                            <option value="{{$level->id}}">{{$level->title}}</option>
                        @endforeach
                    </select>
                    @error('frm_level_id') <span class="error">{{$message}}</span> @enderror
                </div>
                <div class="col mb-3">
                    <label for="defaultFormControlInput" class="form-label">المادة</label>
                    <div class="col-sm-12 mb-3">
                        <div class="input-group input-group-merge">
                            <select id="subject_id"
                                    wire:model="frm_subject_id"
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
            </div>
            <div class="row">
                <label for="defaultFormControlInput" class="form-label">المجلد</label>
                <div class="col-12 mb-3">
                    <select id="upload-folder"
                            wire:model="upload_folder"
                            class="form-select">
                        @foreach($storage_directories as $dir)
                            <option value="{{$dir}}">{{$dir}}</option>
                        @endforeach
                    </select>
                    @error('upload_folder')<span class="error">{{ $message }}</span> @enderror
                </div>
            </div>


            <div class="flex-grow-1 row">
                <div class="col-3 text-start">
                    <label class="switch me-0 enFont">
                        <input type="checkbox" class="switch-input" wire:model="frm_lecture_active" checked="">
                        <span class="switch-toggle-slider">
                      <span class="switch-on">
                        <i class="bx bx-check"></i>
                      </span>
                      <span class="switch-off">
                        <i class="bx bx-x"></i>
                      </span>
                    </span>
                        <span class="switch-label"></span>
                    </label>
                </div>
                <div class="col-9 text-end">
                    <h6 class="mb-0">تفعيل</h6>
                    <small class="text-muted">حيث انها لا تظهر في العرض</small>
                </div>
            </div>
            <div class="d-flex flex-grow-1 g-2 justify-content-start mt-3 ltr enFont ">
                <span class="badge bg-label-primary ms-2 text-lowercase"><i class="bx bx-pie-chart"></i> {{$frm_file_size}}</span>
                <span class="badge bg-label-primary ms-2 text-lowercase"><i class="bx bx-time"></i> {{$frm_file_media_info['duration']??''}}</span>
                <a class="badge bg-label-warning ms-2 text-lowercase" href="{{$frm_file_url}}" target="_blank"><i class="bx bx-play-circle"></i></a>
            </div>

        </div>
    </form>
    <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إغلاق</button>
        <button type="button" class="btn btn-primary" wire:click="save">حفظ التغيرات</button>
    </div>

</div>

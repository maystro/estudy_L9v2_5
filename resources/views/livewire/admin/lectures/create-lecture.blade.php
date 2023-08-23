<div class="modal-content">

    <div class="modal-header">
        <h5 class="modal-title">
            <i class='text-warning bx {{$frm_file_icon}} bx-lg'></i>
            إضافة محاضرة / ملف
        </h5>
    </div>

    <div class="modal-body border-bottom">

        <div
            x-init="init()"
            x-data="{
            isUploading :false,
            isUploadFinished :false,
            progress:0}"
            x-on:livewire-upload-start    ="isUploadFinished=false , isUploading = true, progress=0"
            x-on:livewire-upload-finish   ="isUploadFinished=true , isUploading = false, @this.emit('upload_finish'), init()"
            x-on:livewire-upload-error    ="isUploadFinished=false , isUploading = false"
            x-on:livewire-upload-progress ="progress = $event.detail.progress, console.log($event.detail.progress)"
            x-on:livewire-upload-reset ="init()"
        >

            <script>
                function init() {
                    this.isUploading = false;
                    this.isUploadFinished = false;
                    this.progress = 0;
                }
                window.addEventListener('livewire-upload-reset',function(){
                    init();
                })

            </script>


            <div x-show="!$wire.upload_finished">
                <form class="dropzone needsclick dz-clickable" id="dropzone-basic">
                    <div x-show="!isUploading" class="dz-message">
                        قم بالسحب الملفات هنا او اضغط للإضافة
                        <span class="note">
                            يمكنك إضافة ملفات من نوع pdf,excel,word,image,mp3,mp4
                        </span>
                    </div>
                    <div x-show="isUploading" class="dz-message enFont bold ltr" x-text="progress+' %'"></div>

                        <input name="file" type="file" wire:model="file" id="upload{{ $iteration }}"/>
                </form>
            </div>

            <div x-show="isUploading">
                <div class="progress my-3" style="height: 6px;">
                    <div class="progress-bar" role="progressbar"
                         x-bind:style="`width: ${progress}%`"
                         aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>

        </div>

        @if($upload_finished)
            <form>
                <div class="row">
                    <label for="defaultFormControlInput" class="form-label">عنوان المحاضرة</label>
                    <div class="col-8 mb-3">
                        <input type="text"
                               class="form-control"
                               wire:model="frm_lecture_title"
                               placeholder="اكتب عنوان المحاضرة هنا"/>
                        @error('frm_lecture_title') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <input type="number" min="1" max="100"
                                   class="form-control"
                                   wire:model="frm_lecture_number"
                                   placeholder="رقم"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label for="defaultFormControlInput" class="form-label">المستوى</label>
                    <div class="col-12 mb-3">
                        <div class="input-group input-group-merge">
                            <select id="level_id"
                                    wire:model="frm_level_id"
                                    class="form-select text-capitalize">
                                <option value="0">اختر المستوى</option>
                                @foreach(\App\Models\Level::all() as $level)
                                    <option value="{{$level->id}}">{{$level->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label for="defaultFormControlInput" class="form-label">المادة</label>
                    <div class="col-sm-12 mb-3">
                        <div class="input-group input-group-merge">
                            <select id="subject_id"
                                    wire:model="frm_subject_id"
                                    class="form-select text-capitalize">
                                <option value="0">اختر المادة</option>
                                @foreach($level_subjects as $subject)
                                    <option value="{{$subject->id}}">{{$subject->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('frm_subject_id')<span class="error">{{ $message }}</span> @enderror
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
                    <span class="badge bg-label-primary ms-2 text-lowercase"><i class="bx bx-file-blank"></i> {{$frm_file_ext}}</span>
{{--                    <a class="badge bg-label-warning ms-2 text-lowercase" href="{{$frm_file_url}}" target="_blank"><i class="bx bx-play-circle"></i></a>--}}
                </div>

            </form>
        @endif

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" wire:click="close" data-bs-dismiss="modal">إغلاق</button>

        @if($upload_finished)
            <button type="button" class="btn btn-primary" wire:click="createRecord">حفظ</button>
        @endif

    </div>

</div>

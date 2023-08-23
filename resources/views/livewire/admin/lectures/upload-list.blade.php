<div>
    <div
        x-data="{ isUploading :false,
        isUploadFinished :false,
        progress:0}"
        x-on:livewire-upload-start="isUploading = true, isUploadFinished=false"
        x-on:livewire-upload-finish="isUploadFinished=true, isUploading = false"
        x-on:livewire-upload-error="isUploadFinished=false, isUploading = false"
        x-on:livewire-upload-progress="progress = $event.detail.progress"
        @reset-card.window="isUploadFinished=false, isUploading = false">

        <div x-show="!isUploading && !isUploadFinished">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bx bx-upload bx-md"></i>
                                تحميل محاضرات
                            </h5>
                        </div>

                        <div class="card-body demo-vertical-spacing demo-only-element">
                            <form class="dropzone dz-clickable" id="#dropzone-multi" enctype="multipart/form-data">
                                <div class="d-flex justify-content-between align-items-end row py-2 gap-2 gap-md-0">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="lecture_number" class="form-label">محاضرة رقم</label>
                                            <select id="lecture_number" wire:model="default_lecture_number"
                                                    class="form-select text-capitalize">
                                                <option value="">محاضرة رقم</option>
                                                @for($l=1; $l<=config('app.max-lecture-number'); $l++)
                                                    <option value="{{$l}}">{{$l}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="mb-3" wire:ignore wire:key="select">
                                            <label for="select_level" class="form-label">إدارج للمستويات :</label>
                                            <select id="select_level" class="selectpicker w-100"
                                                    placeholder="اختر مستوى أو أكثر"
                                                    data-style="btn-default" multiple data-icon-base="bx"
                                                    data-tick-icon="bx-check text-primary">
                                                @foreach(\App\Models\Level::all() as $level)
                                                    <option value="{{$level->id}}">{{$level->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="upload_folder" class="form-label">المجلد</label>
                                            <select id="upload_folder"
                                                    wire:model="upload_folder"
                                                    class="form-select">
                                                @foreach($storage_directories as $dir)
                                                    <option value="{{$dir}}">{{$dir}}</option>
                                                @endforeach
                                            </select>
                                            @error('upload_folder')<span class="error">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="default_level_id" class="form-label">اختر الملفات</label>
                                            <div class="input-group enFont">
                                                <input type="file" class="form-control"
                                                       multiple
                                                       wire:model="files"
                                                       aria-label="تحميل">
                                                <button class="btn btn-outline-primary" type="button">...</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="isUploading">
        <div class="row justify-content-center my-3">
            <div class="col-xl-6 col-lg-6 col-md-10 col-sm-12 col-6 mb-12">
                <div class="card">
                    <div class="card-body">
                        <span class="d-block fw-semibold mb-2">{!! $uploaded_message !!}</span>
                        <h2 class="card-title mb-2 text-center antialiased" x-text="`${progress}%`"></h2>
                        <h6 class="text-center">  مجلد :
                            <span class="badge bg-label-info mx-2">{{$upload_folder}}</span>
                            المحاضرة رقم
                            <span class="badge bg-label-info mx-2">{{$default_lecture_number}}</span>
                        </h6>
                        <small class="text-muted d-block text-center">جاري التحميل</small>
                        <div class="d-flex align-items-center">
                            <div class="progress w-100 me-2" style="height: 8px;">
                                <div class="progress-bar bg-info"
                                     x-bind:style="`width: ${progress}%`"
                                     role="progressbar"
                                     aria-valuenow="`width: ${progress}%`" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
{{--                        <span x-text="`${progress}%`"></span>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        @if($upload_finished)
            <div class="hr mt-3 mb-3"></div>
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-end align-items-center">
                        <div class="col">
                            <h5>{!! $uploaded_message !!}</h5>
                        </div>
                        <div class="col-2">
                            <button type="submit" wire:click="saveAll" class="btn btn-primary float-end">حفظ الكل
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hr mt-3 mb-3"></div>
        @endif

    </div>

    <div class="row g-4">
        @if(!empty($files))
            @foreach($files as $key=>$file)
                    <livewire:admin.lectures.upload-single-lecture
                        :wire:key="'A-'.$key"
                        :file=$file
                        :instance-id="'id-'.$key"
                        :original_filename="$original_filename[$key]"
                        :lecture_title="\Illuminate\Support\Facades\File::name($file->getClientOriginalName())"
                        :uploaded_filename="$uploaded_filename[$key]"
                        :upload_folder="$upload_folder"
                        :selected_level_ids="$selected_level_ids"
                        :lecture_number="$default_lecture_number"
                    />

            @endforeach
        @endif
    </div>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="bs-toast toast fade" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <div class="me-auto fw-semibold" id="title"></div>
            </div>
            <div class="toast-body" id="message">
            </div>
        </div>
    </div>

</div>

@push('js')
    <script>
        $('#select_level').on('change',function(){
            let values = $(this).val();
            @this.set('selected_level_ids',values);
        })
    </script>
@endpush

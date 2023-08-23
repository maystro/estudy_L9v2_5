<div class="modal-content">

    <div class="modal-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إضافة ملفات / محاضرات</h5>
            <button type="button" class="btn btn-primary me-0 d-none btn-add-file">
                    <span>
                        <i class="bx bx-plus me-0 me-sm-0 md"></i>
                        <span class="d-none d-lg-inline-block"></span>
                    </span>
            </button>
    </div>

    <div class="modal-body border-bottom">
        <form class="needs-validation" id="formValidation">
            <div class="d-flex justify-content-between align-items-end row mb-3">
                <div class="col-md-12">
                    <label for="default_level_id">المجلد</label>
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
            <div class="d-flex justify-content-between align-items-end row">
                    <div class="col-md-4">
                        <label for="upload-level-id">المستوى</label><select id="upload-level-id"
                                                                     name="upload-level-id"
                                                                     required
                                                                     wire:model="level_id"
                                                                     class="form-select d-block">
                            <option value="">اختر المستوى</option>
                            @foreach(\App\Models\Level::all() as $level)
                                <option value="{{$level->id}}">{{$level->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="upload-lecture-number">رقم المحاضرة</label>
                        <select id="upload-lecture-number"
                               name="upload-lecture-number"
                                wire:model="lecture_number"
                               required
                               class="form-select d-block">
                            <option value="0">محاضرة رقم</option>
                            @for($l=1; $l<=intval(config('app.max-lecture-number')); $l++)
                                <option value="{{$l}}">{{$l}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="btn btn-primary d-block btn-add-file">اختر الملفات</div>
                    </div>
                </div>
        </form>

        <form action="{{route('upload')}}"
              method="post" enctype="multipart/form-data"
              class="dropzone dz-clickable disappear" id="my-dropzone">
            @method('post')
            @csrf
            <div class="dz-message needsclick">
                اسحب الملفات هنا أو قم بالضغط لاختيار ملف / ملفات
                <span class="note needsclick">يمكنك تحميل ملفات صوتية وصور وفيديو و Adobe Acrobat pdf , Microsoft Word </span>
            </div>
        </form>
        <div class="" id="cardsHolder"></div>
        <div class="card p-3 mt-3 disappear" id="cardTemplate">
            <div class="row justify-content-center align-items-center">
                <div class="col-1"><i class='text-warning bx bxs-file-pdf bx-md'></i></div>
                <div class="col-9">
                        <label class="upload-status-text">جاري رفع : filename.ext</label>
                        <div class="progress" style="height: 6px">
                            <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                </div>
                <div class="col-2 ltr enFont text-center upload-status-progress">
                    <h5 class="text-bold"><strong class="text ltr enFont"></strong></h5>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary btn-reset-close" data-bs-dismiss="modal">إغلاق</button>
    </div>

</div>

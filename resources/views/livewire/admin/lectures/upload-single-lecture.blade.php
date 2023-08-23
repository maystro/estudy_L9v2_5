<div class="col-xl-6 col-lg-6 col-md-6">
    @if($card_is_active)
    <div class="card">

            <div class="card-header">
                @if(session()->has('message'))
                <div x-data="{ showLectureSaved: true }"
                     x-init="setTimeout(() => showLectureSaved = true, 3000)"
                     x-on:hideFlashMessage="showLectureSaved=false"
                     x-show="showLectureSaved"
                >
                    <div class="alert alert-info d-flex" role="alert">
                        <span class="badge badge-center rounded-pill bg-primary border-label-primary p-3 me-2"><i
                                class="bx bx-check fs-6"></i></span>
                        <div class="d-flex flex-column ps-1">
                            <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">حفظ</h6>
                            <span>تم حفظ المحاضرة و يمكنك التعديل</span>
                        </div>
                    </div>

                </div>
                @endif
                <div class="d-flex align-items-start">
                    <div class="d-flex align-items-start">
                        <h4><i class="bx bx-file bx-md me-3"></i></h4>
                        <div class="me-2">
                            <a href="javascript:;">
                                ملف :
                                 {{$original_filename}}
                            </a>
                            <div class="client-info d-flex align-items-center">
                                <h6 class="mb-0 me-1"> نوع الملف : - </h6>
                                <span>{{\Illuminate\Support\Facades\File::extension($uploaded_filename)}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="align-items-center flex-wrap">
                    <form>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="defaultFormControlInput" class="form-label">عنوان المحاضرة</label>
                                <input type="text"
                                       class="form-control"
                                       wire:model="lecture_title"
                                       wire:change="hideFlashMessage"
                                       placeholder="اكتب عنوان المحاضرة هنا"/>
                                @error('lecture_title') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label for="nameBasic" class="form-label">إدراج في :</label>
                                <div class="list-group">
                                    @foreach(\App\Models\Level::all() as $level)
                                        <label class="list-group-item">
                                            <input class="form-check-input me-1" type="checkbox"
                                                   wire:model="selected_level_ids"
                                                   value="{{$level->id}}"
                                            >
                                            {{$level->title}}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="defaultFormControlInput" class="form-label">رقم المحاضرة</label>
                                    <input type="number" min="1" max="100"
                                           class="form-control"
                                           wire:model="lecture_number"
                                           wire:change="hideFlashMessage"
                                           placeholder="رقم"/>
                                </div>
                                <div class="mb-3">
                                    <label for="defaultFormControlInput" class="form-label">المجلد</label>
                                    <select id="upload-folder"
                                            wire:model="upload_folder"
                                            class="form-select">
                                        @foreach($storage_directories as $dir)
                                            <option value="{{$dir}}">{{$dir}}</option>
                                        @endforeach
                                    </select>
                                    @error('upload_folder')<span class="error">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="defaultFormControlInput" class="form-label">المادة</label>
                                    <select id="subject-id"
                                            wire:model="subject_id"
                                            wire:change="hideFlashMessage"
                                            class="form-select text-capitalize">
                                        <option value="0">اختر المادة</option>
                                        @foreach(\App\Models\Subject::all() as $subject)
                                            <option value="{{$subject->id}}">{{$subject->title}}</option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')<span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body border-top">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button class="btn btn-primary" wire:click="save">حفظ</button>
                    <button class="btn btn-secondary" wire:click="close">اغلاق</button>
                    <button class="btn btn-danger" wire:click="delete">حذف</button>
                </div>
            </div>

    </div>
    @endif
</div>


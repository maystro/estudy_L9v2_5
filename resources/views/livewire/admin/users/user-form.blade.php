<div class="modal-content">

    <button type="button"
            class="btn-close"
            data-bs-dismiss="modal3"
            aria-label="Close3"></button>

    <div class="modal-header">
        <h5 class="modal-title">
                    <span class="badge bg-label-info me-2">
                        {!! $this->form_icon !!}
                    </span>
            {{$this->form_title}}
        </h5>
    </div>

    <div class="modal-body border-bottom">

        @if( session()->has('message'))
            <div class="alert alert-success" role="alert">
                {{session('message')}}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                    <ul class="my-0">
                        @foreach($errors->all() as $error)
                            <li class="text-danger">{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
        @endif

{{--        <div class="align-items-center justify-content-center flex-column w-100 h-px-150" wire:loading wire:loading.class="d-flex">--}}
{{--            <strong>جاري التحميل...</strong>--}}
{{--            <div class="spinner-border my-3" role="status" aria-hidden="true"></div>--}}
{{--        </div>--}}

        <div class="nav-align-top">

             <div class="loader justify-content-center" wire:click="save" wire:loading.delay wire:loading.class="d-flex">
                 <div class="d-flex align-self-center spinner-border my-3" role="status" aria-hidden="true"></div>
             </div>

            <ul class="nav nav-pills mb-3" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link {{$navsUserBasic?'active':''}}" role="tab"
                            data-bs-toggle="tab"
                            wire:click="TabChanged('navsUserBasic')"
                            data-bs-target="#navsUserBasic" aria-controls="navsUserBasic"
                            aria-selected="true">معلومات
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link {{$navsUserSecurity?'active':''}}" role="tab"
                            data-bs-toggle="tab"
                            wire:click="TabChanged('navsUserSecurity')"
                            data-bs-target="#navsUserSecurity" aria-controls="navsUserSecurity"
                            aria-selected="false">الأمان
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link {{$navsUserPermissions?'active':''}}" role="tab"
                            data-bs-toggle="tab"
                            wire:click="TabChanged('navsUserPermissions')"
                            data-bs-target="#navsUserPermissions" aria-controls="navsUserPermissions"
                            aria-selected="false">الصلاحيات
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link {{$navsUserStatus?'active':''}}" role="tab"
                            data-bs-toggle="tab"
                            wire:click="TabChanged('navsUserStatus')"
                            data-bs-target="#navsUserStatus" aria-controls="navsUserStatus"
                            aria-selected="false">حالة الحساب
                    </button>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade {{$navsUserBasic?'show active':''}}" id="navsUserBasic" role="tabpanel">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        @if($user_photo)
                            <img
                                src="{{$user_photo->temporaryUrl()}}"
                                alt="user-avatar"
                                class="d-block rounded"
                                height="100"
                                width="100"
                                id="uploadedAvatar"
                            />
                        @else
                        <img
                            src="{{auth()->user()->getProfilePhoto()}}"
                            alt="user-avatar"
                            class="d-block rounded"
                            height="100"
                            width="100"
                            id="uploadedAvatar"
                        />
                        @endif
                        <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">اختيار صورة</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input
                                    wire:model="user_photo"
                                    type="file"
                                    id="upload"
                                    name="image"
                                    class="account-file-input"
                                    hidden
                                    accept="image/png, image/jpeg"
                                />
                            </label>
                            <button type="button"
                                    wire:click="image_reset()"
                                    class="btn btn-outline-secondary account-image-reset mb-4">
                                <i class="bx bx-reset d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">إلغاء</span>
                            </button>

                            <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 200K</p>
                        </div>
                    </div>
                    <hr class="my-3"/>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="form_name" class="form-label">الاسم</label>
                            <input
                                class="form-control"
                                wire:model="form_name"
                                wire:change="dataChanged"
                                type="text"
                                id="form_name"
                                name="form_name"
                            />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="form_level_id" class="form-label">المستوى</label>
                            <select id="form_level_id"
                                    wire:model="form_level_id"
                                    wire:change="dataChanged"
                                    class="form-select">
                                <option value="0">اختر المستوى</option>
                                @foreach(\App\Models\Level::all() as $level)
                                    <option value="{{$level->id}}">{{$level->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="form_email" class="form-label">البريد الإليكتروني</label>
                            <input
                                wire:model="form_email"
                                wire:change="dataChanged"
                                class="form-control"
                                type="email"
                                placeholder="ادخل بريد اليكتروني صحيح"
                            />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="organization" class="form-label">العمل</label>
                            <input
                                type="text"
                                wire:model="form_job"
                                wire:change="dataChanged"
                                class="form-control"
                                id="job"
                                name="job"
                                placeholder="طالب أو موظف"
                            />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="home_phone">رقم الهاتف</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">EG (+2)</span>
                                <input
                                    type="text"
                                    wire:model="form_phone"
                                    wire:change="dataChanged"
                                    id="phoneNumber"
                                    name="home_phone"
                                    class="form-control"
                                    placeholder="01012345678"
                                />
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="address" class="form-label">العنوان</label>
                            <input type="text" class="form-control" id="address" name="address"
                                   value=""
                                   placeholder="محل الإقامة - او المنطقة"/>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade {{$navsUserSecurity?'show active':''}}" id="navsUserSecurity"
                     role="tabpanel">
                    <div class="alert alert-info" role="alert">
                        يمكنك فقط ادخال رمز عبور جديد
                        وننصح باستخدام إنشاء كلمة مرور تلقائية لضمان قوة الأمان
                    </div>
                    <div class="row">
                        <div class="col-9">
                            <label for="password" class="form-label">كلمة المرور الجديدة</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control"
                                       id="password"
                                       name="password"
                                       wire:change="password_changed"
                                       wire:model="form_password"
                                       autofocus>
                                <button class="btn btn-outline-secondary" type="button" id="button-addon1">
                                    <i class="bx bx-copy"></i></button>
                            </div>
                        </div>
                        <div class="col-3 d-flex justify-content-end">
                            <button
                                wire:click="generate_password"
                                class="d-block align-self-end btn btn-warning">تلقائي
                            </button>
                        </div>
                    </div>
                    @error('form_password')<span class="text-danger">{{ $message }}</span> @enderror
                    <div class="row mt-3">
                        <div class="col-9">
                            <label for="password2" class="form-label">تأكيد كلمة المرور</label>
                            <input
                                class="form-control"
                                type="text"
                                id="password2"
                                name="password2"
                                wire:change="password_changed"
                                wire:model="form_password_confirmation"
                            />

                        </div>
                    </div>
                    <div class="row mt-3">
                        @if($form_password_changed)
                            <div class="col-12 d-flex justify-content-end">
                                <button
                                    wire:click="update_password"
                                    class="d-block btn btn-primary">حفظ كلمة السر
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade {{$navsUserPermissions?'show active':''}}" id="navsUserPermissions"
                     role="tabpanel">
                    <div class="row">
                        <div class="col">
                            <label for="nameBasic" class="form-label">نوع الحساب :</label>
                            <div class="list-group">
                                @foreach(\App\Enums\Roles::asArray() as $key=>$role)
                                    <label class="list-group-item">
                                        <input class="form-check-input me-1" type="radio"
                                               wire:change="dataChanged"
                                               wire:model="form_user_role"
                                               value="{{$role}}"
                                        >
                                        {{$key}}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="col">
                            <label for="nameBasic" class="form-label">الصلاحيات :</label>
                            <div class="list-group">
                                @foreach(\App\Enums\Permissions::asArray() as $key=>$permission)
                                    <label class="list-group-item">
                                        <input class="form-check-input me-1" type="checkbox"
                                               wire:change="dataChanged"
                                               wire:model="form_selected_permissions"
                                               value="{{$permission}}"
                                        >
                                        {{$key}}
                                    </label>
                                @endforeach
                            </div>

                        </div>
                    </div>

                </div>
                <div class="tab-pane fade {{$navsUserStatus?'show active':''}}" id="navsUserStatus"
                     role="tabpanel">
                    <div class="row">
                        <div class="col">
                            <div class="form-check custom-option custom-option-icon">
                                <label class="form-check-label custom-option-content" for="customRadioIcon1">
                                    <span class="custom-option-body">
                                      <i class="bx text-success bx-user-check"></i>
                                      <span class="custom-option-title">تفعيل</span>
                                      <small> تفعيل حساب المستخدم- يمكنه الدخول و التعامل مع الحساب كامل</small>
                                    </span>
                                    <input name="customRadioIcon"
                                           wire:change="dataChanged"
                                           wire:model="form_user_status"
                                           class="form-check-input" type="radio" value="active"
                                           id="customRadioIcon1" {{$form_user_status=='active'?'checked':''}}/>
                                </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-check custom-option custom-option-icon">
                                <label class="form-check-label custom-option-content" for="customRadioIcon2">
                                    <span class="custom-option-body">
                                      <i class="bx text-danger bx-user-minus"></i>
                                      <span class="custom-option-title"> توقف </span>
                                      <small>حجب حساب المستخدم من الدخول ويمكن تشغيله فيما بعد</small>
                                    </span>
                                    <input name="customRadioIcon"
                                           wire:change="dataChanged"
                                           wire:model="form_user_status"
                                           class="form-check-input" type="radio" {{$form_user_status=='blocked'?'checked':''}} value="blocked"
                                           id="customRadioIcon2"/>
                                </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-check custom-option custom-option-icon">
                                <label class="form-check-label custom-option-content" for="customRadioIcon3">
                                    <span class="custom-option-body">
                                      <i class="bx text-warning bx-user-pin"></i>
                                      <span class="custom-option-title"> تعليق </span>
                                      <small>المستخدم يمكنه الدخول ولا يكن لا يستفيد من ميزة الاستماع والتفاعل</small>
                                    </span>
                                    <input name="customRadioIcon"
                                           wire:model="form_user_status"
                                           wire:change="dataChanged"
                                           class="form-check-input" type="radio"
                                           {{$form_user_status=='pending'?'checked':''}}
                                           value="pending"
                                           id="customRadioIcon3"/>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <div x-data="{visible : @entangle('recordChanged')}" x-show="visible" >
            <button type="button" class="btn btn-primary" wire:click="save" wire:target="save" wire:loading.attr="disabled">
                <span wire:loading.remove>حفظ البيانات</span>
                <span wire:loading>حفظ ...</span>
            </button>
        </div>
        <button type="button" class="btn btn-label-secondary" wire:click="reset_data" data-bs-dismiss="modal">إغلاق</button>
    </div>

</div>

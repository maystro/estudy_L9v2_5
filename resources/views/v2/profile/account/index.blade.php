@extends('v2.profile.layouts.app')
@php
    require_once(app_path() . '/Components/Util.php');
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
        {{ Breadcrumbs::render('profile.account') }}
        <div class="row">
        <div class="col-md-12">
            @include('v2.profile.account.account-nav')
            <div class="card mb-4">
                <h5 class="card-header">بيانات شخصية</h5>
                <!-- Account -->
                <form id="formAccountSettings" method="POST"
                      action="{{route('profile.account')}}"
                      enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="card-body">
                    @if(session()->has('message'))
                        @if(session()->get('message')=='error')
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">خطأ</h6>
                                <p class="mb-0">حدث خطأ أثناء عملية الحفظ من فضلك حاول مرة أخري ، أو اتصل بإدارة الموقع</p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                </button>
                            </div>

                        @elseif(session()->get('message')=='success')
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">تحديث</h6>
                                <p class="mb-0">تم تحديث بياناتك بنجاح</p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                </button>
                            </div>
                        @endif
                    @endif

                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img
                            src="{{auth()->user()->getProfilePhoto()}}"
                            alt="user-avatar"
                            class="d-block rounded"
                            height="100"
                            width="100"
                            id="uploadedAvatar"
                        />
                        <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">اختيار صورة</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input
                                    type="file"
                                    id="upload"
                                    name="image"
                                    class="account-file-input"
                                    hidden
                                    accept="image/png, image/jpeg"
                                />
                            </label>
                            <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                <i class="bx bx-reset d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">إلغاء</span>
                            </button>

                            <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                        </div>
                    </div>
                </div>
                <hr class="my-0"/>
                <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="firstName" class="form-label">الاسم</label>
                                <input
                                    class="form-control"
                                    type="text"
                                    id="firstName"
                                    name="name"
                                    value="{{$basicInfo->name}}"
                                    autofocus
                                />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="lastName" class="form-label">الصف الدراسي</label>
                                <input class="form-control" type="text" name="classLevel" id="classLevel"
                                       value="{{\Illuminate\Support\Facades\Auth::user()->levelTitle()}}"
                                       disabled readonly
                                />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">البريد الإليكتروني</label>
                                <input
                                    class="form-control"
                                    type="text"
                                    id="email"
                                    name="email"
                                    disabled
                                    value="{{$basicInfo->email}}"
                                    placeholder="ادخل بريد اليكتروني صحيح"
                                />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="organization" class="form-label">العمل</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="job"
                                    name="job"
                                    value="{{$basicInfo->job}}"
                                    placeholder="طالب أو موظف"
                                />
                            </div>
                            <div class="mb-3 col-md-6" >
                                <label class="form-label" for="home_phone">رقم الهاتف</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">EG (+2)</span>
                                    <input
                                        type="text"
                                        id="phoneNumber"
                                        name="home_phone"
                                        class="form-control"
                                        value="{{@$basicInfo->home_phone}}"
                                        placeholder="01012345678"
                                    />
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="address" class="form-label">العنوان</label>
                                <input type="text" class="form-control" id="address" name="address"
                                       value="{{$basicInfo->address}}"
                                       placeholder="محل الإقامة - او المنطقة"/>
                            </div>

                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">حفظ التعديلات</button>
                            <button type="reset" class="btn btn-outline-secondary">إلغاء</button>
                        </div>
                </div>

                </form>
                <!-- /Account -->
            </div>
            <!-- Delete Account -->
            <div class="card">
                <h5 class="card-header">حذف حسابي</h5>
                <div class="card-body">
                    <div class="mb-3 col-12 mb-0">
                        <div class="alert alert-warning">
                            <h6 class="alert-heading fw-bold mb-1">هل أنت متاكد من حذف حسابك ؟</h6>
                            <p class="mb-0">بعد حذف حسابك لا يمكنك الرجوع لنفس الحساب مرة أخرى ، هل أنت متأكد ؟</p>
                        </div>
                    </div>
                    <form id="formAccountDeactivation" onsubmit="return false">
                        <div class="form-check mb-3">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="accountActivation"
                                id="accountActivation"
                            />
                            <label class="form-check-label" for="accountActivation">أنا متاكد من إتمام عملية الحذف</label>
                        </div>
                        <button type="submit" class="btn btn-danger deactivate-account">حذف الحساب</button>
                    </form>
                </div>
            </div>
            <!-- /Delete Account -->

        </div>
    </div>
@endsection

@push('js')
    <!-- Page JS -->
    <script src="{{ asset ('assets/js/pages-account-settings-account.js')}}"></script>
@endpush

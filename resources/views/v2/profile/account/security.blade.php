@extends('v2.profile.layouts.app')

@section('content')
    {{ Breadcrumbs::render('profile.account.security') }}
    <div class="row">
        <div class="col-md-12">

            @include('v2.profile.account.account-nav')
            <div class="card mb-4">
                <h5 class="card-header">تغيير كلمة المرور</h5>
                <!-- Account Security -->
                <form id="formAccountSettings" method="POST"
                      action="{{route('profile.account.security')}}"
                      enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="card-body">
                        @if(session()->has('success'))
                            @if(!session()->get('success'))
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">خطأ أثناء
                                        التحديث</h6>
                                    <p class="mb-0">{{session()->get('message')}}</p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    </button>
                                </div>
                            @elseif(session()->get('success'))
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">تحديث</h6>
                                    <p class="mb-0">تم تحديث بياناتك بنجاح</p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    </button>
                                </div>
                            @endif
                        @endif
                        <!-- Change Password -->
                        <div class="row">
                            <div class="mb-3 col-md-6 form-password-toggle">
                                <label class="form-label" for="currentPassword">كلمة المرور الحالية</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" name="currentPassword"
                                           id="currentPassword"
                                           placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"/>
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6 form-password-toggle">
                                <label class="form-label" for="newPassword">كلمة المرور الجديدة</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" id="newPassword" name="newPassword"
                                           placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"/>
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>

                            <div class="mb-3 col-md-6 form-password-toggle">
                                <label class="form-label" for="confirmPassword">تاكيد كلمةا لمرور</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" name="confirmPassword"
                                           id="confirmPassword"
                                           placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"/>
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="col-12 mb-4">
                                <p class="fw-semibold mt-2">شروط كلمة المرور</p>
                                <ul class="ps-3 mb-0">
                                    <li class="mb-1">
                                        عدد الحروف لا يقل عن ٨ حروف - وكلما كان اكثر كان أفضل
                                    </li>
                                    <li class="mb-1">حرف واحد على الأقل lowercase</li>
                                    <li>لابد أن تحتوي علي رموز وأرقام</li>
                                </ul>
                            </div>
                            <div class="col-12 mt-1">
                                <button type="submit" class="btn btn-primary me-2">حفظ التغيير</button>
                                <button type="reset" class="btn btn-label-secondary">إلغاء</button>
                            </div>
                        </div>
                        <!--/ Change Password -->
                    </div>
                </form>
                <!-- /Account Security -->
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Page JS -->
    <script src="{{ asset ('assets/js/pages-account-settings-security.js')}}"></script>
@endpush

@extends('v2.layouts.guest')

@push('css')
    <!-- Page CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap');
        body
        {
            font-family: 'Tajawal', sans-serif !important;
        }
        #template-customizer
        {
            display: none !important;
        }
    </style>
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset ('assets/vendor/css/pages/page-auth.css') }}">
    <!-- Helpers -->
    <script src="{{ asset ('assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ asset ('assets/vendor/js/template-customizer.js') }}"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset ('assets/js/config.js') }}"></script>
@endpush


<!-- Content -->
@section('content')

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">

                <!-- Register Card -->
                <div class="card">
                    <x-jet-validation-errors class="mb-3" />
                    <div class="card-body">

                        <!-- Logo -->
                        <h4 class="mb-2 text-center">المدرسة الربانية 🚀</h4>
                        <p class="mb-4 text-center">انشاء حساب في منصة التعليم عن بُعد</p>
                        <!-- /Logo -->

                        <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">الإسم</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="ادخل اسمك هنا" autofocus>
                            </div>

                            <div class="mb-3">
                                @php
                                $levels = \App\Models\Level::query()->where('is_public','=',true)->get();
                                @endphp
                                <label for="level" class="form-label">اختر المستوى</label>
                                <select id="level"  class="form-control" name="level">
                                    @foreach($levels as $level)
                                        <option value="{{$level->id}}">{{$level->title}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="mb-3">
                                <label for="email" class="form-label">البريد الإليكتروني</label>
                                <input type="text" class="form-control" id="email" name="email"
                                       placeholder="البريد الاليكتروني - حروف انجليزية فقط">
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password">كلمة المرور</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                           placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                           aria-describedby="password"
                                           required autocomplete="new-password"
                                    />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password_confirmation">تأكيد كلمة المرور</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password_confirmation" class="form-control" name="password_confirmation"
                                           placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                           aria-describedby="password"
                                           required autocomplete="new-password"
                                    />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="inv_code">كود التفعيل</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" id="inv_code" class="form-control" name="inv_code"
                                           placeholder="أدخل كود التفعيل - حروف انجليزية فقط"
                                           aria-describedby="password"
                                           required autocomplete="new-password"
                                    />
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms">
                                    <label class="form-check-label" for="terms-conditions">
                                        تأكيد أنك
                                        <a href="javascript:void(0);">لست روبوت - أنا إنسان</a>
                                    </label>
                                </div>
                            </div>
                            <button class="btn btn-primary d-grid w-100">
                                إنشاء حساب
                            </button>
                        </form>

                        <p class="text-center">
                            <span>لدي حساب بالفعل</span>
                            <a href="{{route('login')}}">
                                <span>قم بالدخول من هنا</span>
                            </a>
                        </p>

                        <div class="divider my-4">
                            <div class="divider-text">or</div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <a href="{{route('auth.redirect','facebook')}}" class="btn btn-icon btn-label-facebook me-3">
                                <i class="tf-icons bx bxl-facebook"></i>
                            </a>

                            <a href="{{route('auth.redirect','google')}}" class="btn btn-icon btn-label-google-plus me-3">
                                <i class="tf-icons bx bxl-google-plus"></i>
                            </a>

                            <a href="javascript:;" class="btn btn-icon btn-label-twitter">
                                <i class="tf-icons bx bxl-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Register Card -->
            </div>
        </div>
    </div>

@endsection
<!-- / Content -->

@push('scripts')
    <!-- Page JS -->
    <script src="{{ asset ('assets/js/pages-auth.js') }}"></script>
@endpush

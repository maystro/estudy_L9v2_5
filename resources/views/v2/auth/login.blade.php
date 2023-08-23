@extends('v2.layouts.guest')

@push('css')
    <!-- Page CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap');

        body {
            font-family: 'Tajawal', sans-serif !important;
        }

        #template-customizer {
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
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="{{route('index')}}" class="app-brand-link gap-2">
                                <span class="app-brand-text demo text-body fw-bolder">المدرسة الربانية</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-2">مرحباً بك 👋</h4>
                        <p class="mb-4">من فضلك قم بالدخول إلى حسابك</p>

                        <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">المستخدم</label>
                                <input type="text"
                                       class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email"
                                       name="email"
                                       placeholder="الإيميل أو إسم المستخدم" autofocus
                                       value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">كلمة المرور</label>
                                    <a href="{{route('password.request')}}">
                                        <small>نسيت كلمة السر</small>
                                    </a>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password"
                                           class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                           name="password"
                                           placeholder="كلمة المرور هنا"
                                           aria-describedby="password"/>
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="remember"
                                           id="remember-me">
                                    <label class="form-check-label" for="remember-me">
                                        تـذكرني
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">دخول الحساب</button>
                            </div>
                        </form>

                        <p class="text-center">
                            <span>مستخدم جديد ؟</span>
                            <a href="{{route('register')}}">
                                <span>انشئ حساب جديد</span>
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
                <!-- /Register -->
            </div>
        </div>
    </div>
@endsection
<!-- / Content -->

@push('scripts')
    <!-- Page JS -->
    <script src="{{ asset ('assets/js/pages-auth.js') }}"></script>
@endpush

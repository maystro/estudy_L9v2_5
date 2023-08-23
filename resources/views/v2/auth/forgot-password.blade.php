@extends('v2.layouts.guest')

@push('css')
    <!-- Page CSS -->
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
                <!-- Password Request -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="{{route('home')}}" class="app-brand-link gap-2">
                                <span class="app-brand-text demo text-body fw-bolder">المدرسة الربانية</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        @error('email')
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <p class="mb-0">البريد الاليكتروني غير صحيح</p>
                        </div>
                        @enderror

                        @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <p class="mb-0">تم إرسال الرابط </p>
                                <p class="mb-0">يرجى مراجعة بريدك لاتمام باقي الخطوات </p>
                            </div>
                        @else
                            <h4 class="mb-2">هل نسيت كلمة المرور ؟ 🔒</h4>
                            <p class="mb-4">لا يوجد مشكلة ، سنقوم بارسال رابط إعادة تعيين كلمة المرور الي بريدك الاليكتروني</p>
                            <form id="formAuthentication" class="mb-3" action="{{ route('password.request') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">البريد الاليكتروني</label>
                                    <input type="text"
                                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email"
                                           name="email"
                                           placeholder="الإيميل أو إسم المستخدم" autofocus
                                           value="{{ old('email') }}" required>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary d-grid w-100" type="submit">ارسل الرابط إلى البريد</button>
                                </div>
                            </form>
                        @endif

                        <div class="text-center">
                            <a href="{{route('login')}}" class="d-flex align-items-center justify-content-center">
                                <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                                رجوع إلى صفحة الدخول
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Request -->
            </div>
        </div>
    </div>
@endsection
<!-- / Content -->

@push('scripts')
    <!-- Page JS -->
    <script src="{{ asset ('assets/js/pages-auth.js') }}"></script>
@endpush

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
                <!-- Password Reset -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="{{route('home')}}" class="app-brand-link gap-2">
                                <span class="app-brand-text demo text-body fw-bolder">المدرسة الربانية</span>
                            </a>
                        </div>
                        <!-- /Logo -->
{{--                        @error('email')--}}
{{--                        <div class="alert alert-danger alert-dismissible" role="alert">--}}
{{--                            <p class="mb-0">البريد الاليكتروني غير صحيح</p>--}}
{{--                        </div>--}}
{{--                        @enderror--}}
                        @if($errors->any())
                            @foreach($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <p class="mb-0">{{$error}}</p>
                                </div>
                            @endforeach
                        @endif

                        @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <p class="mb-0">تم إرسال الرابط </p>
                                <p class="mb-0">يرجى مراجعة بريدك لاتمام باقي الخطوات </p>
                            </div>
                        @else
                            <h4 class="mb-2">إعادة إدخال كلمة المرور 🔒</h4>
                            <p class="mb-4">بريد <span class="fw-bold">{{$email}}</span></p>
                            <form id="formAuthentication" class="mb-3" action="{{route('password.update')}}" method="POST">
                                @csrf
                                <input type="hidden" name="token" value="{{$token}}">
                                <input type="hidden" name="email" value="{{$email}}">
                                <div class="mb-3 form-password-toggle">
                                    <label class="form-label" for="password">كلمة المرور الجديدة</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password"
                                               class="form-control" name="password" placeholder="••••••••" aria-describedby="password" />
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                </div>
                                <div class="mb-3 form-password-toggle">
                                    <label class="form-label" for="confirm-password">تأكيد كلمة المرور</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="confirm-password" class="form-control" name="password_confirmation" placeholder="••••••••" aria-describedby="password" />
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                </div>
                                <button class="btn btn-primary d-grid w-100 mb-3">
                                    ضبط كلمة المرور الجديدة
                                </button>
                                <div class="text-center">
                                    <a href="{{route('login')}}" class="d-flex align-items-center justify-content-center">
                                        <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                                        رجوع إلى صفحة الدخول
                                    </a>
                                </div>
                            </form>
                        @endif

                    </div>
                </div>
                <!-- / Password Reset  -->
            </div>
        </div>
    </div>
@endsection
<!-- / Content -->

@push('scripts')
    <!-- Page JS -->
    <script src="{{ asset ('assets/js/pages-auth.js') }}"></script>
@endpush

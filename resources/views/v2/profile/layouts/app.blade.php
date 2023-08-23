<!DOCTYPE html>
<!-- beautify ignore:start -->
<html lang="ar" class="layout-navbar-fixed layout-menu-fixed "
      dir="rtl"
      data-theme="theme-default"
      data-assets-path="{{asset('assets') }}/" data-template="vertical-menu-template') }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>إعدادات الحساب - حسابي | المدرسة الربانية - منصة التعلم عن بعد</title>

    <meta name="description" content="المدرسة الربانية - منصة التعلم عن بعد" />
    <meta name="keywords" content="المدرسة, منصة, التعلم عن بعد, الربانية">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset ('landing/assets/img/logo.png')}}" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset ('assets/vendor/fonts/boxicons.css')}}" />
    <link rel="stylesheet" href="{{ asset ('assets/vendor/fonts/fontawesome.css')}}" />
    <link rel="stylesheet" href="{{ asset ('assets/vendor/fonts/flag-icons.css')}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset ('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset ('assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset ('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/typeahead-js/typeahead.css')}}" />

    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">

    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/typeahead-js/typeahead.css')}}" />
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/animate-css/animate.css')}}" />
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset ('assets/vendor/css/pages/page-profile.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset ('assets/vendor/js/helpers.js')}}"></script>
    <script src="{{ asset ('assets/vendor/js/template-customizer.js')}}"></script>
    <script src="{{ asset ('assets/js/config.js')}}"></script>
    <!-- beautify ignore:end -->

    <style>
        /*<!-- Fonts -->*/

        @font-face {
            src: url("{{asset('assets/fonts/open_sans/OpenSans-VariableFont_wdth,wght.ttf')}}");
            font-family: 'Open Sans'
        }

        @font-face {
            src: url("{{asset('assets/fonts/tajawal/Tajawal-Regular.ttf')}}");
            font-family: 'Tajawal'
        }
        body
        {
            font-family: 'Tajawal', sans-serif; !important;
        }
        .badge
        {
            font-family: "Open Sans",sans-serif; !important;
        }
        .enFont
        {
            font-family: "Open Sans",sans-serif; !important;
        }
        .arFont
        {
            font-family: 'Tajawal', sans-serif; !important;
        }
        #template-customizer
        {
            display: none !important;
        }
        .prevent-select {
            -webkit-user-select: none; /* Safari */
            -ms-user-select: none; /* IE 10 and IE 11 */
            user-select: none; /* Standard syntax */
        }
    </style>

    @stack('style')

    @livewireStyles

</head>

<body>

<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar  ">

    <div class="layout-container">
        <!-- Menu -->
        @include('v2.profile.layouts.menu')
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">

            <!-- Navbar -->
            @include('v2.profile.layouts.navbar')
            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                <!-- Content -->
                @yield('content')
                <!-- / Content -->
                </div>

                <!-- Footer -->
                @include('v2.profile.layouts.footer')
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

    </div>
    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>

</div>
<!-- / Layout wrapper -->
@stack('modals')

<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="{{ asset ('assets/vendor/libs/jquery/jquery.js')}}"></script>
<script src="{{ asset ('assets/vendor/libs/popper/popper.js')}}"></script>
<script src="{{ asset ('assets/vendor/js/bootstrap.js')}}"></script>
<script src="{{ asset ('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
<script src="{{ asset ('assets/vendor/libs/hammer/hammer.js')}}"></script>
<script src="{{ asset ('assets/vendor/libs/i18n/i18n.js')}}"></script>
<script src="{{ asset ('assets/vendor/libs/typeahead-js/typeahead.js')}}"></script>
<script src="{{ asset ('assets/vendor/js/menu.js')}}"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset ('assets/vendor/libs/datatables/jquery.dataTables.js')}}"></script>
<script src="{{ asset ('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{ asset ('assets/vendor/libs/datatables-responsive/datatables.responsive.js')}}"></script>
<script src="{{ asset ('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js')}}"></script>
<script src="{{ asset ('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.js')}}"></script>

<!-- Vendors JS -->
<script src="{{ asset ('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{ asset ('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{ asset ('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{ asset ('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
<script src="{{ asset ('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{ asset ('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
<script src="{{ asset ('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>

<!-- Main JS -->
<script src="{{ asset ('assets/js/main.js')}}"></script>

@stack('js')

@livewireScripts

</body>
</html>

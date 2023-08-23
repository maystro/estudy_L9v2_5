<!DOCTYPE html>

<html lang="ar"
      class="dark-style"
      dir="rtl"
      data-theme="theme-default"
      data-assets-path="{{asset('assets')}}/"
      data-base-url="https://estudy.yaqob.com"
      data-framework="e-Study" data-template="blank-menu-theme-default-dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>المدرسة الربانية | منصة التعليم عن بعد</title>
    <meta name="description" content="المدرسة الربانية" />
    <meta name="keywords" content="عن بعد, التعليم, الربانية, المدرسة">
    <!-- Canonical SEO -->
    <link rel="canonical" href="https://estudy.yaqob.com/">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{asset('landing/assets/img/logo.png')}}" />

    <!-- Include Styles -->
    <!-- BEGIN: Theme CSS-->
    <!-- Fonts -->
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendor/css/rtl/core-dark.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{asset('assets/vendor/css/rtl/theme-default-dark.css')}}" class="template-customizer-theme-css" />
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-misc.css')}}">
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
    <!--  style -->
    <script src="{{asset('assets/vendor/js/helpers.js')}}"></script>
    <script src="{{asset('assets/js/config.js')}}"></script>

    <script>
        window.templateCustomizer = new TemplateCustomizer({
            cssPath: '',
            themesPath: '',
            defaultShowDropdownOnHover: true, // true/false (for horizontal layout only)
            displayCustomizer: true,
            lang: 'ar',
            pathResolver: function(path) {
                var resolvedPaths = {
                    // Core stylesheets
                    'core.css': "{{asset('assets/vendor/css/rtl/core.css?id=7ea028d8943e4d11544610602e504b70')}}",
                    'core-dark.css': "{{asset('assets/vendor/css/rtl/core-dark.css?id=4d3d0e2ab14ecbed2083861be9812a6f')}}",
                    // Themes
                    'theme-default.css': "{{asset('assets/vendor/css/rtl/theme-default.css?id=3cdafbb15e4b7f9cbb567018a632af10')}}",
                    'theme-default-dark.css': "{{asset('assets/vendor/css/rtl/theme-default-dark.css?id=05dbf7c059f1493714333faa2b3b9551')}}",
                    'theme-bordered.css': "{{asset('assets/vendor/css/rtl/theme-bordered.css?id=d6c71dfec8b2243aaa4ff471ffcb4e24')}}",
                    'theme-bordered-dark.css': "{{asset('assets/vendor/css/rtl/theme-bordered-dark.css?id=f6ff29f111b4fa9e7eaf2b1123ef9dfe')}}",
                    'theme-semi-dark.css': "{{asset('assets/vendor/css/rtl/theme-semi-dark.css?id=ab4aad06ff175954e3058d7dc07cca0d')}}",
                    'theme-semi-dark-dark.css': "{{asset(' assets/vendor/css/rtl/theme-semi-dark-dark.css?id=366f5c60c757a1a9970a4c91c66b0cb2')}}",
                }
                return resolvedPaths[path] || path;
            },
            'controls': ["rtl","style","layoutType","showDropdownOnHover","layoutNavbarFixed","layoutFooterFixed","themes"],
        });
    </script>
    <!-- beautify ignore:end -->

<body>
    <!-- Layout Content -->
    @yield('content')
    <!--/ Layout Content -->
</body>

</html>

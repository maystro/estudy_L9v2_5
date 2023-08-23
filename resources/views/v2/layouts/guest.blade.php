<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="light-style customizer-hide" dir="rtl"
      data-theme="theme-default"
      data-assets-path="{{ asset ('assets') }}/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'منصة التعليم عن بعد') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset ('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset ('assets/vendor/fonts/fontawesome.css' ) }}" />
    <link rel="stylesheet" href="{{ asset ('assets/vendor/fonts/flag-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset ('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset ('assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset ('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <!-- Vendor -->
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
    @stack('css')

</head>
<body class="antialiased">

<!-- Page Content -->
    @yield('content')

<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="{{ asset ('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset ('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset ('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset ('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<script src="{{ asset ('assets/vendor/libs/hammer/hammer.js') }}"></script>
<script src="{{ asset ('assets/vendor/libs/i18n/i18n.js') }}"></script>
<script src="{{ asset ('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>

<script src="{{ asset ('assets/vendor/js/menu.js') }}"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset ('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
<script src="{{ asset ('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
<script src="{{ asset ('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset ('assets/js/main.js') }}"></script>

@stack('scripts')
</body>
</html>

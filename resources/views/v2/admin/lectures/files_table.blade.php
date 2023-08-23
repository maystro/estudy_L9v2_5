@extends('v2.admin.layouts.app')

@push('style')
    <link rel="stylesheet" href="{{ asset ('assets/vendor/fonts/fontawesome.css')}}"/>
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/dropzone/dropzone.css')}}"/>
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}"/>

    <link rel="stylesheet" href="{{ asset ('css/table.css')}}"/>
    <link rel="stylesheet" href="{{ asset ('css/modal.css')}}"/>

@endpush

@section('content')
    @livewire('admin.lectures.files-table')
@endsection

@push('modals')
    @include('v2.admin.partials.modal-dialogs')

    <div class="modal fade" id="uploadLecturesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            @livewire('admin.lectures.upload-lectures')
        </div>
    </div>

    <div class="modal fade" id="fileInfoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            @livewire('admin.lectures.lecture-file-info')
        </div>
    </div>
        <livewire:admin.playlists.assign-to-playlist modal-form="assignToPlaylistFormModal"/>
@endpush

@push('js')
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script>
        $(".selectpicker").selectpicker();

    </script>
@endpush
@push('lw-js')
    <script src="{{ asset ('assets/vendor/libs/dropzone/dropzone.js')}}"></script>
    <script src="{{ asset ('js/upload-multi-file-script.js')}}"></script>
@endpush

@extends('v2.admin.layouts.app')

@push('style')
    <link rel="stylesheet" href="{{ asset ('assets/vendor/fonts/fontawesome.css')}}"/>
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/dropzone/dropzone.css')}}"/>
    <link rel="stylesheet" href="{{ asset ('css/table.css')}}"/>
    <link rel="stylesheet" href="{{ asset ('css/modal.css')}}"/>
@endpush

@section('content')
    @livewire('admin.subjects.index')
@endsection

@push('modals')
    @include('v2.admin.partials.modal-dialogs')

    <div class="modal fade" id="subject-form-modal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            @livewire('admin.subjects.form')
        </div>
    </div>
@endpush

@push('js')
@endpush

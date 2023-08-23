@extends('v2.admin.layouts.app')

@push('style')
    <link rel="stylesheet" href="{{ asset ('assets/vendor/fonts/fontawesome.css')}}"/>
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/dropzone/dropzone.css')}}"/>
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}"/>

    <link rel="stylesheet" href="{{ asset ('css/table.css')}}"/>
    <link rel="stylesheet" href="{{ asset ('css/modal.css')}}"/>

@endpush

@section('content')
    @livewire('admin.teams.teams-users-manager')
@endsection

@push('modals')
    @include('v2.admin.partials.modal-dialogs')

    <div class="modal fade" id="team-form-modal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            @livewire('admin.teams.team-form')
        </div>
    </div>

    <x-popup-modal id="edit-member-modal-form" >
        <livewire:admin.teams.edit-member modal-form="edit-member-modal-form"/>
    </x-popup-modal>

@endpush

@push('js')
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script>
        $(".selectpicker").selectpicker();
    </script>
@endpush

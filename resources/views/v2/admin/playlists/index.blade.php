@extends('v2.admin.layouts.app')
@push('style')
    <style>
        table{
            border-collapse: separate !important;
            border-spacing: 0 7px;
        }
        tr{
            background-color: var(--bs-body-bg);
        }
        tr td:last-child {
            border-top-left-radius: 7px;
            border-bottom-left-radius: 7px;
        }
        tr td:first-child,tr th:first-child {
            border-top-right-radius: 7px;
            border-bottom-right-radius: 7px;
        }
        .table>:not(caption)>*>* {
            padding: 0.625rem 1.25rem;
            background-color: var(--bs-table-bg);
            border-bottom-width: 0px;
            box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
        }
    </style>
@endpush

@section('content')
    <livewire:admin.playlists.play-list-table />
@endsection

@include('v2.admin.partials.modal-dialogs')

@push('modals')
    <div class="modal fade" id="createPlaylistModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            @livewire('admin.playlists.create-playlist')
        </div>
    </div>
@endpush




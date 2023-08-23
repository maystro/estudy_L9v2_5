@extends('v2.admin.layouts.app')

@push('style')
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/flatpickr/flatpickr.css')}}"/>
    <link rel="stylesheet" href="{{ asset ('assets/vendor/fonts/fontawesome.css')}}"/>
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/dropzone/dropzone.css')}}"/>
    <style>
        .flatpickr-calendar {
            font-family: "Open Sans" !important;
        }

        .dropdown-menu[data-bs-popper].rtl {
            top: 100%;
            right: 0;
            margin-top: 0.125rem;
        }

        table th {
            font-size: inherit !important;
        }

        .dz-message {
            margin: 0.1em 0;
            font-weight: 500;
            text-align: center
        }

        input[type=file] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
            cursor: pointer;
        }

        input[type=file]::-webkit-file-upload-button {
            visibility: hidden;
        }

        .disappear {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            visibility: hidden;
        }

        .modal .btn-close {
            position: absolute;
            left: 1em;
            top: 0.8em;
        }

    </style>
@endpush

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
    <!-- Content -->
        @isset($subject_id)
            @livewire('admin.lectures.lecture-table',['subject_id' => $subject_id])
        @else
            @livewire('admin.lectures.lecture-table',['subject_id' => null])
        @endisset
    <!-- / Content -->
    @include('v2.admin.partials.modal-dialogs')
@endsection

@push('modals')
    <div class="modal fade" id="editLectureModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            @livewire('admin.lectures.lecture-form')
        </div>
    </div>

    <div class="modal fade" id="createLectureModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            @livewire('admin.lectures.create-lecture')
        </div>
    </div>

    <div class="modal fade" id="uploadLecturesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            @livewire('admin.lectures.upload-lectures')
        </div>
    </div>

    <div class="modal fade" id="selectPlaylistModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            @livewire('admin.playlists.select-playlist-modal')
        </div>
    </div>


@endpush

@push('js')
    <script src="{{ asset ('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>

    <script>
        $(document).ready(e => {

            let flatpickrDate = document.querySelector("#flatpickr-date");
            flatpickrDate.flatpickr({
                monthSelectorType: "static"
            });

            $(document).on('showModal', function (e) {
                let myModal = new bootstrap.Modal(document.getElementById(e.detail.target))
                myModal.show();
            });

            $(document).on('hideModal', function (e) {
                $(e.detail.target).modal('hide');
            });

        })

    </script>

@endpush

@push('lw-js')
    <script src="{{ asset ('assets/vendor/libs/dropzone/dropzone.js')}}"></script>

    <script>
        Dropzone.autoDiscover = false; // must to work with jquery()

        $(document).ready(e => {

            let fileCards = [];
            let cardTemplate = $('#cardTemplate');
            let cardsContainer = $('#cardsHolder');

            let self = this;

            $("#my-dropzone").dropzone({
                paramName: "file", // The name that will be used to transfer the file
                maxFilesize: 4,
                parallelUploads: 1,
                // Called just before the file is sent.
                sending: (file, xhr, formData) => {
                    formData.append("level-id", $('#upload-level-id').val());
                    formData.append("lecture-number", $('#upload-lecture-number').val());
                    formData.append("upload-frm_folder", $('#upload-frm_folder').val());
                    formData.append("subject-id", $('#subject-id').val());
                },
                addedfile: (file) => {
                    addFileCard(file);
                },
                error: (file, response) => {
                    showCardDetails(file, response, false);
                    console.log(response.message)
                },
                uploadprogress: function (file, progress, bytesSent) {
                    updateFileCardProgress(file, progress, bytesSent)
                },
                complete: function (file, response) {
                },
                success: function (file, response) {
                    showCardDetails(file, response, true);
                    Livewire.emitTo('admin.lectures.lecture-table', 'recordChanged')
                },
            })

            function addFileCard(file) {
                cardTemplate = cardTemplate.clone().removeClass('disappear')
                let uuid = file.upload.uuid;
                let fileCard = [];

                cardTemplate.attr('id', uuid) //set unique id
                cardTemplate.find('label.upload-status-text').text(file.upload.filename)

                fileCard['id'] = uuid;
                fileCard['container'] = cardTemplate;

                fileCards.push(fileCard);
                cardsContainer.append(cardTemplate);

                $("#my-dropzone").hide();
                $("#btn-add-file").removeClass('d-none');
            }

            const formValidationExamples = document.getElementById('formValidation');
            const fv = FormValidation.formValidation(formValidationExamples, {
                fields: {
                    'upload-level-id': {
                        validators: {
                            notEmpty: {
                                message: 'Please enter your name'
                            },
                            stringLength: {
                                min: 6,
                                max: 30,
                                message: 'The name must be more than 6 and less than 30 characters long'
                            },
                            regexp: {
                                regexp: /^[a-zA-Z0-9 ]+$/,
                                message: 'The name can only consist of alphabetical, number and space'
                            }
                        }
                    },

                }
            });

            $('.btn-add-file').on('click', function () {
                //validate inputs !
                //fv.validate();
                let level_id = $('#upload-level-id').val();
                let lecture_number = $('#upload-lecture-number').val();
                const $dropzone = $('#my-dropzone')
                if (level_id > 0 && lecture_number > 0)
                    $dropzone.click();
            });
            $('.btn-reset-close').on('click', function () {
                Livewire.emitTo('admin.lectures.upload-lectures', 'reset')
            });

            function updateFileCardProgress(file, progress, bytesSent) {
                let cardFileId = $('#' + file.upload.uuid)
                let $progress = cardFileId.find('.progress .progress-bar');
                let $progressInfo = cardFileId.find('.upload-status-progress .text');

                $progress.css('width', progress + '%');
                $progressInfo.text(parseInt(progress) + ' %');
            }

            function showCardDetails(file, response, success) {
                let cardFileId = $('#' + file.upload.uuid)
                let $progressInfo = cardFileId.find('.upload-status-progress');
                if (success)
                    $progressInfo.html("<i class='text-success bx bx-check bx-md'></i>")
                else {
                    $progressInfo.html("<i class='text-danger bx bx-x-circle bx-md'></i>")
                }
            }

        })


    </script>
@endpush

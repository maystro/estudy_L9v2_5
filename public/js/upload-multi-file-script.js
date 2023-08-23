Dropzone.autoDiscover = false; // must to work with jquery()

$(document).ready(e => {

    let fileCards;
    fileCards = [];
    let cardTemplate = $('#cardTemplate');
    let cardsContainer = $('#cardsHolder');

    let self = this;

    $("#my-dropzone").dropzone({
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 500,
        timeout:6000000,
        parallelUploads: 1,
        // Called just before the file is sent.
        sending: (file, xhr, formData) => {
            formData.append("level-id", $('#upload-level-id').val());
            formData.append("lecture-number", $('#upload-lecture-number').val());
            formData.append("upload-folder", $('#upload-folder').val());
            formData.append("subject-id", $('#subject-id').val());
        },
        addedfile: (file) => {
            addFileCard(file);
        },
        error: (file, response) => {
            showCardDetails(file, response, false);
            console.log(response);
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
});


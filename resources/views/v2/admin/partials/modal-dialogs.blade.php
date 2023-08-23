@push('style')
    <style>
        .toast-header {
            padding: 0.5rem 1.25rem; !important;
        }
        .toast-body {
            padding: 0.5rem 1.25rem;!important;
        }
    </style>
@endpush

@push('modals')
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto" id="title"></strong>
            </div>
            <div class="toast-body" id="message">
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="status-message" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="backdrop-status-message">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script>
        var toastTrigger = document.getElementById('liveToastBtn')
        var toastLiveExample = document.getElementById('liveToast')
        if (toastTrigger) {
            toastTrigger.addEventListener('click', function () {
                var toast = new bootstrap.Toast(toastLiveExample)
                toast.show()
            })
        }

        $.fn.extend({
            hasClasses: function (selectors) {
                var self = this;
                for (var i in selectors) {
                    if ($(self).hasClass(selectors[i]))
                        return true;
                }
                return false;
            }
        });

        $.fn.extend({
            removeClasses: function (selectors) {
                var self = this;
                for (var i in selectors) {
                    if ($(self).removeClass(selectors[i]))
                        return true;
                }
                return false;
            }
        });

        $(document).ready(function (e){

            $(document).on('show_toast', function (e) {
                let liveToast = $('#liveToast');
                let classes = [
                    'bg-primary','bg-secondary',
                    'bg-success','bg-warning',
                    'bg-info','bg-danger','bg-dark'];
                let toast = new bootstrap.Toast(liveToast);
                liveToast.find('#message').html(e.detail.message);
                liveToast.find('#title').html(e.detail.title);
                liveToast.removeClasses(classes);
                liveToast.addClass(e.detail.class)
                toast.show()
            })

            $(document).on('show_toaster', function (e) {
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-bottom-center",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
                toastr[e.detail.class](e.detail.title,e.detail.message);
            })

            $(document).on('confirm_delete', (e) => {
                let id=e.detail.id;
                    Swal.fire({
                        title: 'هل حضرتك متأكد ؟',
                        text: "لا يمكنك الرجوع بعد إتمام الحذف",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'إلغاء',
                        confirmButtonText: 'نعم ، قم بالحذف',
                        customClass: {
                            confirmButton: 'btn btn-primary me-1',
                            cancelButton: 'btn btn-label-secondary'
                        },
                        buttonsStyling: false
                    }).then(function(result) {
                        if (result.value) {
                            if(e.detail.event==null)
                                Livewire.emit('delete_record',id);
                            else
                                Livewire.emit(e.detail.event,id);
                        }
                        // else
                        //     if (result.dismiss === Swal.DismissReason.cancel) {
                        //     Swal.fire({
                        //         title: 'إلغاء',
                        //         text: 'تم إلغاد الحذف',
                        //         icon: 'success',
                        //         customClass: {
                        //             confirmButton: 'btn btn-success'
                        //         }
                        //     });
                        // }
                    });
            })

            $(document).on('show_message', (e) => {
                Swal.fire({
                    icon: e.detail.icon,
                    title: e.detail.title,
                    text: e.detail.text,
                    confirmButtonText:'موافق',
                    customClass: {
                        confirmButton: 'btn btn-'+e.detail.type
                    }
                });
            })

            $(document).on('confirm_delete_selected', (e) => {
                const el = document.querySelector("#delete-selected-modal");
                const modal = bootstrap.Modal.getOrCreateInstance(el);
                modal.show();
            })

            $(document).on('confirm-dialog', (e) => {

                let dlg='#success-dialog';

                switch (e.detail.type)
                {
                    case 'success':dlg = '#success-dialog';break;
                    case 'warning':dlg = '#warning-dialog';break;
                }

                $(dlg).find('.dlg-title').html(e.detail.title)
                $(dlg).find('.dlg-text').html(e.detail.text)

                const el = document.querySelector(dlg);
                const modal = bootstrap.Modal.getOrCreateInstance(el);
                modal.show()
            })
        })

    </script>
@endpush

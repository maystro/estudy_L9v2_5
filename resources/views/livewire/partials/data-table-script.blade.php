@push('style')
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/flatpickr/flatpickr.css')}}"/>
@endpush

@push('modals')
    <!-- Modal -->
    <div class="modal fade" id="status-message" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="modal-title text-center" id="backdrop-status-message">
                        <span class="dlg-text"></span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script src="{{ asset ('assets/vendor/libs/sortablejs/sortable.js')}}"></script>
    <script src="{{ asset ('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>

    <script>
        // Apply Sortable feature
        let el = document.getElementById('dataTableBody');
        let sortable = Sortable.create(el, {
            handle: '.drag-handle',
            onUpdate: function (/**Event*/evt) {
                let $tr = $('#dataTableBody tr');
                let newId = $tr.eq(evt.newDraggableIndex).attr("data-id");
                let oldId = $tr.eq(evt.oldDraggableIndex).attr("data-id");
                let newOrder = $tr.eq(evt.newDraggableIndex).attr("data-order");
                let oldOrder = $tr.eq(evt.oldDraggableIndex).attr("data-order");

                $tr.eq(evt.newDraggableIndex).attr("data-order",oldOrder);
                $tr.eq(evt.oldDraggableIndex).attr("data-order",newOrder);

                Livewire.emit('rowOrderChanged',
                    {
                        'new_rec_id':newId,'new_rec_order':evt.newDraggableIndex+1,
                        'old_rec_id':oldId,'old_rec_order':evt.oldDraggableIndex+1
                    });
            },
        });

        // hide and show modals
        $(document).on('showModal', function (e) {
            //let myModal = new bootstrap.Modal(document.getElementById(e.detail.target))
            let myModal = new bootstrap.Modal($(e.detail.target))
            myModal.show();
        });

        $(document).on('hideModal', function (e) {
            $(e.detail.target).modal('hide');
        });


        // Enable dropdown calendar
        if($(".flatpickr-date").length>0)
        {
                let flatpickrDate = document.querySelector(".flatpickr-date");
                flatpickrDate.flatpickr({
                    monthSelectorType: "static"
                });
        }

        // print feature but not working with popup blocker
        $(document).on('show_print_window', () => {
            window.open('{{route('admin.print')}}', '_blank');
        })

        $(document).on('status-message', (e) => {

            let dlg = '#status-message';
            const el = document.querySelector(dlg);

            if (e.detail.state === 'show') {
                //const modal = bootstrap.Modal.getOrCreateInstance(el);
                let modal = new bootstrap.Modal(el)
                $(dlg).find('.dlg-text').html(e.detail.message)
                $(dlg).modal('show');
            }
            if (e.detail.state === 'hide') {
                setTimeout(() => {
                    $('#status-message').modal('hide');
                }, 1000)
            }
        })

        function reset_sort() {
            let $rows = $('#dataTableBody').find('tr');
            let sort_array = Array();
            $.each($rows, function (key, row) {
                sort_array.push({'id': $(row).data('id'), 'order': key + 1});
            })
            Livewire.emit('reset_sort', sort_array)
        }

    </script>
@endpush

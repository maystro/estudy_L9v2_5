@push('js')
    <script src="{{ asset ('assets/vendor/libs/sortablejs/sortable.js')}}"></script>
    <script>
        let el = document.getElementById('PlaylistTable');
        let sortable = Sortable.create(el, {
            handle: '.drag-handle',
            onUpdate: function (/**Event*/evt) {
                let $tr = $('#PlaylistTable tr');
                let newId = $tr.eq(evt.newDraggableIndex).attr("data-id");
                let oldId = $tr.eq(evt.oldDraggableIndex).attr("data-id");
                let newOrder = $tr.eq(evt.newDraggableIndex).attr("data-order");
                let oldOrder = $tr.eq(evt.oldDraggableIndex).attr("data-order");

                $tr.eq(evt.newDraggableIndex).attr("data-order",oldOrder);
                $tr.eq(evt.oldDraggableIndex).attr("data-order",newOrder);

                Livewire.emit('rowOrderChanged',
                    {
                        'new_rec_id':newId,'new_rec_order':oldOrder,
                        'old_rec_id':oldId,'old_rec_order':newOrder
                    });
            },
        });

        $(document).on('showModal',function(e){
            let myModal = new bootstrap.Modal(document.getElementById(e.detail.target))
            myModal.show();
        });

        $(document).on('hideModal',function(e){
            $(e.detail.target).modal('hide');
        });
    </script>
@endpush

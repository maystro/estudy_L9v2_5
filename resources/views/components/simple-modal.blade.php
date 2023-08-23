@props(['id', 'livewireId'])

<div class="modal fade" id="{{$id}}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered" role="document" wire:ignore.self>
        <div class="modal-content">
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
            </button>

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class='{{$formIcon}}'></i>
                    {{$formTitle}}
                </h5>
            </div>

            <div class="modal-body">
                {{$slot}}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" id="btn-close-dlg" wire:click="close" data-bs-dismiss="modal">
                    {{$closeTitle}}</button>
                <button type="button" class="btn btn-primary" id="btn-ok-dlg">{{$okTitle}}</button>
            </div>

        </div>
    </div>
</div>

@push('js')
    <script>
        document.addEventListener('livewire:load', function () {
            $('#btn-ok-dlg').on('click',function(e){
                Livewire.emitTo('{{$livewireId}}','ok-button-clicked')
            })
            $('#btn-close-dlg').on('click',function(e){
                Livewire.emitTo('{{$livewireId}}','close-button-clicked')
            })
        })
    </script>
@endpush

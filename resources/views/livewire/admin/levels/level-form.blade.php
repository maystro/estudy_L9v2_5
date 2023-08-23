<div class="modal-content">

    <button type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"></button>

    <div class="modal-header">
        <h5 class="modal-title">{{$form_title}}</h5>
    </div>
    <form>
        <div class="modal-body">
            <div class="row g-2">
                <div class="col mb-3">
                    <label for="nameBasic" class="form-label">اسم المستوى</label>
                    <input type="text"
                           wire:model="frm_level_title"
                           id="nameBasic"
                           class="form-control" placeholder="ادخل اسم المستوى">
                    @error('frm_level_title') <span class="error">{{$message}}</span> @enderror
                </div>
            </div>
        </div>
    </form>
    <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" wire:click="close" >إغلاق</button>
        <button type="button" class="btn btn-primary" wire:click="save">حفظ التغيرات</button>
    </div>

</div>

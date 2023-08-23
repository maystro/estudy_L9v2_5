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
                    <label for="nameBasic" class="form-label">اسم المادة</label>
                    <input type="text"
                           wire:model="frm_subject_title"
                           id="nameBasic"
                           class="form-control" placeholder="ادخل اسم المادة">
                    @error('frm_subject_title') <span class="error">{{$message}}</span> @enderror
                </div>
            </div>
            <div class="row g-2">
                <div class="col mb-0">
                    <label for="emailBasic" class="form-label">الدرجة العظمى</label>
                    <input
                        type="number"
                        min="10" max="100"
                        id="min"
                        wire:model="frm_subject_min"
                        class="form-control" placeholder="00">
                    @error('frm_subject_min') <span class="error">{{$message}}</span> @enderror
                </div>
                <div class="col mb-0">
                    <label for="dobBasic" class="form-label">الدرجة الصغرى</label>
                    <input
                        type="number"
                        min="10" max="100"
                        id="max"
                        wire:model="frm_subject_max"
                        class="form-control" placeholder="00">
                    @error('frm_subject_max') <span class="error">{{$message}}</span> @enderror
                </div>
            </div>
        </div>
    </form>
    <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" wire:click="close" >إغلاق</button>
        <button type="button" class="btn btn-primary" wire:click="save">حفظ التغيرات</button>
    </div>

</div>

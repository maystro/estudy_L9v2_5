<div class="modal-content">

    <button type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"></button>

    <div class="modal-header">
        <h5 class="modal-title">
            <i class='text-warning bx {{$form_icon}} bx-lg'></i>
            {{$form_title}}
        </h5>
    </div>

    <form>
        <div class="modal-body">
            <div class="row">
                <div class="col mb-0">
                    <label for="dobBasic" class="form-label">اسم الفريق</label>
                    <input
                        type="text"
                        id="team-name"
                        wire:model.lazy="form_team_name"
                        class="form-control" placeholder="اسم الفريق - غير مكرر">
                    @error('form_team_name') <span class="error">{{$message}}</span> @enderror
                </div>
            </div>
            <div class="row mt-3">
                <div class="mb-3" wire:ignore>
                    <label for="selectpickerLiveSearch" class="form-label">اسم مشرف الفريق - اكتب للبحث</label>
                    <select
                        wire:model="form_team_user_id"
                        id="selectpickerLiveSearch"
                        class="selectpicker w-100 text-primary"
                        data-style="btn-default" data-live-search="true">
                        @foreach($teachers as $key=>$teacher)
                            <option
                                value="{{$teacher->user_id}}"
                                data-tokens="{{$teacher->user_id}}">{{$teacher->user_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </form>

    <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" wire:click="close">إغلاق</button>
        <button type="button" class="btn btn-primary" wire:click="save">حفظ التغيرات</button>
    </div>

</div>

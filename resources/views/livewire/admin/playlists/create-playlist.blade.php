<div class="modal-content">

    <div class="modal-header">
        <h5 class="modal-title">
            <i class='text-warning bx bx-lg'></i>
            {{$form_title}}
        </h5>
        <label class="switch me-0 enFont">
            <input type="checkbox" class="switch-input" checked="">
            <span class="switch-toggle-slider">
                      <span class="switch-on">
                        <i class="bx bx-check"></i>
                      </span>
                      <span class="switch-off">
                        <i class="bx bx-x"></i>
                      </span>
                    </span>
            <span class="switch-label"></span>
        </label>
    </div>

    <div class="modal-body border-bottom">
        <form>
            <div class="row">
                <label for="defaultFormControlInput" class="form-label">اسم القائمة</label>
                <div class="col">
                    <input type="text"
                           wire:model="form_playlist_title"
                           class="form-control"
                           placeholder="اكتب اسم القائمة"/>
                    @error('form_playlist_title') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label for="nameBasic" class="form-label">المستوى</label>
                    <select id="frm_list_type"
                            wire:model="form_list_type"
                            class="form-select">
                        @foreach(\App\Models\Playlist::LIST_TYPE as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                    @error('form_list_type') <span class="error">{{$message}}</span> @enderror
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-6 mb-3">
                    <label for="nameBasic" class="form-label">متاح أيضًا لمجوعات:</label>
                    <div class="list-group">
                        @foreach(\App\Models\Level::all() as $level)
                            <label class="list-group-item">
                                <input class="form-check-input me-1" type="checkbox"
                                       wire:model="form_visible_to" value="{{$level->id}}">
                                {{$level->title}}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="col-6 mb-3 border-right">
                    <label for="nameBasic" class="form-label">مسموح لحسابات المستخدمين :</label>
                    <div class="list-group ltr">
                        @foreach( \App\Enums\Roles::asArray()  as $key=>$value)
                            <label class="list-group-item text-end">
                                <input class="form-check-input ms-2" type="checkbox"
                                       wire:model="form_user_role" value="{{$value}}">
                                {{$key}}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">إغلاق</button>
        <button type="button" class="btn btn-primary" wire:click="save">حفظ</button>
    </div>

</div>

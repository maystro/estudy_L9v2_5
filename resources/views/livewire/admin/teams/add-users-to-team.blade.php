<form>
    <div class="modal-header">
        <h5 class="modal-title">
            <i class='text-warning bx {{$form_icon}} bx-lg'></i>
            {{$form_title}}
        </h5>
    </div>
    <div class="modal-body">
        <div class="alert alert-info" role="alert">
            لقد قمت باختيار
            <strong>{{count($users_ids)}} مشترك</strong>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </div>
        @endif

        <div class="row mt-3">
            <div class="mb-3">
                <label for="selectpickerLiveSearch" class="form-label">اسم المجموعة</label>
                <select
                    wire:model="team_id"
                    id="selectpickerLiveSearch"
                    class="form-select"
                    data-style="btn-default" data-live-search="false">
                    <option
                        value="0"
                        data-tokens="0">اختر اسم المجموعة
                    </option>
                    @foreach($teamsList as $team)
                        <option
                            value="{{$team->id}}"
                            data-tokens="{{$team->id}}">{{$team->name}}</option>
                    @endforeach
                </select>
                @error('team_id') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" wire:click="close" data-bs-dismiss="modal">إغلاق</button>
        <button type="button" class="btn btn-primary" wire:click="save"> حفظ التغيرات</button>
    </div>
</form>




<form>
    <div class="modal-header">
        <h5 class="modal-title">
            <i class='text-warning bx {{$form_icon}} bx-lg'></i>
            {{$form_title}}
        </h5>
    </div>

    <div class="modal-body">
        <div class="alert alert-info" role="alert">
            <li><label>إسم الفريق : </label>
                <strong>{{$team->name ?? ''}}</strong></li>

            <li><label>إسم المنشىء : </label>
                <strong>{{$team->owner->name ?? ''}}</strong></li>

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
                <label>إسم العضو : </label>
                @if($this->team_member)
                    <label>{{$this->team_member->user()->name}}</label>
                @endif

            </div>
        </div>
        <div class="row">
            <div class="col-md mb-md-0 mb-2">
                <div class="form-check custom-option custom-option-icon">
                    <label class="form-check-label custom-option-content" for="customRadioIcon1">
                                    <span class="custom-option-body">
                                      <i class="bx bx-rocket"></i>
                                      <span class="custom-option-title">حجب</span>
                                      <small> يمكنك إيقاف هذا الحساب </small>
                                    </span>
                        <input name="customRadioIcon"
                               wire:model="role"
                               class="form-check-input" type="radio" value="blocked" id="customRadioIcon1" checked />
                    </label>
                </div>
            </div>
            <div class="col-md mb-md-0 mb-2">
                <div class="form-check custom-option custom-option-icon">
                    <label class="form-check-label custom-option-content" for="customRadioIcon2">
                                    <span class="custom-option-body">
                                      <i class="bx bx-user"></i>
                                      <span class="custom-option-title"> عضو </span>
                                      <small> صلاحيات عضو </small>
                                    </span>
                        <input name="customRadioIcon"
                               wire:model="role"
                               class="form-check-input" type="radio" value="{{\App\Enums\Roles::Student}}" id="customRadioIcon2" />
                    </label>
                </div>
            </div>
            <div class="col-md">
                <div class="form-check custom-option custom-option-icon">
                    <label class="form-check-label custom-option-content" for="customRadioIcon3">
                                    <span class="custom-option-body">
                                      <i class="bx bx-crown"></i>
                                      <span class="custom-option-title"> مشرف </span>
                                      <small>صلاحيات مشرف متابعة لأفراد المجموعة</small>
                                    </span>
                        <input name="customRadioIcon"
                               wire:model="role"
                               class="form-check-input" type="radio" value="{{\App\Enums\Roles::Teacher}}" id="customRadioIcon3" />
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" wire:click="close" data-bs-dismiss="modal">إغلاق</button>
        <button type="button" class="btn btn-primary" wire:click="save"> حفظ التغيرات</button>
    </div>
</form>

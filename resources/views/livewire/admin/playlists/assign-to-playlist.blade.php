<x-simple-modal id="assignToPlaylistFormModal"
                :livewireId="$thisLivewireTemplate"
                form_icon="text-warning bx bxs-copy-alt bx-lg"
                :form_title="$form_title"
                ok-title="موافق"
                close-title="إغلاق">
<div>
    <div class="alert alert-info" role="alert">
        لقد قمت باختيار
        {{count($selectedFiles)}} ملف
    </div>

    <div class="row mt-3">
        <div class="mb-3" wire:ignore>
            <label for="selectpickerLiveSearch" class="form-label">قوائم التشغيل - اكتب للبحث</label>
            <select
                wire:model="form_playlist_id"
                id="selectpickerLiveSearch"
                class="selectpicker w-100 text-primary"
                data-style="btn-default">
                <option value="0">اختر قائمة تشغيل</option>
                @foreach($playlists as $playlist)
                    <option
                        value="{{$playlist->id}}"
                        data-tokens="{{$playlist->id}}">{{$playlist->title}}</option>
                @endforeach
            </select>
        </div>
        @error('form_playlist_id')<span class="text-danger">{{$message}}</span>@enderror
    </div>
</div>

</x-simple-modal>

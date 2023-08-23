<?php

namespace App\Http\Livewire\Admin\Playlists;

use App\Models\Playlist;
use Illuminate\Support\Arr;
use Livewire\Component;

class CreatePlaylist extends Component
{
    public $form_title='إضافة قائمة تشغيل';
    public $form_data_mode ='create'; // or edit
    public $form_playlist_title;
    public $form_list_type='';
    public $form_visible_to=[];
    public $form_user_role=[];
    public $form_playlist_id=0;

    protected $listeners=[
        'editPlaylist'=>'getPlaylistData',
        'newPlaylist'=>'resetPlaylistData'
    ];

    protected $rules= [
        'form_playlist_title'=>'required|min:3',
        'form_list_type'=>'required',
    ];
    protected $messages = [
        'form_playlist_title.required'=>'لا بد من كتابة اسم القائمة',
        'form_playlist_title.min'=>'العنوان لا يقل عن ٣ أحرف'
    ];

    public function render()
    {
        return view('livewire.admin.playlists.create-playlist');
    }

    public function resetPlaylistData()
    {
        $this->reset();
        $this->form_data_mode='create';
    }
    public function getPlaylistData($id)
    {
        $playlist = Playlist::query()->find($id);
        if($playlist)
        {
            $this->form_playlist_id = $playlist->id;
            $this->form_playlist_title = $playlist->title;
            $this->form_list_type = $playlist->list_type;
            $this->form_visible_to = $playlist->getVisibleTo();
            $this->form_user_role = $playlist->getUserRole();
            $this->form_data_mode = 'edit';
        }
    }
    public function save()
    {
        if($this->form_data_mode=='edit')
            $this->update($this->form_playlist_id);
        if($this->form_data_mode=='create')
            $this->create();
        $this->emitTo('admin.playlists.play-list-table','recordChanged');
    }
    public function create()
    {
        $this->validate();
        $playlist=Playlist::query()->create([
            'title'=>$this->form_playlist_title,
            'list_type'=>$this->form_list_type,
            'visible_to'=> implode(',',$this->form_visible_to),
            'user_role'=> implode(',',$this->form_user_role),
            'idx'=> Playlist::query()->max('idx') + 1
        ]);
        $this->getPlaylistData($playlist->id);
    }
    public function update($id)
    {
        $this->validate();
        $playlist = Playlist::query()->find($id);
        $playlist->title = $this->form_playlist_title;
        $playlist->list_type = $this->form_list_type;
        $playlist->visible_to = implode(',',$this->form_visible_to);
        $playlist->user_role = implode(',',$this->form_user_role);
        $playlist->save();
    }

}

<?php

namespace App\Http\Livewire\Admin\Playlists;

use App\Models\Playlist;
use Livewire\Component;

class AssignToPlaylist extends Component
{
    public $playlists;
    public $selectedFiles=[];
    public $form_playlist_id=0;
    public $form_title='قوائم التشغيل';
    public $modal_form='#assignToPlaylistFormModal';

    public $thisLivewireTemplate='admin.playlists.assign-to-playlist';

    protected $listeners=[
        'ok-button-clicked'=>'save',
        'close-button-clicked'=>'close',
        'before_items_added'=>'before_items_added'
    ];
    protected $rules=[
        'form_playlist_id'=>'required|gt:0'
    ];
    protected $messages=[
        'form_playlist_id.required'=>'لابد من اختيار قائمة تشغيل',
        'form_playlist_id.gt'=>'لابد من اختيار قائمة تشغيل',
    ];

    public function render()
    {
        return view('livewire.'.$this->thisLivewireTemplate);
    }
    public function mount()
    {
        $this->playlists=Playlist::all();
    }

    public function before_items_added($items)
    {
        $this->selectedFiles=$items;
    }
    public function close()
    {
        $this->reset();
    }
    public function save()
    {
        $this->validate();
        $created_count=0;
        $updated_count=0;
        foreach ($this->selectedFiles as $key=>$file_id)
        {
            $playlist_file = \App\Models\PlaylistFiles::query()->updateOrCreate(
                ['lecture_id'=>$file_id,'playlist_id'=>$this->form_playlist_id],
                ['idx'=>$key],
            );
            if($playlist_file->exists)
                $updated_count+=1;
            else
                $created_count+=1;
        }

        $playlist=Playlist::query()->find($this->form_playlist_id);

        $this->dispatchBrowserEvent('hideModal',['target'=>$this->modal_form]);
        $this->dispatchBrowserEvent('show_message',[
            'icon'=>'success',
            'type'=>'primary',
            'title'=> 'تم اضافة '.$created_count.' من '. ($updated_count+$created_count),
            'text'=>' لقائمة '.$playlist->title,
        ]);

    }
}

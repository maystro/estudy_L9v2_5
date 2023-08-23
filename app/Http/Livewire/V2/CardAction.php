<?php

namespace App\Http\Livewire\V2;

use App\Models\PlaylistFiles;
use Livewire\Component;

class CardAction extends Component
{
    public $media_file;
    public $media_id;
    public $likeCount=0;
    public $hasLike=false;

    public function render()
    {
        return view('livewire.v2.card-action');
    }

    public function mount()
    {
        $this->media_file = PlaylistFiles::query()->find($this->media_id);
        $this->likeCount = $this->media_file->likeCount();
        $this->hasLike = $this->media_file->hasLike();
    }

    public function addLike()
    {
        $this->media_file->addLike();
        $this->likeCount = $this->media_file->likeCount();
    }

}

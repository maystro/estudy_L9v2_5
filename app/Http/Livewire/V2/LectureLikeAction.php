<?php

namespace App\Http\Livewire\V2;

use App\Models\Lecture;
use Livewire\Component;
use PhpParser\Node\Stmt\Else_;

class LectureLikeAction extends Component
{
    public $lecture;
    public int $likeCount = 0;
    public bool $hasLike = false;
    public int $visits=0;

    public function render()
    {
        return view('livewire.v2.lecture-like-action',[
            'lecture'=>$this->lecture,
            'hasLike'=>$this->hasLike,
            'likeCount'=>$this->likeCount,
            'visits'=>$this->visits
        ]);
    }

    public function mount()
    {
        $lecture_id = $this->lecture->id;
        $this->lecture = Lecture::query()->find($lecture_id);
        $this->visits = $this->lecture->visits();
        $likeCount =Lecture::likesCount($lecture_id);
        $hasLike = Lecture::hasLike($lecture_id);
        $this->hasLike  = $hasLike;
        $this->likeCount = $likeCount;
    }

    public function ToggleLike()
    {

        $lecture_id = $this->lecture->id;
        $likesCount =Lecture::likesCount($lecture_id);
        $hasLike = Lecture::hasLike($lecture_id);

        $this->hasLike  = $hasLike;
        $this->likeCount = $likesCount;

        if (!$hasLike)
        {
            Lecture::addLike($lecture_id);
            $this->hasLike = true;
        }
        else
        {
            Lecture::removeLike($lecture_id);
            $this->hasLike = false;
        }
        $this->likeCount =Lecture::likesCount($lecture_id);


    }
}

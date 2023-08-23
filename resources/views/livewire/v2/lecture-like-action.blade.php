<div>
    <div class="card-actions">

        <a wire:click="ToggleLike" class="text-muted me-3 cursor-pointer prevent-select" >
        @if($hasLike)
            <i class="bx bxs-heart me-1 text-danger"></i>{{$likeCount}}
        @else
            <i class="bx bx-heart me-1"></i>{{$likeCount}}
        @endif
        </a>

        <a class="text-muted me-3 cursor-pointer prevent-select" >
            <i class="bx bxs-show me-1"></i>{{$visits}}
        </a>

    </div>
</div>

<div>
    <div class="card-actions">
        @if($hasLike)
            <a wire:click="addLike" class="text-muted me-3"><i class="bx bxs-heart me-1 text-danger"></i>{{$likeCount}}</a>
        @else
            <a wire:click="addLike" class="text-muted me-3"><i class="bx bx-heart me-1"></i>{{$likeCount}}</a>
        @endif

        <a class="text-muted me-3"><i class="bx bx-message me-1"></i>0</a>
        <a class="text-muted"><i class='bx bx-show-alt me-1'></i>0</a>

    </div>
</div>

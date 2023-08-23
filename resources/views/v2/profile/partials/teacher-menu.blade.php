@foreach(\App\Models\Level::all() as $level)

    <li class="menu-item {{Route::is('profile.subjects')? 'active':''}}">
        <a href="{{route('profile.level.lectures',$level)}}" class="menu-link">
            <i class='menu-icon tf-icons bx bx-list-ul'></i>
            <div data-i18n="محاضرات
            {{$level->title}}">
                محاضرات
                {{$level->title}}
            </div>
        </a>
    </li>

@endforeach

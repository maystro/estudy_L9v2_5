@php
    use Illuminate\Support\Facades\Route;
        $allplaylists = \App\Models\Playlist::query()
        ->whereIn('list_type',['audio','video'])
        ->get();
        $allowed_playlists =[];
        foreach ($allplaylists as $playlist)
            {
                $found = boolval(
                    in_array(auth()->user()->levelId(),
                    explode(',',$playlist->visible_to))
                    );
                if($found)
                    $allowed_playlists[] = $playlist;
            }

        $master_books_lists = \App\Models\Playlist::query()->where('list_type','=','MASTER_BOOKS')->get();

        $books_lists = \App\Models\Playlist::query()
        ->where('list_type','=','book')
        ->whereRaw("FIND_IN_SET(".auth()->user()->levelId().", visible_to )")
        ->get();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <div class="app-brand demo ">

        <a href="{{route('profile')}}" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bolder ms-2">صفحتي</span>
        </a>
        <a href="{{route('profile')}}" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>

    </div>

    <div class="menu-inner-shadow"></div>


    <ul class="menu-inner py-1">
        <!-- Home -->
        <li class="menu-item {{Route::is('profile')? 'active':''}}">
            <a href="{{route('profile')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="الرئيسية">الرئيسية</div>
            </a>
        </li>

        <!-- Subjects -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">المنهج</span>
        </li>

        @can(\App\Enums\Roles::Teacher)
            @include('v2.profile.partials.teacher-menu')
        @endcan

        @can(\App\Enums\Roles::Student)
            @include('v2.profile.partials.student-menu')
        @endcan

        @can(\App\Enums\Roles::Subscriber)
            @include('v2.profile.partials.subscriber-menu')
        @endcan

        <!-- General Lib -->
        @if($allowed_playlists)
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">المكتبة العامة</span>
            </li>
        @endif
        @foreach($allowed_playlists as $playlist)
            <li class="menu-item
            {{
        Route::is('profile.media.list') &&
        Route::current()->parameter('playlist_id')==$playlist->id?
        'active':''}} ">
                <a href="{{ route('profile.media.list',$playlist->id) }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div data-i18n="{{$playlist->title}}">{{$playlist->title}}</div>
                </a>
            </li>
        @endforeach

        <!--PDF Lib -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">المكتبة الاليكترونية</span>
        </li>

        @foreach($master_books_lists as $book_list)
            <li class="menu-item {{
    Route::is('profile.book.list') &&
    Route::current()->parameter('playlist_id')==$book_list->id?
    'active':''}}">
                <a href="{{ route('profile.book.list',$book_list->id) }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div data-i18n="{{$book_list->title}}">{{$book_list->title}}</div>
                </a>
            </li>
        @endforeach

        @foreach($books_lists as $book_list)
            <li class="menu-item">
                <a href="{{ route('profile.book.list',$book_list->id) }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div data-i18n="{{$book_list->title}}">{{$book_list->title}}</div>
                </a>
            </li>
        @endforeach

    </ul>

</aside>

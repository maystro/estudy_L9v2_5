<li class="menu-item {{Route::is('profile.subject.lectures','profile.subjects')? 'active':''}}">
    <a href="{{route('profile.subjects')}}" class="menu-link">
        <i class='menu-icon tf-icons bx bx-list-ul'></i>
        <div data-i18n="استعراض حسب المادة">استعراض حسب المادة</div>
    </a>
</li>
<li class="menu-item {{Route::is('profile.lecturesByDay')? 'active':''}}">
    <a href="{{route('profile.lecturesByDay')}}" class="menu-link">
        <i class='menu-icon tf-icons bx bxs-calendar'></i>
        <div data-i18n="استعراض حسب اليوم">استعراض حسب اليوم</div>
    </a>
</li>

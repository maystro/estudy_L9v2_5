@php
    $basic_active='';
    $security_active='';
    $notifications_active='';
    switch ( \Illuminate\Support\Facades\Route::currentRouteName())
    {
        case 'profile.account':
            $basic_active = 'active';
            break;
        case 'profile.account.security':
            $security_active = 'active';
            break;
        case 'profile.account.notifications':
            $notifications_active = 'active';
            break;
    }
@endphp
<ul class="nav nav-pills flex-column flex-md-row mb-3">

    <li class="nav-item">
        <a class="nav-link {{$basic_active}}" href="{{route('profile.account')}}">
            <i class="bx bx-user me-1"></i>بياناتي</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{$security_active}}" href="{{route('profile.account.security')}}">
            <i class="bx bx-lock-alt me-1"></i>الأمان</a>
    </li>

</ul>

@php
    require_once(app_path() . '/Components/Util.php');
@endphp

<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
     id="layout-navbar">

    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0   d-xl-none ">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item navbar-search-wrapper mb-0">
                <a class="nav-item nav-link search-toggler px-0" href="javascript:void(0);">
                    <i class="bx bx-search bx-sm"></i>
                    <span class="d-none d-md-inline-block text-muted">بحث</span>
                </a>
            </div>
        </div>
        <!-- /Search -->


        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <!-- Style Switcher -->
            <li class="nav-item me-2 me-xl-0">
                <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                    <i class='bx bx-sm'></i>
                </a>
            </li>
            <!--/ Style Switcher -->

            <!-- Quick links  -->
            <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
                   data-bs-auto-close="outside" aria-expanded="false">
                    <i class='bx bx-grid-alt bx-sm'></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end py-0">
                    <div class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h5 class="text-body mb-0 me-auto">مختصرات</h5>
                            <a href="javascript:void(0)" class="dropdown-shortcuts-add text-body"
                               data-bs-toggle="tooltip" data-bs-placement="top" title="إضافة مختصر"><i
                                    class="bx bx-sm bx-plus-circle"></i></a>
                        </div>
                    </div>

                    <div class="dropdown-shortcuts-list scrollable-container">
                        <div class="row row-bordered overflow-visible g-0">
                            <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                <i class="bx bx-pie-chart-alt-2 fs-4"></i>
                                </span>
                                <a href="{{route('profile.account')}}" class="stretched-link">الرئيسية</a>
                                <small class="text-muted mb-0">الملف الشخصي</small>
                            </div>
                            <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                <i class="bx bx-cog fs-4"></i>
                                </span>
                                <a href="{{route('profile')}}" class="stretched-link">إعدادات</a>
                                <small class="text-muted mb-0">إعدادات الملف الشخصي</small>
                            </div>
                        </div>

                        <div class="row row-bordered overflow-visible g-0">
                            <div class="dropdown-shortcuts-item col">
<span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
<i class="bx bx-help-circle fs-4"></i>
</span>
                                <a href="{{route('profile.subjects')}}" class="stretched-link">قائمة المواد</a>
                                <small class="text-muted mb-0">المكتبة الصوتية</small>
                            </div>
                            <div class="dropdown-shortcuts-item col">
<span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
<i class="bx bx-window-open fs-4"></i>
</span>
                                <a href="{{ route('profile.book.list',1) }}" class="stretched-link">الكتب</a>
                                <small class="text-muted mb-0">المكتبة الإليكترونية</small>
                            </div>
                        </div>
                    </div>

                </div>
            </li>
            <!-- Quick links -->

            <!-- Notification -->
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
                   data-bs-auto-close="outside" aria-expanded="false">
                    <i class="bx bx-bell bx-sm"></i>
                    {{--badge-notifications--}}
                </a>
            </li>
            <!--/ Notification -->

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img
                            src="{{auth()->user()->getProfilePhoto()}}"
                            alt="user-avatar"
                            class="w-px-40 h-auto rounded-circle"
                        />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{route('profile')}}">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ auth()->user()->getProfilePhoto()}}" alt
                                             class="w-px-40 h-auto rounded-circle">
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{(auth()->user()->name)}}</span>
                                    <small class="text-muted">{{auth()->user()->levelTitle()}}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{route('profile.account')}}">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">بياناتي</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{route('profile')}}">
                            <i class="bx bx-cog me-2"></i>
                            <span class="align-middle">إعدادات</span>
                        </a>
                    </li>
                    <!-- LI Notifications -->

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>

                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
document.getElementById('logout-form').submit();">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">تسجيل خروج</span>
                            <form method="POST" id="logout-form" action="{{ route('logout') }}">
                                @csrf
                            </form>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->


        </ul>
    </div>


    <!-- Search Small Screens -->
    <div class="navbar-search-wrapper search-input-wrapper  d-none">
        <input type="text" class="form-control search-input container-xxl border-0" placeholder="Search..."
               aria-label="Search...">
        <i class="bx bx-x bx-sm search-toggler cursor-pointer"></i>
    </div>


</nav>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

<div class="app-brand demo ">

    <a href="index.html" class="app-brand-link">
    <span class="app-brand-logo demo">


</span>
        <span class="app-brand-text demo menu-text fw-bold ms-2">إدارة الموقع</span>
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>

</div>

<div class="menu-inner-shadow"></div>


<ul class="menu-inner py-1">
    <!-- Dashboards -->
    <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="Dashboards">لوحة البيانات</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="dashboards-analytics.html" class="menu-link">
                    <div data-i18n="Analytics">Analytics</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="dashboards-crm.html" class="menu-link">
                    <div data-i18n="CRM">CRM</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="dashboards-ecommerce.html" class="menu-link">
                    <div data-i18n="eCommerce">eCommerce</div>
                </a>
            </li>
        </ul>
    </li>

    <!-- المحتوى -->

    <li class="menu-header small text-uppercase">
        <span class="menu-header-text">إدارة المحتوى</span>
    </li>

    <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="إدارة المحتوى">إدارة المحتوى</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="{{route('admin.lectures')}}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="المحاضرات">المحاضرات</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{route('admin.lectures.index')}}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="إضافة محاضرات">إضافة محاضرات</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{route('admin.playlists')}}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="قوائم التشغيل">قوائم التشغيل</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{route('admin.playlistsfiles')}}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="ملفات القوائم">ملفات القوائم</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="التنظيم الدراسي">التنظيم الدراسي</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item">
                <a href="{{route('admin.levels.index')}}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="المراحل والفئات">المراحل والفئات</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{route('admin.subjects.index')}}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="المواد">المواد</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-header small text-uppercase">
        <span class="menu-header-text">فريق العمل</span>
    </li>

    <li class="menu-item">
        <a href="{{route('admin.teams')}}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div data-i18n="فرق العمل">فرق العمل</div>
        </a>
    </li>
    <li class="menu-item">
        <a href="{{route('admin.teams.users')}}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div data-i18n="أعضاء الفريق">أعضاء الفريق</div>
        </a>
    </li>


    <!-- Apps & Pages -->
    <li class="menu-header small text-uppercase">
        <span class="menu-header-text">إدارة المستخدمين</span>
    </li>
{{--    <li class="menu-item">--}}
{{--        <a href="{{route('admin.users.list')}}" class="menu-link">--}}
{{--            <i class="menu-icon tf-icons bx bx-user"></i>--}}
{{--            <div data-i18n="الكل">الكل</div>--}}
{{--        </a>--}}
{{--    </li>--}}
{{--    <li class="menu-item">--}}
{{--        <a href="{{route('admin.users.list.role',\App\Enums\Roles::Admin)}}" class="menu-link">--}}
{{--            <i class="menu-icon tf-icons bx bx-user"></i>--}}
{{--            <div data-i18n="المديرين">المديرين</div>--}}
{{--        </a>--}}
{{--    </li>--}}

    <li class="menu-item">
        <a href="{{route('admin.users.list.role',\App\Enums\Roles::Teacher)}}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div data-i18n="المدرسين">المدرسين</div>
        </a>
    </li>
    <li class="menu-item">
        <a href="{{route('admin.users.list.role',\App\Enums\Roles::Student)}}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div data-i18n="الطلاب">الطلاب</div>
        </a>
    </li>
    <li class="menu-item">
        <a href="{{route('admin.users.list.role',\App\Enums\Roles::Subscriber)}}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div data-i18n="المشتركين من الخارج">المشتركين من الخارج</div>
        </a>
    </li>
</ul>

</aside>

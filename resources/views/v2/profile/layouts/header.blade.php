<!-- Header -->
@php
    require_once(app_path() . '/Components/Util.php');
@endphp

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="user-profile-header-banner">
                <img src="{{ asset ('assets/img/pages/profile-banner.png')}}" alt="Banner image" class="rounded-top">
            </div>
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                    <img
                        src="{{auth()->user()->getProfilePhoto()}}"
                        alt="user image"
                        class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img"
                    />
                </div>
                <div class="flex-grow-1 mt-3 mt-sm-5">
                    <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4>{{(auth()->user()->name)}}</h4>
                            <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item fw-semibold">
                                    <i class='bx bx-pen'></i>
                                    @if(!empty(auth()->user()->details()->job))
                                    {{auth()->user()->details()->job}}
                                    @else
                                        لم تحددد
                                    @endif
                                </li>
                                <li class="list-inline-item fw-semibold">
                                    <i class='bx bx-map'></i>
                                    @if(!empty(auth()->user()->details()->address))
                                    {{auth()->user()->details()->address}}
                                    @else
                                        لم تحددد
                                    @endif
                                </li>
                                <li class="list-inline-item fw-semibold">
                                    <i class='bx bx-calendar-alt'></i>
                                    {{auth()->user()->levelTitle()}}
                                </li>
                            </ul>
                        </div>
                        <a href="{{route('password.request')}}" class="btn btn-primary text-nowrap">
                            <i class='bx bx-user-check'></i>مشاركة
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Header -->

@extends('v2.profile.layouts.app')

@section('content')

        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">الرئيسية /</span> صفحتي
        </h4>

        <!-- Header -->
        @include('v2.profile.layouts.header')
        <!--/ Header -->

        @php
        use App\Http\Controllers\ProfileController;
            $lectures = ProfileController::getLatestLectures();
            $books = ProfileController::getLatestBooks();
        @endphp

        <!-- User Profile Content -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">

                <div class="divider">
                    <div class="divider-text"> أحدث المحاضرات - الأسبوع : {{ $lectures[0]->lecture_number }}</div>
                </div>

                <div class="row">

                    @forelse($lectures as $lecture)
                        <div class="col-lg-6 col-md-6 col-sm-6 mb-4">
                            <div class="card">
                                <div class="d-flex align-items-end row">
                                    <div class="col-8">
                                        <div class="card-body">
                                            <h6 class="card-title mb-1 text-nowrap">{{$lecture->subject_title}}</h6>
                                            <small class="d-block mb-3 text-nowrap">{{$lecture->lecture_title}}</small>

                                            <h5 class="card-title text-primary mb-1">{{$lecture->visits}} مشاهدة</h5>
                                            <small class="d-block mb-3 text-muted">زمن التشغيل :{{ProfileController::getMp3Info($lecture->lecture_id)['duration']}}</small>

                                            <a href="{{route('profile.file.view',$lecture->lecture_id)}}" class="btn btn-sm btn-primary">استعراض الآن ..</a>
                                        </div>
                                    </div>
                                    <div class="col-4 pt-3 ps-0">
                                        <img src="{{asset('assets/img/illustrations/media-audio.png')}}" width="90" height="140" class="rounded-start" alt="View Sales">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse

                </div>


                <div class="divider">
                    <div class="divider-text">فضيلة الشيخ الوالد حفظه الله</div>
                </div>

                <div class="row">
                    @forelse($books as $book)
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-4">
                        <div class="card">
                            <div class="d-flex align-items-end row">
                                <div class="col-8">
                                    <div class="card-body">
                                        <h6 class="card-title mb-1 text-nowrap">محاضرات</h6>
                                        <small class="d-block mb-3 text-nowrap">محاضرات التزكية والتربية والفقه</small>

                                        <h5 class="card-title text-primary mb-1">$48.9k</h5>
                                        <small class="d-block mb-3 text-muted">78% of target</small>

                                        <a href="javascript:;" class="btn btn-sm btn-primary">استعراض الآن ..</a>
                                    </div>
                                </div>
                                <div class="col-4 pt-3 ps-0">
                                    <img src="../../assets/img/illustrations/read-light.png" width="90" height="140" class="rounded-start" alt="View Sales">
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty()
                    @endforelse

                </div>

                <!-- Activity Timeline -->
                <div class="card card-action mb-4">
                    <div class="card-header align-items-center">
                        <h5 class="card-action-title mb-0"><i class='bx bx-list-ul me-2'></i>مشاركات مفيدة</h5>
                        <div class="card-action-element">
                            <div class="dropdown">
                                <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="javascript:void(0);">Share timeline</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">Suggest edits</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">Report bug</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="timeline ms-2">
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-warning"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Client Meeting</h6>
                                        <small class="text-muted">Today</small>
                                    </div>
                                    <p class="mb-2">Project meeting with john @10:15am</p>
                                    <div class="d-flex flex-wrap">
                                        <div class="avatar me-3">
                                            <img src="{{ asset ('assets/img/avatars/3.png')}}" alt="Avatar" class="rounded-circle" />
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Lester McCarthy (Client)</h6>
                                            <span>CEO of Infibeam</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-info"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Create a new project for client</h6>
                                        <small class="text-muted">2 Day Ago</small>
                                    </div>
                                    <p class="mb-0">Add files to new design frm_folder</p>
                                </div>
                            </li>
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-primary"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Shared 2 New Project Files</h6>
                                        <small class="text-muted">6 Day Ago</small>
                                    </div>
                                    <p class="mb-2">Sent by Mollie Dixon <img src="{{ asset ('assets/img/avatars/4.png')}}" class="rounded-circle ms-3" alt="avatar" height="20" width="20"></p>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="javascript:void(0)" class="me-3">
                                            <img src="{{ asset ('assets/img/icons/misc/pdf.png')}}" alt="Document image" width="20" class="me-2">
                                            <span class="h6">App Guidelines</span>
                                        </a>
                                        <a href="javascript:void(0)">
                                            <img src="{{ asset ('assets/img/icons/misc/doc.png')}}" alt="Excel image" width="20" class="me-2">
                                            <span class="h6">Testing Results</span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-success"></span>
                                <div class="timeline-event pb-0">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Project status updated</h6>
                                        <small class="text-muted">10 Day Ago</small>
                                    </div>
                                    <p class="mb-0">Woocommerce iOS App Completed</p>
                                </div>
                            </li>
                            <li class="timeline-end-indicator">
                                <i class="bx bx-check-circle"></i>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--/ Activity Timeline -->

            </div>
        </div>
        <!--/ User Profile Content -->

@endsection

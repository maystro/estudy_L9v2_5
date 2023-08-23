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

                @if($lectures->count()>0)
                    <div class="divider">
                        <div class="divider-text"> أحدث المحاضرات - الأسبوع : {{ $lectures[0]->lecture_number }}</div>
                    </div>
                @endif

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

                                            @php
                                                $file = \Illuminate\Support\Facades\Storage::path($lecture->folder).'/'.$lecture->filename;
                                                $ext = \Illuminate\Support\Facades\File::extension($lecture->filename);
                                            @endphp

                                            @if($ext=='mp3' || $ext=='mp4')
                                                <a href="{{route('profile.file.view',$lecture->lecture_id)}}" class="btn btn-sm btn-primary">استعراض الآن ..</a>
                                            @else
                                                <a href="{{route('profile.file.open',$lecture->lecture_id)}}" class="btn btn-sm btn-primary">استعراض الآن ..</a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4 pt-3 ps-0 pb-3">
                                        <img src="{{asset('assets/img/illustrations/media-audio.png')}}" width="90" height="84" class="rounded-start" alt="View Sales">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-lg-12 col-md-12 col-sm-6 mb-4">
                            <div class="card">
                                <div class="d-flex align-items-end row">
                                    <div class="col-8">
                                        <div class="card-body">
                                            <h6 class="card-title mb-1 text-nowrap">لا يوجد بيانات حتى الآن ...</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforelse

                </div>
                @if($books->count()>0)
                    <div class="divider">
                        <div class="divider-text">{{$books[0]->playlist_title}}</div>
                    </div>
                @endif
                <div class="row">
                    @forelse($books as $book)
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-4">
                        <div class="card">
                            <div class="d-flex align-items-end row">
                                <div class="col-8">
                                    <div class="card-body">
                                        <h6 class="card-title mb-1 text-nowrap">{{$book->title}}</h6>
                                        <small class="d-block mb-3 text-nowrap">{{$book->title}}</small>

                                        <h5 class="card-title text-primary mb-1">{{ProfileController::pdfPagesCount($book->id)}} صفحة</h5>
                                        <small class="d-block mb-3 text-muted">78% of target</small>

                                        <a href="{{route('profile.book.open',$book->id)}}" class="btn btn-sm btn-primary">استعراض الآن ..</a>
                                    </div>
                                </div>
                                <div class="col-4 pt-3 ps-0">
                                    <img src="{{asset('assets/img/illustrations/read-light.png')}}" width="90" height="140" class="rounded-start" alt="View Sales">
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="col-lg-12 col-md-12 col-sm-6 mb-4">
                            <div class="card">
                                <div class="d-flex align-items-end row">
                                    <div class="col-8">
                                        <div class="card-body">
                                            <h6 class="card-title mb-1 text-nowrap">لا يوجد بيانات حتى الآن ...</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endforelse
                </div>

            </div>
        </div>
        <!--/ User Profile Content -->
@endsection
@push('js')
    <!-- Page JS -->
    <script src="{{ asset ('assets/js/pages-profile.js')}}"></script>
@endpush

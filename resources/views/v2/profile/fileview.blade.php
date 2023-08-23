@extends('v2.profile.layouts.app')
@push('style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/plyr/plyr.css')}}"/>
@endpush
@section('content')
        {{ Breadcrumbs::render('profile.file.view',$subject,$document) }}
        <h5 class="card-title text-primary mb-1"></h5>
        <div class="row justify-content-center">
            @php
                $file = $document->folder.'/'.$document->filename;
                $filepath = \Illuminate\Support\Facades\Storage::url($document->folder.'/'.$document->filename);
                $ext = \Illuminate\Support\Facades\File::extension($document->filename);
            @endphp
            <div class="col-xl-8 col-lg-8 col-md-12">
                @if($ext=='mp3')
                    <div class="card h-100">
                        <div class="card-header flex-grow-0">
                            <div class="d-flex">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{asset('assets/img/elements/12.jpg')}}" alt="User" class="rounded-circle">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-1">
                                    <div class="me-2">
                                        <h5 class="mb-0">{{$document->title}}</h5>
                                        <small class="text-muted">محاضرة رقم : {{$document->lecture_number}}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <img class="img-fluid" src="{{asset('assets/img/backgrounds/event2.jpg')}}" alt="Card image cap">
                        <div class="featured-date mt-n4 ms-4 bg-white rounded w-px-50 shadow text-center p-1">
                            @php
                                $day = \Carbon\Carbon::parse($document->updated_at);
                            @endphp
                            <h5 class="mb-0 text-dark enFont lrt">{{$day->day}}</h5>
                            <span class="text-primary enFont">{{$day->monthName}}</span>
                        </div>

                        <div class="card-body">
                            <audio class="w-100" id="media-player" controls>
                                <source src="{{$filepath}}" type="audio/mp3"/>
                            </audio>
                        </div>

                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                @livewire('v2.lecture-like-action',['lecture'=>$document])
                            </div>
                        </div>
                    </div>
                @endif
                @if($ext=='mp4')
                    <div class="card h-100">
                        <div class="card-header flex-grow-0">
                            <div class="d-flex">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{asset('assets/img/elements/12.jpg')}}" alt="User" class="rounded-circle">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-1">
                                    <div class="me-2">
                                        <h5 class="mb-0">{{$document->title}}</h5>
                                        <small class="text-muted">محاضرة رقم : {{$document->lecture_number}}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <video class="w-100" id="media-player" playsinline controls>
                                <source src="{{$filepath}}" type="video/mp4"/>
                            </video>
                        </div>

                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                @livewire('v2.lecture-like-action',['lecture'=>$document])
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
@endsection
@push('js')
    <!-- Page JS -->
    <script src="{{ asset ('assets/js/pages-profile.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/plyr/plyr.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            const audioPlayer = new Plyr('#media-player');
        })
    </script>
@endpush

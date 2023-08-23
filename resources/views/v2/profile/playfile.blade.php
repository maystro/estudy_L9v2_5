@extends('v2.profile.layouts.app')
@push('style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/plyr/plyr.css')}}"/>
@endpush
@section('content')

        {{ Breadcrumbs::render('profile.media.open',$media_file) }}

        <h5 class="card-title text-primary mb-1"></h5>
        <div class="row">
            @php
                $filename = $media_file->file_name ;
                $filepath = public_path($media_file->file_folder)."/$filename";
                $filepath = asset($media_file->file_folder)."/$filename";
            @endphp

            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card h-100">
                    <div class="card-header flex-grow-0">
                        <div class="d-flex">
                            <div class="avatar flex-shrink-0 me-3">
                                <img src="{{asset('assets/img/elements/12.jpg')}}" alt="User" class="rounded-circle">
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-1">
                                <div class="me-2">
                                    <h5 class="mb-0">{{$media_file->file_title}}</h5>
                                    <small class="text-muted">محاضرة رقم : {{$media_file->file_idx}}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <video class="w-100" id="plyr-video-player" playsinline controls>
                        <source src="{{$filepath}}" type="video/mp4"/>
                    </video>


                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            @livewire('v2.card-action',['media_id'=>$media_file->file_id])
                        </div>
                    </div>

                </div>
            </div>

        </div>

@endsection
@push('js')
    <!-- Page JS -->
    <script src="{{ asset ('assets/js/pages-profile.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/plyr/plyr.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            const videoPlayer = new Plyr('#plyr-video-player');
        })
    </script>
@endpush

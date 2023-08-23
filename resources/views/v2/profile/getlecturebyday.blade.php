@extends('v2.profile.layouts.app')
@php
    use Illuminate\Support\Facades\File;
@endphp

@section('content')

        {{ Breadcrumbs::render('profile.lecturesByDay') }}

        <!-- User Profile Content -->
        <ul class="list-group list-group-timeline list-group-horizontal">
            <li class="list-group-item list-group-timeline-primary">{{$level->title}}</li>
            <li class="list-group-item list-group-timeline-warning">عدد الأيام : {{count($lectures)}}</li>
            <li class="list-group-item list-group-timeline-info">عدد المحاضرات : {{($count)}}</li>
        </ul>

        <h5 class="card-title text-primary mb-1">
        </h5>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                @foreach($lectures as $lecture_number=>$lecture)

                    <div class="divider">
                        <div class="divider-text"> اليوم رقم : {{ $lecture_number }}</div>
                    </div>

                    <div class="card">
                        <div class="d-flex align-items-end row">
                            <div class="col-12">

                                <div class="card-body">

                                    <div class="table-responsive text-wrap">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th style="width: 5%">رقم</th>
                                                <th style="width: 5%">المادة</th>
                                                <th>ملف المحاضرة</th>
                                            </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                            @foreach($lecture as $files)

                                                <tr class="table-default">
                                                    <td><strong>{{$loop->index+1}}</strong></td>
                                                    <td>{{$files[0]->subject_title}}</td>
                                                    <td>
                                                        <div class="list-group">
                                                            @foreach($files as $file)
                                                                @php
                                                                    $filepath= public_path($file->frm_folder).'/'.$file->filename;
                                                                    $ext = File::extension($filepath);
                                                                    $ext_icon='';
                                                                    switch ($ext) {
                                                                    case 'pdf':
                                                                    $ext_icon="<i class='bx bxs-file-pdf'></i>";
                                                                    break;
                                                                    case 'mp3':
                                                                    $ext_icon="<i class='bx bxs-music'></i>";
                                                                    break;
                                                                    case 'mp4':
                                                                    $ext_icon="<i class='bx bx-video' ></i>";
                                                                    break;
                                                                    case 'jpg'||'png':
                                                                    $ext_icon="<i class='bx bx-image' ></i>";
                                                                    break;
                                                                    default:
                                                                    # code...
                                                                    break;
                                                                    }

                                                                @endphp

                                                                <a href="{{route('profile.file.view',$file->id)}}"
                                                                   class="list-group-item list-group-item-action">
                                                                    @php
                                                                        echo $ext_icon;
                                                                    @endphp
                                                                    {{$file->file_title}}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <!--/ User Profile Content -->
@endsection
@push('js')
    <!-- Page JS -->
    <script src="{{ asset ('assets/js/pages-profile.js')}}"></script>
@endpush

@extends('v2.profile.layouts.app')
@php
    use Illuminate\Support\Facades\File;
@endphp
@section('content')
    {{ Breadcrumbs::render('profile.lectures',$subject_name,$subject_id) }}

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">

                <div class="divider">
                    <div class="divider-text"> مادة {{ $subject_name }} : {{sizeof($lectures)}} محاضرة </div>
                </div>

                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-12">
                            <div class="card-body">

                                <div class="table-responsive text-wrap">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%">اليوم</th>
                                            <th>عنوان المحاضرة</th>
                                        </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0">
                                        @foreach($lectures as $lecture)
                                            <tr class="table-default">
                                                <td><strong>{{$lecture[0]->lecture_number}}</strong></td>
                                                <td>
                                                    <div class="list-group">
                                                        @foreach($lecture as $file)
                                                            @php
                                                                $filepath= storage_path($file->folder).'/'.$file->filename;
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

                                                            @if($ext=='mp3' || $ext=='mp4')
                                                                <a href="{{route('profile.file.view',$file->id)}}"
                                                                   target="_blank"
                                                                   class="list-group-item list-group-item-action">
                                                                    @php
                                                                        echo $ext_icon;
                                                                    @endphp
                                                                    {{$file->title}}
                                                                </a>
                                                            @else
                                                                <a href="{{route('profile.file.open',$file->id)}}"
                                                                   target="_blank"
                                                                   class="list-group-item list-group-item-action">
                                                                    @php
                                                                        echo $ext_icon;
                                                                    @endphp
                                                                    {{$file->title}}
                                                                </a>
                                                            @endif
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

        </div>
    </div>
@endsection
@push('js')
    <!-- Page JS -->
    <script src="{{ asset ('assets/js/pages-profile.js')}}"></script>
@endpush

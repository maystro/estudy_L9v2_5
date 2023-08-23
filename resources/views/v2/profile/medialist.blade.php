@extends('v2.profile.layouts.app')
@php
    use Illuminate\Support\Facades\File;
@endphp

@section('content')
            {{ Breadcrumbs::render('profile.playlist',$playlist->title,$playlist->id) }}

        <div class="divider">
            <div class="divider-text">{{ $playlist->title }}</div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-12">
                            <div class="card-body">

                                <div class="table-responsive text-wrap">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%">رقم</th>
                                            <th>عنوان المادة</th>
                                        </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0">
                                        @foreach($files as $index=>$file)

                                            <tr class="table-default">
                                                <td><strong>{{$index+1}}</strong></td>
                                                <td>
                                                    <div class="list-group">
                                                        @php
                                                            $ext = File::extension($file->file_name);
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
                                                            <a href="{{route('profile.file.view',$file->file_id)}}"
                                                               class="list-group-item list-group-item-action">
                                                        @else
                                                            <a href="{{route('profile.file.open',$file->file_id)}}"
                                                               class="list-group-item list-group-item-action">
                                                        @endif
                                                            @php
                                                                echo $ext_icon;
                                                            @endphp
                                                            {{$file->file_title}}
                                                        </a>


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

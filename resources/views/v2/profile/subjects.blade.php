@extends('v2.profile.layouts.app')
@push('style')
    <style>
        .card{
            height: 100%;
        }
    </style>
@endpush
@section('content')
        {{ Breadcrumbs::render('profile.subjects') }}

        <div class="divider">
            <div class="divider-text">اضغط علي المادة لإظهار المحاضرات الخاصة بها</div>
        </div>

        <div class="row">
            @foreach($subjects as $subject)
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-6 mb-4">
                    <a href="{{route('profile.subject.lectures',$subject->id)}}" class="link-danger">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="avatar avatar-xl border-5 border-light-primary rounded-circle mx-auto mb-4">
                                    <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bx-book-open bx-lg"></i></span>
                                </div>
                                <h4 class="card-title mb-1 me-2">{{$subject->title}}</h4>
                                <small class="d-block mb-2 text-success">{{\App\Http\Controllers\ProfileController::lecturesCount($subject->id)}} محاضرة</small>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
@endsection
@push('js')
    <!-- Page JS -->
    <script src="{{ asset ('assets/js/pages-profile.js')}}"></script>
@endpush

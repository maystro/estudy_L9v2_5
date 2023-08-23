@extends('v2.admin.layouts.app')
@push('style')
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/dropzone/dropzone.css')}}" />
{{--    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />--}}

    <style>
        table th {
            font-size: inherit !important;
        }
        .dz-message {
            margin: 0.1em 0;
            font-weight: 500;
            text-align: center
        }

        .dropzoneform {
            width: 100%;
            height: 30%;
            position: relative;
            padding: 1.5rem;
            cursor: pointer;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .dz-preview {
            display: flex;
            vertical-align: top;
            margin: 1.5rem 0 0 1.5rem;
            background: #fff;
            font-size: .8125rem;
            box-sizing: content-box;
            cursor: default;
        }
        .disappear
        {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            visibility: hidden;
        }
    </style>
@endpush

@section('content')

    <form action="{{route('upload')}}"
          method="post" enctype="multipart/form-data"
          class="dropzone dz-clickable" id="my-dropzone">
        @method('post')
        @csrf
        <div class="dz-message needsclick">
            اسحب الملفات هنا أو قم بالضغط لاختيار ملف / ملفات
            <span class="note needsclick">يمكنك تحميل ملفات صوتية وصور وفيديو و Adobe Acrobat pdf , Microsoft Word </span>
        </div>
    </form>
    <button class="btn btn-danger" id ="addUpload">إضافة ملف / محاضرة</button>

    <div class="row mb-5" id="cardsHolder">
    </div>

    <div class="col-md-6 col-lg-6 disappear" id="cardTemplate" >
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title text-center"></h5>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: 0%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="fieldsContainer">
                <div class="card-body">
                    <div class="row">
                        <label for="defaultFormControlInput" class="form-label">عنوان المحاضرة</label>
                        <div class="col-8 mb-3">
                            <input type="text"
                                   class="form-control"
                                   placeholder="اكتب عنوان المحاضرة هنا"/>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <input type="number" min="1" max="100"
                                       class="form-control"
                                       wire:model="frm_lecture_number"
                                       placeholder="رقم"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label for="defaultFormControlInput" class="form-label">المستوى</label>
                        <div class="col-12 mb-3">
                            <div class="input-group input-group-merge">
                                <select id="level_id"
                                        class="form-select text-capitalize">
                                    <option value="0">اختر المستوى</option>
                                    @foreach(\App\Models\Level::all() as $level)
                                        <option value="{{$level->id}}">{{$level->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label for="defaultFormControlInput" class="form-label">المادة</label>
                        <div class="col-sm-12 mb-3">
                            <div class="input-group input-group-merge">
                                <select id="subject_id"
                                        wire:model="frm_subject_id"
                                        class="form-select text-capitalize">
                                    <option value="0">اختر المادة</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 row">
                        <div class="col-3 text-start">
                            <label class="switch me-0 enFont">
                                <input type="checkbox" class="switch-input" checked="">
                                <span class="switch-toggle-slider">
                          <span class="switch-on">
                            <i class="bx bx-check"></i>
                          </span>
                          <span class="switch-off">
                            <i class="bx bx-x"></i>
                          </span>
                        </span>
                                <span class="switch-label"></span>
                            </label>
                        </div>
                        <div class="col-9 text-end">
                            <h6 class="mb-0">تفعيل</h6>
                            <small class="text-muted">حيث انها لا تظهر في العرض</small>
                        </div>
                    </div>
                    <div class="d-flex flex-grow-1 g-2 justify-content-start mt-3 ltr enFont ">
                        <span class="badge bg-label-primary ms-2 text-lowercase"><i class="bx bx-pie-chart"></i></span>
                        <span class="badge bg-label-primary ms-2 text-lowercase"><i class="bx bx-time"></i></span>
                        <span class="badge bg-label-primary ms-2 text-lowercase"><i class="bx bx-file-blank"></i></span>
                        {{--                    <a class="badge bg-label-warning ms-2 text-lowercase" href="{{$frm_file_url}}" target="_blank"><i class="bx bx-play-circle"></i></a>--}}
                    </div>
                </div>
                <div class="card-footer" data-id="" data-key="">
                    <a class="btn btn-primary save-btn">حفظ</a>
                    <a class="btn btn-secondary cancel-btn">إلغاء</a>
                </div>
            </div>
        </div>

    </div>


@endsection

@push('lw-js')
    <script src="{{ asset ('assets/vendor/libs/dropzone/dropzone.js')}}"></script>

    <script>
        Dropzone.autoDiscover = false; // must to work with jquery()

        $(document).ready(e=>{

            let fileCards=[];
            let cardTemplate = $('#cardTemplate');
            let cardsContainer = $('#cardsHolder');

            let self = this;

            $("#my-dropzone").dropzone({
                paramName: "file", // The name that will be used to transfer the file
                maxFilesize: 4,
                parallelUploads : 1,
                success: function(file,response)
                {
                    showCardDetails(file, response);
                },
                complete : function(file, response) {
                },
                uploadprogress : function (file, progress, bytesSent)  {
                    updateFileCardProgress(file, progress, bytesSent)
                },
                addedfile : file =>{
                    addFileCard(file);
                }
            })


            $('#addUpload').on('click',function(){
                //const $dropzone = $('#my-dropzone')
                //$dropzone.addClass('disappear')
                //$dropzone.click();
            })

            function addFileCard(file) {
                cardTemplate = cardTemplate.clone().removeClass('disappear')
                let uuid = file.upload.uuid;
                let fileCard=[];

                cardTemplate.attr('id',uuid) //set unique id
                cardTemplate.find('.card-title').text(file.upload.filename)

                fileCard['id'] = uuid;
                fileCard['container'] = cardTemplate;

                fileCards.push(fileCard);
                cardsContainer.append(cardTemplate);
            }
            function updateFileCardProgress(file, progress, bytesSent){
                let cardFileId = $('#' + file.upload.uuid)
                let $progress = cardFileId.find('.progress .progress-bar');
                $progress.css('width',progress+'%')
            }
            function showCardDetails(file,response) {
                let uuid = file.upload.uuid;
                let $targetCard = null;
                $.each(fileCards,function (key, item){
                    if(item.id === uuid)
                    {
                        $targetCard = item.container;
                        return 0;
                    }
                })

                if($targetCard !=null)
                {
                    console.log($targetCard)
                    $targetCard.find('.card-footer')
                        .attr('data-id',response.data.id)
                        .attr('data-key',uuid)
                }
            }


        })

    </script>
@endpush

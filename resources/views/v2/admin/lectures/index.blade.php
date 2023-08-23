@extends('v2.admin.layouts.app')
@push('style')
    <link rel="stylesheet" href="{{ asset ('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}"/>
@endpush

@section('content')
    <!-- Content -->
    @livewire('admin.lectures.upload-list')
    <!-- / Content -->
@endsection


@push('js')
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>

    <script>
        $(".selectpicker").selectpicker(function (){});

        $.fn.extend({
            hasClasses: function (selectors) {
                var self = this;
                for (var i in selectors) {
                    if ($(self).hasClass(selectors[i]))
                        return true;
                }
                return false;
            }
        });
        $.fn.extend({
            removeClasses: function (selectors) {
                var self = this;
                for (var i in selectors) {
                    if ($(self).removeClass(selectors[i]))
                        return true;
                }
                return false;
            }
        });

        // document.addEventListener("DOMContentLoaded", () => {
        //     Livewire.hook('component.initialized', (component) => {
        //             if(component.name==='admin.lectures.upload-single-lecture')
        //             {
        //                 //console.log(component.name);
        //             }
        //         })
        //
        //     var toastLiveExample = $('#liveToast')
        //     window.addEventListener('show_toast', function (e) {
        //             var classes = ['bg-primary','bg-secondary','bg-success','bg-warning','bg-info','bg-danger','bg-dark']
        //             var toast = new bootstrap.Toast(toastLiveExample)
        //             $('#liveToast').find('#message').html(e.detail.message);
        //             $('#liveToast').find('#title').html(e.detail.title);
        //             $('#liveToast').removeClasses(classes);
        //             $('#liveToast').addClass(e.detail.class)
        //             toast.show()
        //         })
        //
        //     $(window).on('livewire-upload-progress',function(e){
        //         //$('.progress-bar').css('width',e.detail.progress+'%')
        //     })
        //
        //     $('')
        // });

        $(document).ready((e)=>{
            $('body').on('click','.btn-close-card',function(){
                //$(this).parents('.card').fadeOut();
            });
        })
    </script>
@endpush

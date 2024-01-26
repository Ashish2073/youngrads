<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
@include('inc.variable')
<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link rel="icon" sizes="128x128" href="{{ asset('img/touch/icon-128x128.png') }}">
    <!-- Styles -->
    <link rel="stylesheet" type="text/css" id="theme" href="{{ asset('dashboard/css/theme-dark.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/css/dt.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/css/style.css') }}">
    <link rel="icon" href="{{ asset('dashboard/img/favicon.png') }}" type="" sizes="32x32">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.dataTables.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">


    <!-- summernote css-->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <!-- alertify css-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <style>
        .pointer {
            cursor: pointer;
        }

        .icon-copy {
            width: 16px;
            height: 16px;
            padding: 0;
            margin: 0;
            vertical-align: middle;
        }

        .bootstrap-select-searchbox input {
            width: 100% !important;
        }

        .second-place > div {
            display: inline;
        }

        body.modal-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
        }

        td {
            vertical-align: middle !important;
        }

        .btn-time {
            padding: 0px 5px !important;
            margin-top: 1px;
        }

        .profile .profile-image img {
            height: 100px !important;
            cursor: pointer;
        }
    </style>
    @yield('head_script')
    @routes
</head>
<body>

<div id="app" v-cloak>
    <!-- START PAGE CONTAINER -->
    <div class="page-container">
        <!-- START PAGE SIDEBAR -->
        <div class="page-sidebar page-sidebar-fixed scroll mCustomScrollbar _mCS_1 mCS-autoHide ">
            @include('dashboard.inc.sidebar')
        </div>
        <!-- END PAGE SIDEBAR -->
        <!-- PAGE CONTENT -->
        <div class="page-content">

            <!-- START X-NAVIGATION VERTICAL -->
        @include('dashboard.inc.nav')
        <!-- END X-NAVIGATION VERTICAL -->
            <!-- START BREADCRUMB -->
        @yield('breadcrumb')
        <!-- END BREADCRUMB -->
            {{-- Start Page Content --}}
            <div class="page-content-wrap">
                @yield('content')
            </div>
            {{-- End Page Content --}}

        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->
</div>
<!-- MESSAGE BOX-->
@include('dashboard.inc.modal.logout')
@include('dashboard.inc.modal.side')
@include('dashboard.inc.modal.apply')
<!-- END MESSAGE BOX-->
<!-- START PRELOADS -->
<audio id="audio-alert" src="{{ asset('dashboard/audio/alert.mp3') }}" preload="auto"></audio>
<audio id="audio-fail" src="{{ asset('dashboard/audio/fail.mp3') }}" preload="auto"></audio>
<!-- END PRELOADS -->
<script type="text/javascript" src="{{ asset('dashboard/js/plugins/jquery/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/js/plugins/jquery/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/js/plugins/bootstrap/bootstrap.min.js') }}"></script>
{{-- <script type='text/javascript' src='{{ asset('dashboard/js/plugins/icheck/icheck.min.js') }}'></script> --}}
<script type="text/javascript"
        src="{{ asset('dashboard/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/js/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/js/plugins/jquery-validation/jquery.validate.js') }}"></script>
{{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script> --}}
{{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script> --}}
{{-- <script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/r-2.2.2/datatables.min.js"></script> --}}
<script type="text/javascript" src="{{ asset('dashboard/js/dt.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/js/plugins/bootstrap/bootstrap-select.js') }}"></script>
<script src=" //cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/fh-3.1.6/datatables.min.js"></script>
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
<!-- summernote script-->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<!--alertify -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"></script>
<script>
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
</script>
<script type="text/javascript" src="{{ asset('dashboard/js/plugins.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/js/actions.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/js/main.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/js/common.js') }}"></script>
<script src="{{ asset('js/scripts/select_ajax.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $(".x-navigation li").tooltip({
            placement: "right"
        });
        $(".pro-image").on("click", function () {
            $("input[name='profile']").click();
        });
        $('#dynamic-modal').on('hidden.bs.modal', function (e) {
            $("#dynamic-modal").removeClass("show");
            $(".modal-dialog").css('width', '500px');
        });
        $('#dynamic-modal').on('hide.bs.modal', function (e) {
            $("#dynamic-modal").removeClass("show");
            $(".modal-dialog").css('width', '500px');

        });
        $("body").on("click", "#filter-btn", function () {
            $(".filter-div").toggleClass('hide');
            $(this).toggleClass('line-through');
        });

        $(".x-navigation li").each(function () {
            if ($(this).hasClass("active")) {
                $(this).parent().parent().addClass('active');
            }
        });

        //profile pic

        validateForm($('#profile-pic'), {
            rules: {
                profile: {
                    required: true,
                }
            },
            messages: {
                profile: {
                    required: 'Please add a picture'
                }
            }
        });

        //image preview
        let imgInput = 'input[name="profile"]';

        $(document).on('change', imgInput, (e) => {

            let preview = new FileReader();

            preview.onload = (e) => $('.pro-image').attr('src', e.target.result);

            preview.readAsDataURL(e.target.files[0]);

        });

        //submit profile pic
        submitForm($('#profile-pic'), {
            beforeSubmit: function () {
                submitLoader("#profile-submit-btn");
            },
            success: function (data) {
               console.log('hello');
                // setAlert(data);
                // if (data.success) {
                //     $('.pro-image').attr('src', '/uploads/profile_pic/' + data.image);
                //      //toast('success', data.message,data.title);
                //      toastr.success(data.message,data.title);
                //     submitReset('#profile-submit-btn', 'Change Profile');
                // } else {
                //     //toast('error', data.error[0],"");
                //     toastr.error(data.error[0],data.title)
                //     submitReset('#profile-submit-btn', 'Change Profile');

                // }
            },
            error: function (data) {
                //toast("error", "Something went wrong.", "Error");
               // toastr.error("Something went wrong.","Error");
               // submitReset('#profile-submit-btn', 'Change Profile');
            }
        });

    });

    function copyToClipboard(text, el) {
        var copyTest = document.queryCommandSupported('copy');
        var elOriginalText = el.attr('data-original-title');

        if (copyTest === true) {
            var copyTextArea = document.createElement("textarea");
            copyTextArea.value = text;
            document.body.appendChild(copyTextArea);
            copyTextArea.select();
            try {
                var successful = document.execCommand('copy');
                var msg = successful ? 'Copied!' : 'Whoops, not copied!';
                el.attr('data-original-title', msg).tooltip('show');
            } catch (err) {
                console.log('Oops, unable to copy');
            }
            document.body.removeChild(copyTextArea);
            el.attr('data-original-title', elOriginalText);
        } else {
            // Fallback if browser doesn't support .execCommand('copy')
            window.prompt("Copy to clipboard: Ctrl+C or Command+C, Enter", text);
        }
    }


</script>
@yield('foot_script')
</body>
</html>

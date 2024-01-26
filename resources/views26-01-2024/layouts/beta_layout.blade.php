<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Youngrads') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
        integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css" media="screen" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="" sizes="32x32">

    {{-- dashboard css --}}

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/ui/prism.min.css') }}">
    {{-- Vendor Styles --}}
    <link rel="stylesheet" href="{{ asset('css/plugins/forms/validation/form-validation.css') }}">
    @yield('vendor-style')
    {{-- Theme Styles --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-extended.css') }}">
    <link rel="stylesheet" href="{{ asset('css/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    {{--
    <link rel="stylesheet" href="{{ asset(mix('css/themes/dark-layout.css')) }}"> --}}
    {{--
    <link rel="stylesheet" href="{{ asset(mix('css/themes/semi-dark-layout.css')) }}"> --}}
    {{-- {!! Helper::applClasses() !!} --}}
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    @php
        $configData = Helper::applClasses();
    @endphp

    {{-- Layout Styles works when don't use customizer --}}

    {{-- @if ($configData['theme'] == 'dark-layout')
        <link rel="stylesheet" href="{{ asset(mix('css/themes/dark-layout.css')) }}">
    @endif
    @if ($configData['theme'] == 'semi-dark-layout')
        <link rel="stylesheet" href="{{ asset(mix('css/themes/semi-dark-layout.css')) }}">
    @endif --}}
    {{-- Page Styles --}}
    @if ($configData['mainLayoutType'] === 'horizontal')
        <link rel="stylesheet" href="{{ asset('css/core/menu/menu-types/horizontal-menu.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/core/colors/palette-gradient.css') }}">
    {{-- Page Styles --}}
    @yield('page-style')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
    <link rel="stylesheet" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/datatables.min.css') }}">
    {{-- Laravel Style --}}
    <link rel="stylesheet" href="{{ asset('css/custom-laravel.css') }}">
    {{-- Custom RTL Styles --}}
    @if ($configData['direction'] === 'rtl' && isset($configData['direction']))
        <link rel="stylesheet" href="{{ asset('css/custom-rtl.css') }}">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        table.data-list-view.dataTable tbody tr,
        table.data-thumb-view.dataTable tbody tr {
            cursor: unset !important;
        }
    </style>

    {{-- @routes --}}

    @include('inc.gtag')

</head>

<body>
    <header class="header">
        @include('inc.nav')
    </header>

    @yield('content')

    @include('inc.footer')
    {{-- <script type="text/javascript" src="{{ asset('js/core/app.js') }}"></script>
    --}}
    {{-- <script type="text/javascript" src="{{ asset('js/core/app-menu.js') }}"></script>
    --}}

    <script src="{{ asset('vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('vendors/js/ui/prism.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/scripts/main.js') }}"></script>
    <script src="{{ asset('js/scripts/select_ajax.js') }}"></script>

    {{-- dashboard css --}}

    {{-- Vendor Scripts --}}
    <script src="{{ asset('vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('vendors/js/ui/prism.min.js') }}"></script>
    @yield('vendor-script')
    {{-- Theme Scripts --}}
    <script src="{{ asset('js/core/app-menu.js') }}"></script>
    <script src="{{ asset('js/core/app.js') }}"></script>
    <script src="{{ asset('js/scripts/components.js') }}"></script>
    @if ($configData['blankPage'] == false)
        <script src="{{ asset('js/scripts/customizer.js') }}"></script>
        <script src="{{ asset('js/scripts/footer.js') }}"></script>
    @endif
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
    <script>
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
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
        $(".has-sub").each(function() {
            let sub = $(this).find('ul');
            if (sub.children().length == 0) {
                $(this).remove();
            }
        });
    </script>
    <script src="{{ asset('js/scripts/jquery.form.js') }}"></script>
    <script src="{{ asset('js/scripts/main.js') }}"></script>
    <script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/scripts/select_ajax.js') }}"></script>
    <script>
        $('#view-profile').on('click', function() {
            id = "{{ Auth::id() }}";


            var url = `{{ url('student/${id}/viewprofile') }}`;

            console.log("hello");
            $('#apply-model').modal('show');
            $('.apply-title').html('View Profile');
            $(".dynamic-apply").html("Loading...");
            getContent({
                "url": url,
                success: function(data) {
                    $('#apply-model').find('.modal-dialog').addClass('modal-lg');
                    $(".dynamic-apply").html(data);
                }
            });
        });
    </script>

    <script>
        jQuery(document).ready(function() {
            jQuery(".navbar button.navbar-toggler").click(function() {
                jQuery(".navbar button.navbar-toggler").toggleClass("active-mobile-menu");
                jQuery(".header .navbar").toggleClass("open-nav");
                jQuery('#navbarSupportedContent').slideToggle();
            });
        });
    </script>


    {{-- page script --}}
    @yield('page-script')

    @yield('foot_script')

</body>

</html>

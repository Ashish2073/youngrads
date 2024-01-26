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

@guest
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
@endguest
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css" />
<style>
    .bs-select-all,
    .bs-select-all:hover,
    .bs-deselect-all,
    .bs-deselect-all:hover {
        background: white !important;
        color: black !important;
        border: 1px solid #babfc7 !important;
    }

    .dropdown-header {
        font-weight: bold !important;
        color: #626262;
        cursor: pointer;
    }

    .dropdown .dropdown-menu::before {
        content: unset !important;
    }

    .dropdown-toggle:focus {
        outline-color: white !important;
    }

    .select2-container--default .select2-selection--single {
        border-color: #d9d9d9
    }

    .dropdown .dropdown-menu .dropdown-item:hover {
        background-color: #ff9f43;
        color: #fff;
    }

    li.disabled .disabled {
        color: unset !important;
    }

    .bootstrap-select .dropdown-item {
        white-space: normal;
    }

    .required {
        font-weight: bold;
        font-size: 14px;
        margin-left: 5px;
    }

    table.data-list-view.dataTable tbody tr,
    table.data-thumb-view.dataTable tbody tr {
        cursor: unset !important;
    }

    .dropdown .btn:not(.btn-sm):not(.btn-lg),
    .dropdown .btn:not(.btn-sm):not(.btn-lg).dropdown-toggle {
        padding: 0.8rem 0.7rem !important;
    }

    .dropdown-toggle::after {
        left: 0 !important;
    }

    div.dataTables_wrapper div.dataTables_length select {
        padding-right: 20px;
    }

    @media screen and (max-width: 767px) {
        .breadcrumbs-top .float-left {
            float: none !important;
        }

        .breadcrumbs-top .breadcrumb-wrapper.col-12 {
            padding: 0 !important;
        }

        .breadcrumbs-top .breadcrumb-wrapper.col-12 .breadcrumb {
            padding: 10px 0 0 !important;
        }

        .fixed-left.modal .modal-dialog-aside {
            width: 100% !important;
        }

        .app-content .wizard>.steps>ul>li {
            width: 50%;
        }

    }

    @media screen and (max-width: 800px) {
        input::-webkit-input-placeholder {
            font-size: 10px !important;
        }

        .course-finder-year .filter-option-inner-inner {
            font-size: 10px !important;
        }
    }

    .app-content .wizard>.steps>ul>li.done:last-child .step {
        background-color: unset !important;
        border-color: #b8c2cc !important;
        color: #b8c2cc !important;
    }

    .app-content .wizard>.steps>ul>li.done .step {
        border-color: #b8c2cc !important;
    }
</style>

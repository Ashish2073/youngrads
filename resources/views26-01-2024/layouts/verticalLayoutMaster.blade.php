<body
        class="vertical-layout vertical-menu-modern 2-columns {{ $configData['blankPageClass'] }} {{ $configData['bodyClass']}} {{($configData['theme'] === 'light') ? '' : $configData['layoutTheme'] }}  {{ $configData['verticalMenuNavbarType'] }} {{ $configData['sidebarClass'] }} {{ $configData['footerType'] }}"
        data-menu="vertical-menu-modern" data-col="2-columns" data-layout="{{ $configData['theme'] }}">
{{-- Include Sidebar --}}

@if(request()->segment(1) == 'admin')
    @include('panels.sidebar')
@else
    @include('panels.student.sidebar')
@endif

<!-- BEGIN: Content-->
<div class="app-content content">
    <!-- BEGIN: Header-->
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    {{-- Include Navbar --}}
    @if(auth()->check() || auth('admin')->check())
        {{-- Include Navbar --}}
        @if(request()->segment(1) == 'admin')
            @include('panels.navbar')
        @else
            <div class="mobile-menu d-xl-none">
                @include('panels.student.navbar')
            </div>
        @endif
    @else
        <div class="header">
            @include('inc.nav')
        </div>
    @endif

    @if(($configData['contentLayout']!=='default') && isset($configData['contentLayout']))
        <div class="content-area-wrapper">
            <div class="{{ $configData['sidebarPositionClass'] }}">
                <div class="sidebar">
                    {{-- Include Sidebar Content --}}
                    @yield('content-sidebar')
                </div>
            </div>
            <div class="{{ $configData['contentsidebarClass'] }}">
                <div class="content-wrapper">
                    <div class="content-body">
                        {{-- Include Page Content --}}
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="content-wrapper">
            {{-- Include Breadcrumb --}}
            @if($configData['pageHeader'] === true && isset($configData['pageHeader']))
                @include('panels.breadcrumb')
            @endif

            <div class="content-body">
                {{-- Include Page Content --}}
                @yield('content')
                @include('dashboard.inc.modal.side')
            </div>
        </div>
    @endif

</div>
<!-- End: Content-->

@if($configData['blankPage'] == false && isset($configData['blankPage']))
{{--     @include('pages/customizer')--}}

{{--     @include('pages/buy-now')--}}
@endif

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

{{-- include footer --}}
@include('panels/footer')

{{-- include default scripts --}}
@include('panels/scripts')

</body>

</html>
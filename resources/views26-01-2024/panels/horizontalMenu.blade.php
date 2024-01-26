{{-- Horizontal Menu --}}
<div class="horizontal-menu-wrapper">
    <div
            class="header-navbar navbar-expand-sm navbar navbar-horizontal {{$configData['horizontalMenuClass']}} {{($configData['theme'] === 'light') ? "navbar-light" : "navbar-dark" }} navbar-light navbar-without-dd-arrow navbar-shadow navbar-brand-center"
            role="navigation" data-menu="menu-wrapper" data-nav="brand-center">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto"><a class="navbar-brand" href="dashboard-analytics">
                        <div class="brand-logo"></div>
                        <h2 class="brand-text mb-0">{{ config('app.name') }}</h2>
                    </a></li>
                <li class="nav-item nav-toggle">
                    <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
                        <i class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i>
                        <i class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary"
                           data-ticon="icon-disc"></i>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Horizontal menu content-->
        <div class="navbar-container main-menu-content" data-menu="menu-container">
            <ul class="nav navbar-nav justify-content-center" id="main-menu-navigation" data-menu="menu-navigation">
                {{-- Foreach menu item starts --}}
                @if(isset($menuData[1]))
                    @foreach($menuData[1]->menu as $menu)
                        @php if(!isset($menu['route'])) $menu['route'] = ""; @endphp
                        @php
                            $custom_classes = "";
                            if(isset($menu['classlist'])) {
                                $custom_classes = $menu['classlist'];
                            }
                            $translation = "";
                            if(isset($menu['i18n'])){
                                $translation = $menu['i18n'];
                            }
                        @endphp

                        @if(isset($menu['sub_menu']))
                            <li class="@if(isset($menu['submenu'])){{'dropdown'}}@endif nav-item {{ request()->route()->getName() == $menu['route'] ? "active" : "" }} {{ $custom_classes }}"
                            @if(isset($menu['sub_menu'])){{'data-menu=dropdown'}}@endif>
                                <a href="{{ $menu['route'] }}"
                                   class="@if(isset($menu['sub_menu'])){{'dropdown-toggle'}}@endif nav-link" @if(isset($menu['sub_menu'])){{'data-toggle=dropdown'}}@endif>
                        @else
                            <li class="nav-item {{ request()->route()->getName() == $menu['route'] ? "active" : "" }} {{ $custom_classes }}">
                                <a href="{{ route($menu['route']) }}" class="nav-link">
                        @endif
                                    <i class="{{ $menu['icon_class'] ?? "" }}"></i>
                                    <span data-i18n="{{ $translation }}">{{ $menu['menu_name'] ?? "" }}</span>
                                </a>
                                @if(isset($menu['sub_menu']))
                                    @include('panels/horizontalSubmenu', ['menu' => $menu['sub_menu']])
                                @endif
                            </li>
                    @endforeach
                @endif
                {{-- Foreach menu item ends --}}
            </ul>
        </div>
    </div>
</div>
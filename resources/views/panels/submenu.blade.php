{{-- For submenu --}}
<ul class="menu-content">
    @if(isset($menu))
        @foreach($menu as $submenu)
            @php
                $submenuTranslation = "";
                if(isset($menu['i18n'])){
                    $submenuTranslation = $menu['i18n'];
                }
            @endphp
            @if( 
                !isset($submenu['permission']) || 
                empty($submenu['permission']) || 
                (
                    isset($submenu['permission']) && 
                    auth(config('auth.guard.admin'))->user()->can($submenu['permission'])
                ) 
            )
                <li class=" {{ request()->route()->getName() == $submenu['route'] ? "active" : "" }} ">
                    <a href="{{ isset($submenu['route']) ? route($submenu['route']) : "" }}">
                        <i class="{{ isset($submenu['icon_class']) ? $submenu['icon_class'] : "" }}"></i>
                        {{-- <span class="menu-title" data-i18n="{{ $submenuTranslation }}">{{ __('locale.'.$submenu['menu_name']) }}</span> --}}
                        <span class="menu-title" data-i18n="{{ $submenuTranslation }}">{{ __($submenu['menu_name']) }}</span>
                    </a>
                    @if(isset($submenu['sub_menu']))
                        @include('panels/submenu', ['menu' => $submenu['sub_menu']])
                    @endif
                </li>
            @endif
        @endforeach
    @endif
</ul>

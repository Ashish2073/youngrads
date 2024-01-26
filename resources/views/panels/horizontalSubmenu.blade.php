{{-- For Horizontal submenu --}}
<ul class="dropdown-menu">
    @if(isset($menu))
        @foreach($menu as $submenu)
            <?php
            $custom_classes = "";
            if (isset($submenu->classlist)) {
                $custom_classes = $submenu->classlist;
            }
            $submenuTranslation = "";
            if (isset($menu->i18n)) {
                $submenuTranslation = $menu->i18n;
            }
            ?>


                @php
                    $submenuTranslation = "";
                    if(isset($menu['i18n'])){
                        $submenuTranslation = $menu['i18n'];
                    }
                @endphp
                    <li class=" {{ request()->route()->getName() == $submenu['route'] ? "active" : "" }} {{ (isset($submenu['sub_menu'])) ? "dropdown dropdown-submenu" : '' }} {{ $custom_classes }}">
                        <a href="{{ isset($submenu['route']) ? route($submenu['route']) : "" }}" class="dropdown-item {{ (isset($submenu['sub_menu'])) ? "dropdown-toggle" : '' }}"
                                {{ (isset($submenu['sub_menu'])) ? 'data-toggle=dropdown' : '' }}>
                            <i class="{{ isset($submenu['icon_class']) ? $submenu['icon_class'] : "" }}"></i>
                            <span data-i18n="{{ $submenuTranslation }}">{{ __($submenu['menu_name']) }}</span>
                        </a>
                        @if(isset($submenu['sub_menu']))
                            @include('panels/horizontalSubmenu', ['menu' => $submenu['sub_menu']])
                        @endif
                    </li>


        @endforeach
    @endif
</ul>
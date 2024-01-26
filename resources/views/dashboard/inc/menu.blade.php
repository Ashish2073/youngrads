@foreach(config('menu') as $item)
    @php
        $permission = [];
        if(isset($item['sub_menu'])) {
            foreach($item['sub_menu'] as $value) {
                $permission[] = $value['permission'];
            }
        } else {
            $permission[] = $item['permission'];
        }
        $item_name = isset($item['route']) ? $item['route'] : "";
        $item_route = isset($item['route']) ? route($item['route']) : "";
    @endphp
    @if(can($permission))
        <li class="{{ request()->route()->getName() == $item_name ? "active" : "" }} {{ isset($item['sub_menu']) ? "xn-openable" : "" }}">

            <a href="{{ $item_route }}"><span class="{{ $item['icon_class'] }}"></span> <span
                        class="xn-text">{{ $item['menu_name'] }}</span></a>
            @if(isset($item['sub_menu']))
                <ul>
                    @foreach ($item['sub_menu'] as $sub_item)
                        @if(auth(config('auth.guard.admin'))->user()->can($sub_item['permission']))
                            <li class="{{ request()->route()->getName() == $sub_item['route'] ? "active" : "" }}">
                                <a href="{{ route($sub_item['route']) }}"><span
                                            class="{{ $sub_item['icon_class'] }}"></span> <span
                                            class="xn-text">{{ $sub_item['menu_name'] }}</span></a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </li>
    @endif
@endforeach
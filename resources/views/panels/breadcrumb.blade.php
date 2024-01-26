<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2 ">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">@yield('title')</h2>
                <div class="breadcrumb-wrapper col-12">
                    @if(@isset($breadcrumbs))
                        <ol class="breadcrumb">
                            {{-- this will load breadcrumbs dynamically from controller --}}
                            @foreach ($breadcrumbs as $breadcrumb)
                                <li class="breadcrumb-item">
                                    @if(isset($breadcrumb['link']))
                                        <a href="{{ route($breadcrumb['link']) }}">
                                            {{-- <a href=""> --}}
                                            @endif
                                            {{$breadcrumb['name']}}
                                            @if(isset($breadcrumb['link']))
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    @endisset
                </div>
            </div>
        </div>
    </div>
    <div class="content-header-right text-md-right col-md-3 col-12">
        <div class="form-group breadcrum-right">
            <div class="dropdown">
                @yield('breadcumb-right')
{{--                <button class="btn-icon btn btn-primary btn-round btn-sm"><i class="feather icon-plus" aria-expanded="false"></i></button>--}}
{{--                <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="#">Chat</a><a--}}
{{--                            class="dropdown-item" href="#">Email</a><a class="dropdown-item" href="#">Calendar</a></div>--}}
            </div>
        </div>
    </div>
</div>
@if ($configData['mainLayoutType'] == 'horizontal' && isset($configData['mainLayoutType']))
    <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu {{ $configData['navbarColor'] }} navbar-fixed">
        <div class="navbar-header d-xl-block d-none">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item"><a class="navbar-brand" href="dashboard-analytics">
                        <div class="brand-logo"></div>
                    </a></li>
            </ul>
        </div>
    @else
        <nav
            class="header-navbar navbar-expand-lg navbar navbar-with-menu {{ $configData['navbarClass'] }} navbar-light navbar-shadow {{ $configData['navbarColor'] }}">
@endif
<div class="navbar-wrapper">
    <div class="navbar-container content">
        <div class="navbar-collapse" id="navbar-mobile">
            <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav">
                    <li class="nav-item mobile-menu d-xl-none mr-auto"><a
                            class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i
                                class="ficon feather icon-menu"></i></a></li>
                </ul>
                <ul class="nav navbar-nav bookmark-icons d-none">
                    <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-todo" data-toggle="tooltip"
                            data-placement="top" title="Todo"><i class="ficon feather icon-check-square"></i></a></li>
                    <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-chat" data-toggle="tooltip"
                            data-placement="top" title="Chat"><i class="ficon feather icon-message-square"></i></a>
                    </li>
                    <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-email" data-toggle="tooltip"
                            data-placement="top" title="Email"><i class="ficon feather icon-mail"></i></a></li>
                    <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-calender" data-toggle="tooltip"
                            data-placement="top" title="Calendar"><i class="ficon feather icon-calendar"></i></a></li>
                </ul>
                <ul class="nav navbar-nav d-none">
                    <li class="nav-item d-none d-lg-block"><a class="nav-link bookmark-star"><i
                                class="ficon feather icon-star warning"></i></a>
                        <div class="bookmark-input search-input">
                            <div class="bookmark-input-icon"><i class="feather icon-search primary"></i>
                            </div>
                            <input class="form-control input" type="text" placeholder="Explore Vuexy..."
                                tabindex="0" data-search="laravel-search-list" />
                            <ul class="search-list search-list-bookmark"></ul>
                        </div>
                        <!-- select.bookmark-select-->
                        <!--   option 1-Column-->
                        <!--   option 2-Column-->
                        <!--   option Static Layout-->
                    </li>
                </ul>
            </div>

            <ul class="nav navbar-nav float-right">

                <li class="dropdown dropdown-language nav-item d-none">
                    <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="flag-icon flag-icon-us"></i>
                        <span class="selected-language">English</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                        <a class="dropdown-item" href="{{ url('lang/en') }}" data-language="en">
                            <i class="flag-icon flag-icon-us"></i>English
                        </a>
                        <a class="dropdown-item" href="{{ url('lang/fr') }}" data-language="fr">
                            <i class="flag-icon flag-icon-fr"></i>French
                        </a>
                        <a class="dropdown-item" href="{{ url('lang/de') }}" data-language="de">
                            <i class="flag-icon flag-icon-de"></i>German
                        </a>
                        <a class="dropdown-item" href="{{ url('lang/pt') }}" data-language="pt">
                            <i class="flag-icon flag-icon-pt"></i>Portuguese
                        </a>
                    </div>
                </li>


                @if (session('permissionerror'))
                    <div class="alert alert-danger mt-2 py-2" role="alert" style="font-size: 20px">
                        <button type="button" id="permission_error" class="close" data-dismiss="alert"
                            aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Fail!</strong> {{ session('permissionerror') }}
                    </div>
                @endif


                @php  $userrole=json_decode(auth('admin')->user()->getRoleNames(),true) ??[] ; @endphp

                @if (in_array('moderator', $userrole) ||
                        auth('admin')->user()->getRoleNames()[0] == 'Admin' ||
                        in_array('supermoderator', $userrole))
                    @php $messageUnreadData=\App\Models\Admin::getunreadmessage(0); @endphp
                    <li class="dropdown dropdown-notification nav-item "><a class="nav-link nav-link-label"
                            href="#" data-toggle="dropdown"><i
                                class="ficon feather icon-message-square">A&S&M&U</i><span
                                class="badge badge-pill badge-primary badge-up">{{ $messageUnreadData->count() }}</span></a>
                        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                            <li class="dropdown-menu-header">
                                <div class="dropdown-header m-0 p-2">


                                    <h3 class="white">{{ $messageUnreadData->count() }} New</h3><span
                                        class="white darken-2">My Messages</span>
                                </div>
                            </li>
                            @php   $application_id=[]; @endphp
                            @foreach ($messageUnreadData as $k => $data)
                                <li class="scrollable-container media-list">
                                    <a class="d-flex justify-content-between" href="javascript:void(0)">
                                        <div class="media d-flex align-items-start">
                                            <div class="media-left"><i
                                                    class="ficon feather icon-message-square font-medium-5 primary"></i>
                                            </div>
                                            <div class="media-body">
                                                @php $application_id[$k]=$data->application_id; @endphp

                                                <h6 class="primary media-heading">{{ $data->application_number }}!
                                                </h6>


                                                <small class="notification-text">
                                                    {{ \Str::limit($data->message, 40, '....') }}
                                                </small>
                                            </div>
                                            <small>
                                                <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">
                                                    @php

                                                        $formattedTime = \Carbon\Carbon::parse(
                                                            $data->time,
                                                        )->diffForHumans();
                                                    @endphp
                                                    {{ $formattedTime }}

                                                    {{-- {{ \Carbon\Carbon::parse($data->time)->diffForHumans() }}
                                            {{ date('d M Y h:i A', strtotime($data->time)) }} --}}
                                                </time>
                                            </small>
                                        </div>
                                    </a>
                                </li>
                            @endforeach


                            @php session()->put('application_id_message', $application_id) ; @endphp

                            @if ($messageUnreadData->count() > 0)
                                @if (\Request::segment(2) != 'applications-all')
                                    <li class="dropdown-menu-footer"><a class="dropdown-item p-1 text-center"
                                            href="{{ url('admin/applications-all') }}">Read
                                            all Messages</a></li>
                                @endif
                                @if (\Request::segment(2) == 'applications-all')
                                    <li class="dropdown-menu-footer"><a class="dropdown-item p-1 text-center"
                                            data-id="{{ implode(',', $application_id) }}" id="showlatestmessage"
                                            href="javascript:void(0)">Read all
                                            Message</a></li>
                                @endif
                            @else
                                <li class="dropdown-menu-footer"><a class="dropdown-item p-1 text-center"
                                        href="javascript:void(0)">No new Message</a></li>

                            @endif
                        </ul>
                    </li>


                    @php $messageadminmoderatorUnreadData=\App\Models\Admin::getunreadmessage(1); @endphp
                    <li class="dropdown dropdown-notification nav-item "><a class="nav-link nav-link-label"
                            href="#" data-toggle="dropdown"><i
                                class="ficon feather icon-message-square">A&S&M</i><span
                                class="badge badge-pill badge-primary badge-up">{{ $messageadminmoderatorUnreadData->count() }}</span></a>
                        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                            <li class="dropdown-menu-header">
                                <div class="dropdown-header m-0 p-2">


                                    <h3 class="white">{{ $messageadminmoderatorUnreadData->count() }} New</h3><span
                                        class="white darken-2">My Messages</span>
                                </div>
                            </li>
                            @php   $application_moderator_admin_id=[]; @endphp
                            @foreach ($messageadminmoderatorUnreadData as $k => $data)
                                <li class="scrollable-container media-list">
                                    <a class="d-flex justify-content-between" href="javascript:void(0)">
                                        <div class="media d-flex align-items-start">
                                            <div class="media-left"><i
                                                    class="ficon feather icon-message-square font-medium-5 primary"></i>
                                            </div>
                                            <div class="media-body">
                                                @php $application_moderator_admin_id[$k]=$data->application_id; @endphp

                                                <h6 class="primary media-heading">
                                                    {{ $data->application_number }}!
                                                </h6>


                                                <small class="notification-text">
                                                    {{ \Str::limit($data->message, 40, '....') }}
                                                </small>
                                            </div>
                                            <small>
                                                <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">
                                                    @php

                                                        $formattedTime = \Carbon\Carbon::parse(
                                                            $data->time,
                                                        )->diffForHumans();
                                                    @endphp
                                                    {{ $formattedTime }}

                                                    {{-- {{ \Carbon\Carbon::parse($data->time)->diffForHumans() }}
                                        {{ date('d M Y h:i A', strtotime($data->time)) }} --}}
                                                </time>
                                            </small>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                            @php session()->put('application_moderator_admin_id', $application_moderator_admin_id) ; @endphp
                            @if ($messageadminmoderatorUnreadData->count() > 0)
                                @if (\Request::segment(2) != 'applications-all')
                                    <li class="dropdown-menu-footer"><a class="dropdown-item p-1 text-center"
                                            href="{{ url('admin/applications-all') }}">Read
                                            all Messages</a></li>
                                @endif
                                @if (\Request::segment(2) == 'applications-all')
                                    <li class="dropdown-menu-footer"><a class="dropdown-item p-1 text-center"
                                            data-id="{{ implode(',', $application_moderator_admin_id) }}"
                                            id="showadminmoderatorlatestmessage" href="javascript:void(0)">Read all
                                            Message</a></li>
                                @endif
                            @else
                                <li class="dropdown-menu-footer"><a class="dropdown-item p-1 text-center"
                                        href="javascript:void(0)">No new Message</a></li>
                            @endif

                        </ul>
                    </li>


                    @if (in_array('supermoderator', $userrole) || auth('admin')->user()->getRoleNames()[0] == 'Admin')


                        @php $messageUnreadData=\App\Models\Admin::getunreadmessage(2); @endphp
                        <li class="dropdown dropdown-notification nav-item "><a class="nav-link nav-link-label"
                                href="#" data-toggle="dropdown"><i
                                    class="ficon feather icon-message-square">A&S</i><span
                                    class="badge badge-pill badge-primary badge-up">{{ $messageUnreadData->count() }}</span></a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <div class="dropdown-header m-0 p-2">


                                        <h3 class="white">{{ $messageUnreadData->count() }} New</h3><span
                                            class="white darken-2">My Messages</span>
                                    </div>
                                </li>
                                @php   $application_id=[]; @endphp
                                @foreach ($messageUnreadData as $k => $data)
                                    <li class="scrollable-container media-list">
                                        <a class="d-flex justify-content-between" href="javascript:void(0)">
                                            <div class="media d-flex align-items-start">
                                                <div class="media-left"><i
                                                        class="ficon feather icon-message-square font-medium-5 primary"></i>
                                                </div>
                                                <div class="media-body">
                                                    @php $application_id[$k]=$data->application_id; @endphp

                                                    <h6 class="primary media-heading">{{ $data->application_number }}!
                                                    </h6>


                                                    <small class="notification-text">
                                                        {{ \Str::limit($data->message, 40, '....') }}
                                                    </small>
                                                </div>
                                                <small>
                                                    <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">
                                                        @php

                                                            $formattedTime = \Carbon\Carbon::parse(
                                                                $data->time,
                                                            )->diffForHumans();
                                                        @endphp
                                                        {{ $formattedTime }}

                                                        {{-- {{ \Carbon\Carbon::parse($data->time)->diffForHumans() }}
                                        {{ date('d M Y h:i A', strtotime($data->time)) }} --}}
                                                    </time>
                                                </small>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach


                                @php session()->put('application_supermoderator_admin_id_message', $application_id) ; @endphp

                                @if ($messageUnreadData->count() > 0)
                                    @if (\Request::segment(2) != 'applications-all')
                                        <li class="dropdown-menu-footer"><a class="dropdown-item p-1 text-center"
                                                href="{{ url('admin/applications-all') }}">Read
                                                all Messages</a></li>
                                    @endif
                                    @if (\Request::segment(2) == 'applications-all')
                                        <li class="dropdown-menu-footer"><a class="dropdown-item p-1 text-center"
                                                data-id="{{ implode(',', $application_id) }}"
                                                id="showlatestmessagesupermoderatoradmin"
                                                href="javascript:void(0)">Read all
                                                Message</a></li>
                                    @endif
                                @else
                                    <li class="dropdown-menu-footer"><a class="dropdown-item p-1 text-center"
                                            href="javascript:void(0)">No new Message</a></li>

                                @endif
                            </ul>
                        </li>



                    @endif







                    <li class="nav-item d-block d-lg-block"><a class="nav-link nav-link-expand"><i
                                class="ficon feather icon-maximize"></i></a></li>
                    <li class="nav-item nav-search d-none"><a class="nav-link nav-link-search"><i
                                class="ficon feather icon-search"></i></a>
                        <div class="search-input">
                            <div class="search-input-icon"><i class="feather icon-search primary"></i></div>
                            <input class="input" type="text" placeholder="Explore Vuexy..." tabindex="-1"
                                data-search="laravel-search-list" />
                            <div class="search-input-close"><i class="feather icon-x"></i></div>
                            <ul class="search-list search-list-main"></ul>
                        </div>
                    </li>
                    <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label"
                            href="#" data-toggle="dropdown"><i class="ficon feather icon-bell"></i>
                            @if (auth('admin')->user()->unreadNotifications->count() != 0)
                                <span class="badge badge-pill badge-primary badge-up"
                                    id="notification">{{ auth('admin')->user()->unreadNotifications->count() }}</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                            <li class="dropdown-menu-header">
                                <div class="dropdown-header m-0 p-2">
                                    <span class="white darken-2"> Notifications</span>
                                </div>
                            </li>
                            <li class="scrollable-container media-list">
                                @forelse (auth('admin')->user()->unreadNotifications->take(5) as $notification)
                                    <a class="d-flex justify-content-between"
                                        href="{{ $notification->data['Link'] }}">
                                        <div class="media d-flex align-items-start">
                                            <div class="media-left d-none"><i
                                                    class="feather icon-plus-square font-medium-5 primary"></i>
                                            </div>
                                            <div class="media-body">
                                                <h6 class="primary media-heading">{{ $notification->data['Title'] }}
                                                </h6>
                                            </div>
                                            <small>
                                                {{ date('d M Y h:i A', strtotime($notification->created_at)) }}
                                            </small>
                                        </div>
                                    </a>
                                @empty
                                    <a class="d-flex justify-content-between">
                                        <div class="media d-flex align-items-start text-center no-cursor">
                                            <div class="media-left d-none"><i
                                                    class="feather icon-plus-square font-medium-5 primary"></i>
                                            </div>
                                            <div class="media-body">
                                                <h6 class=" media-heading">No new notification.</h6>
                                            </div>

                                        </div>
                                    </a>
                                @endforelse
                            </li>
                            <li class="dropdown-menu-footer"><a class="dropdown-item p-1 text-center"
                                    href="{{ route('admin.notifications') }}">View
                                    all notifications</a></li>
                        </ul>
                    </li>

                @endif




                <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link"
                        href="#" data-toggle="dropdown">
                        <div class="user-nav d-sm-flex d-none"><span class="user-name text-bold-600">
                                {{ auth('admin')->user()->first_name . ' ' . auth('admin')->user()->last_name }}</span>
                            @php $roles=auth('admin')->user()->getRoleNames()   @endphp
                            @php $rolesLength=count($roles); @endphp
                            @for ($i = 0; $i < $rolesLength; $i++)
                                <span class="user-status">{{ $roles[$i] }}</span>
                            @endfor

                        </div>
                        <span>
                            {{-- <img class="round pro-image" src="{{ auth('admin')->user()->profileImage() }}"
                                alt="avatar" height="40" width="40" /> --}}
                            <div class="avatar bg-warning font-weight-bold">
                                <div class="avatar-content">
                                    {{ auth()->user()->getInitials() }}
                                </div>
                            </div>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        @if (auth('admin')->user()->getRoleNames()[0] == 'Admin')
                            <a class="dropdown-item"
                                href="{{ route('admin.profile', auth('admin')->user()->id) }}"><i
                                    class="feather icon-user"></i> Edit Profile</a>
                        @endif
                        <a class="dropdown-item" href="{{ route('home') }}"><i class="feather icon-user"></i> Visit
                            Site</a>
                        <a class="dropdown-item d-none" href="app-email"><i class="feather icon-mail"></i> My
                            Inbox</a>
                        <a class="dropdown-item d-none" href="app-todo"><i class="feather icon-check-square"></i>
                            Task</a><a class="dropdown-item d-none" href="app-chat"><i
                                class="feather icon-message-square"></i> Chats</a>
                        <div class="dropdown-divider"></div>
                        <a onclick="event.preventDefault();
                document.getElementById('logout-form').submit();"
                            class="dropdown-item" href="auth-login"><i class="feather icon-power"></i> Logout</a>


                        @if (auth('admin')->user()->getRoleNames()[0] == 'Admin')
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        @else
                            <form id="logout-form" action="{{ route('modifier.logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        @endif
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
</nav>

{{-- Search Start Here --}}
<ul class="main-search-list-defaultlist d-none">
    <li class="d-flex align-items-center">
        <a class="pb-25" href="#">
            <h6 class="text-primary mb-0">Files</h6>
        </a>
    </li>
    <li class="auto-suggestion d-flex align-items-center cursor-pointer">
        <a class="d-flex align-items-center justify-content-between w-100" href="#">
            <div class="d-flex">
                <div class="ml-0 mr-50"><img src="{{ asset('images/icons/xls.png') }}" alt="png"
                        height="32" />
                </div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Two new item submitted</p><small class="text-muted">Marketing
                        Manager</small>
                </div>
            </div>
            <small class="search-data-size mr-50 text-muted">&apos;17kb</small>
        </a>
    </li>
    <li class="auto-suggestion d-flex align-items-center cursor-pointer">
        <a class="d-flex align-items-center justify-content-between w-100" href="#">
            <div class="d-flex">
                <div class="ml-0 mr-50"><img src="{{ asset('images/icons/jpg.png') }}" alt="png"
                        height="32" />
                </div>
                <div class="search-data">
                    <p class="search-data-title mb-0">52 JPG file Generated</p><small class="text-muted">FontEnd
                        Developer</small>
                </div>
            </div>
            <small class="search-data-size mr-50 text-muted">&apos;11kb</small>
        </a>
    </li>
    <li class="auto-suggestion d-flex align-items-center cursor-pointer">
        <a class="d-flex align-items-center justify-content-between w-100" href="#">
            <div class="d-flex">
                <div class="ml-0 mr-50"><img src="{{ asset('images/icons/pdf.png') }}" alt="png"
                        height="32" />
                </div>
                <div class="search-data">
                    <p class="search-data-title mb-0">25 PDF File Uploaded</p><small class="text-muted">Digital
                        Marketing Manager</small>
                </div>
            </div>
            <small class="search-data-size mr-50 text-muted">&apos;150kb</small>
        </a>
    </li>
    <li class="auto-suggestion d-flex align-items-center cursor-pointer">
        <a class="d-flex align-items-center justify-content-between w-100" href="#">
            <div class="d-flex">
                <div class="ml-0 mr-50"><img src="{{ asset('images/icons/doc.png') }}" alt="png"
                        height="32" />
                </div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Anna_Strong.doc</p><small class="text-muted">Web
                        Designer</small>
                </div>
            </div>
            <small class="search-data-size mr-50 text-muted">&apos;256kb</small>
        </a>
    </li>
    <li class="d-flex align-items-center">
        <a class="pb-25" href="#">
            <h6 class="text-primary mb-0">Members</h6>
        </a>
    </li>
    <li class="auto-suggestion d-flex align-items-center cursor-pointer">
        <a class="d-flex align-items-center justify-content-between py-50 w-100" href="#">
            <div class="d-flex align-items-center">
                <div class="avatar mr-50"><img src="{{ asset('images/portrait/small/avatar-s-8.jpg') }}"
                        alt="png" height="32" /></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">John Doe</p><small class="text-muted">UI
                        designer</small>
                </div>
            </div>
        </a>
    </li>
    <li class="auto-suggestion d-flex align-items-center cursor-pointer">
        <a class="d-flex align-items-center justify-content-between py-50 w-100" href="#">
            <div class="d-flex align-items-center">
                <div class="avatar mr-50"><img src="{{ asset('images/portrait/small/avatar-s-1.jpg') }}"
                        alt="png" height="32" /></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Michal Clark</p><small class="text-muted">FontEnd
                        Developer</small>
                </div>
            </div>
        </a>
    </li>
    <li class="auto-suggestion d-flex align-items-center cursor-pointer">
        <a class="d-flex align-items-center justify-content-between py-50 w-100" href="#">
            <div class="d-flex align-items-center">
                <div class="avatar mr-50"><img src="{{ asset('images/portrait/small/avatar-s-14.jpg') }}"
                        alt="png" height="32" /></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Milena Gibson</p><small class="text-muted">Digital
                        Marketing
                        Manager</small>
                </div>
            </div>
        </a>
    </li>
    <li class="auto-suggestion d-flex align-items-center cursor-pointer">
        <a class="d-flex align-items-center justify-content-between py-50 w-100" href="#">
            <div class="d-flex align-items-center">
                <div class="avatar mr-50"><img src="{{ asset('images/portrait/small/avatar-s-6.jpg') }}"
                        alt="png" height="32" /></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Anna Strong</p><small class="text-muted">Web
                        Designer</small>
                </div>
            </div>
        </a>
    </li>
</ul>
<ul class="main-search-list-defaultlist-other-list d-none">
    <li class="auto-suggestion d-flex align-items-center justify-content-between cursor-pointer">
        <a class="d-flex align-items-center justify-content-between w-100 py-50">
            <div class="d-flex justify-content-start"><span class="mr-75 feather icon-alert-circle"></span><span>No
                    results found.</span></div>
        </a>
    </li>
</ul>
{{-- Search Ends --}}
<!-- END: Header-->

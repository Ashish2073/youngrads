<!-- START X-NAVIGATION -->
<ul class="x-navigation">
    <li class="xn-logo text-center">
        <a href="{{ route('admin.home') }}">{{ config('app.name') }}</a>
        <a href="#" class="x-navigation-control"></a>
    </li>

    <li class="xn-profile">
        <a href="#" class="profile-mini">
            <img class="" src="{{ auth('admin')->user()->profileImage() }}"
                 alt="{{ auth('admin')->user()->first_name }}"/>
        </a>
        <div class="profile text-center">
            <div class="profile-image">
                <img title="Click/Tap here to browse picture" class="pro-image"
                     src="{{ auth('admin')->user()->profileImage() }}" alt="{{ auth('admin')->user()->first_name }}"/>
            </div>
            <div class="profile-data">
                <div class="profile-data-name">{{ auth('admin')->user()->first_name }}</div>

                <div class="profile-data-title">
                    {{-- {{ (auth()->user()->first_name) }} --}}
                </div>

            </div>
            <div class="profile-controls">
                <a
                        href=''
                        type='button'
                        data-toggle="modal" data-target="#dynamic-modal"
                        data-url="{{ route('admin.profile', auth('admin')->user()->id) }}"
                        class="profile-control-left common-edit-profile">
                    <span class="fa fa-info"></span>

                </a>
                <form class="form-inline"
                      action="{{route('admin.profilePic')}}"
                      enctype="multipart/form-data" method="post" id="profile-pic">
                    @csrf
                    <input type="file" name="profile" class="hide">
                    <button type="submit" id="profile-submit-btn" class="btn btn-info btn-sm text-center">Change
                        Picture
                    </button>
                </form>

                <a href="#" class="profile-control-right mb-control" data-box="#mb-signout"><span
                            class="fa fa-sign-out"></span></a>
                <a href="#" class="profile-control-right mb-control" data-box="#mb-signout"><span
                            class="fa fa-sign-out"></span></a>
            </div>
        </div>
    </li>
    <li class="xn-title hide">

    </li>
    <li class="xn-title hide">Navigation</li>

    @include('dashboard.inc.menu')

</ul>
<!-- END X-NAVIGATION -->

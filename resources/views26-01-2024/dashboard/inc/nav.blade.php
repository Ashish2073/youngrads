<ul class="x-navigation x-navigation-horizontal x-navigation-panel">
    <!-- TOGGLE NAVIGATION -->
    <li class="xn-icon-button ">
        <a href="#" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
    </li>
    <!-- END TOGGLE NAVIGATION -->
    <!-- SEARCH -->
    <li class="xn-search hide">
        <form role="form">
            <input type="text" name="search" placeholder="Search..."/>
        </form>
    </li>
    <!-- END SEARCH -->


    <!-- POWER OFF -->
    <li class="xn-icon-button pull-right last">
        <a href="#"> {{ auth('admin')->user()->name }} <span class="fa fa-cog"></span></a>
        <ul class="xn-drop-left animated zoomIn">
            {{-- <li><a href="{{ route('admin.setting') }}"><span class="fa fa-cog"></span> Setting</a></li> --}}
            <li><a href="#" class="mb-control" data-box="#mb-signout"><span class="fa fa-sign-out"></span> Log Out</a>
            </li>
        </ul>
    </li>


    <li class="xn-icon-button pull-right">
        <a href="#"><span class="fa fa-comments"></span></a>
        <div class="informer informer-danger">4</div>
    </li>

    <li class="xn-icon-button pull-right">
        <a href="#"><span class="fa fa-tasks"></span></a>
        <div class="informer informer-warning">3</div>

    </li>
    <!-- END POWER OFF -->
</ul>


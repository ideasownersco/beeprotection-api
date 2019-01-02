<header id="topnav">
    <div class="topbar-main">
        <div class="container-fluid">

            <!-- Logo container-->
            <div class="logo">
                <a href="{{ route('admin.home') }}" class="logo">
                    {{--<img src="/images/logo_dark.png" alt="" height="20" class="logo-lg">--}}
                    <span class="logo-lg"> {{ config('app.name') }}</span>
                    <img src="/images/logo-sm.png" alt="" height="24" class="logo-sm">
                </a>
            </div>
            <!-- End Logo container-->

            <div class="menu-extras topbar-custom">

                <ul class="list-inline float-right mb-0">

                    <li class="menu-item list-inline-item">
                        <!-- Mobile menu toggle-->
                        <a class="navbar-toggle nav-link">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                        <!-- End mobile menu toggle-->
                    </li>
                    {{--<li class="list-inline-item dropdown notification-list">--}}
                        {{--<a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown" href="#" role="button"--}}
                           {{--aria-haspopup="false" aria-expanded="false">--}}
                            {{--<i class="dripicons-bell noti-icon"></i>--}}
                            {{--<span class="badge badge-pink noti-icon-badge">4</span>--}}
                        {{--</a>--}}
                        {{--<div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-lg" aria-labelledby="Preview">--}}
                            {{--<!-- item-->--}}
                            {{--<div class="dropdown-item noti-title">--}}
                                {{--<h5><span class="badge badge-danger float-right">5</span>Notification</h5>--}}
                            {{--</div>--}}

                            {{--<!-- item-->--}}
                            {{--<a href="javascript:void(0);" class="dropdown-item notify-item">--}}
                                {{--<div class="notify-icon bg-success"><i class="icon-bubble"></i></div>--}}
                                {{--<p class="notify-details">Robert S. Taylor commented on Admin<small class="text-muted">1 min ago</small></p>--}}
                            {{--</a>--}}

                            {{--<!-- All-->--}}
                            {{--<a href="javascript:void(0);" class="dropdown-item notify-item notify-all">--}}
                                {{--View All--}}
                            {{--</a>--}}

                        {{--</div>--}}
                    {{--</li>--}}

                    <li class="list-inline-item dropdown notification-list">
                        <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            <img src="/images/logo-sm.png" alt="user" class="img img-responsive ">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">
                            <!-- item-->
                            <a href="{{ route('logout') }}" class="dropdown-item notify-item">
                                <i class="md md-settings-power"></i> <span>Logout</span>
                            </a>
                        </div>
                    </li>

                </ul>
            </div>
            <!-- end menu-extras -->

            <div class="clearfix"></div>

        </div> <!-- end container -->
    </div>
    <!-- end topbar-main -->

    @include('admin.partials.navbar')
</header>
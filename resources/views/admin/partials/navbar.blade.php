<div class="navbar-custom">
    <div class="container-fluid">
        <div id="navigation">
            <ul class="navigation-menu">
                <li class="{{ !request()->segment(2) ? 'has-submenu active' : ''}}">
                    <a href="{{ route('admin.home') }}"><i class="md md-dashboard"></i>Dashboard</a>
                </li>
                <li class="{{ request()->segment(2) === 'categories' ? 'has-submenu active' : ''}}">
                    <a href="{{ route('admin.categories.index') }}" class=""><i class="md md-color-lens"></i>Categories</a>
                </li>
                <li class="{{ request()->segment(2) === 'packages' ? 'has-submenu active' : ''}}">
                    <a href="{{ route('admin.packages.index') }}"><i class="md md-color-lens"></i>Packages</a>
                </li>
                {{--<li class="{{ request()->segment(2) === 'services' ? 'has-submenu active' : ''}}">--}}
                    {{--<a href="{{ route('admin.services.index') }}"><i class="md md-color-lens"></i>Services</a>--}}
                {{--</li>--}}
                <li class="{{ request()->segment(2) === 'drivers' ? 'has-submenu active' : ''}}">
                    <a href="{{ route('admin.drivers.index') }}"><i class="md md-color-lens"></i>Drivers</a>
                </li>
                <li class="{{ request()->segment(2) === 'users' ? 'has-submenu active' : ''}}">
                    <a href="{{ route('admin.users.index') }}"><i class="md md-color-lens"></i>Users</a>
                </li>
                <li class="{{ request()->segment(2) === 'orders' ? 'has-submenu active' : ''}}">
                    <a href="{{ route('admin.orders.index') }}"><i class="md md-color-lens"></i>Orders</a>
                </li>
                <li class="{{ request()->segment(2) === 'locations' ? 'has-submenu active' : ''}}">
                    <a href="{{ route('admin.revenue.index') }}"><i class="md md-color-lens"></i>Revenue</a>
                </li>
                <li class="{{ request()->segment(2) === 'timings' ? 'has-submenu active' : ''}}">
                    <a href="{{ route('admin.timings.index') }}"><i class="md md-color-lens"></i>Timings</a>
                </li>
                <li class="{{ request()->segment(2) === 'locations' ? 'has-submenu active' : ''}}">
                    <a href="{{ route('admin.areas.index') }}"><i class="md md-color-lens"></i>Areas</a>
                </li>
                <li class="{{ request()->segment(2) === 'notifications' ? 'has-submenu active' : ''}}">
                    <a href="{{ route('admin.notifications.index') }}"><i class="md md-color-lens"></i>Push Notifications</a>
                </li>
            </ul>
        </div>
    </div>
</div>
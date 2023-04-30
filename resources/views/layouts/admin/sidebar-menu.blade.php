<ul class="sidebar-menu" data-widget="tree">
    <li class="header">MAIN NAVIGATION</li>
    <li class="{{ \App\Utils::checkRoute(['dashboard::index', 'admin::index']) ? 'active': '' }}">
        <a href="/admin">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
    </li>
    <li class="{{ \App\Utils::checkRouteAdmin('config') ? 'active': '' }}">
        <a href="{{ route('admin::config.index') }}">
            <i class="fa fa-gears"></i> <span>{{__a('config')}}</span>
        </a>
    </li>
    <li class="{{ \App\Utils::checkRouteAdmin('users') ? 'active': '' }}">
        <a href="{{ route('admin::users.index') }}">
            <i class="fa fa-user"></i> <span>{{__a('users')}}</span>
        </a>
    </li>
</ul>

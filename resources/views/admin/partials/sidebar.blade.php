<!-- <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">FundHive Admin</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">

                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.users') }}"
                       class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.campaigns') }}"
                       class="nav-link {{ request()->routeIs('admin.campaigns*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>Campaigns</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.fraud') }}"
                       class="nav-link {{ request()->routeIs('admin.fraud*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shield-alt"></i>
                        <p>Fraud Detection</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside> -->

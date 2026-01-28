<nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom">
    <!-- Left navbar -->
    <ul class="navbar-nav">
        <li class="nav-item me-2">
            <a class="nav-link" data-widget="pushmenu" href="#">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand">
                <span class="brand-text font-weight-light">FundHive Admin</span>
            </a>
        </li>
    </ul>

    <!-- Center navbar - Main navigation -->
    <ul class="navbar-nav d-none d-lg-flex flex-row gap-0">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active border-bottom border-primary' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt mr-1"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.users') }}"
               class="nav-link {{ request()->routeIs('admin.users*') ? 'active border-bottom border-primary' : '' }}">
                <i class="nav-icon fas fa-users mr-1"></i>
                <span>Users</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.campaigns') }}"
               class="nav-link {{ request()->routeIs('admin.campaigns*') ? 'active border-bottom border-primary' : '' }}">
                <i class="nav-icon fas fa-bullhorn mr-1"></i>
                <span>Campaigns</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.fraud') }}"
               class="nav-link {{ request()->routeIs('admin.fraud*') ? 'active border-bottom border-primary' : '' }}">
                <i class="nav-icon fas fa-shield-alt mr-1"></i>
                <span>Fraud Detection</span>
            </a>
        </li>
    </ul>

    <!-- Right navbar -->
    <ul class="navbar-nav ml-auto d-flex flex-row align-items-center gap-2">
        <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link" title="View Site">
                <i class="fas fa-globe"></i>
            </a>
        </li>
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-link nav-link p-0" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </li>
    </ul>
</nav>

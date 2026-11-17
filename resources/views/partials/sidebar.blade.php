<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion {{ request()->query('sidenav') === 'light' ? 'sb-sidenav-light' : 'sb-sidenav-dark' }}" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                @unless (auth()->user()->isAdmin())
                <a class="nav-link" href="{{ route('dashboard') }}#profile">
                    <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                    Profile
                </a>
                @endunless
                @if (auth()->user()->isAdmin())
                <div class="sb-sidenav-menu-heading">Administration</div>
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-users-cog"></i></div>
                    User Management
                </a>
                @endif
                @if (auth()->user()->isAdmin())
                <div class="sb-sidenav-menu-heading">Interface</div>
                <a class="nav-link {{ request()->query('sidenav') === 'light' ? '' : 'collapsed' }}" href="#collapseLayouts" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="{{ request()->query('sidenav') === 'light' ? 'true' : 'false' }}" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Layouts
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ request()->query('sidenav') === 'light' ? 'show' : '' }}" id="collapseLayouts" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->query('sidenav') !== 'light' && request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Default Sidebar</a>
                        <a class="nav-link {{ request()->query('sidenav') === 'light' ? 'active' : '' }}" href="{{ route('dashboard', ['sidenav' => 'light']) }}">Light Sidebar</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#collapsePages" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                    <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                    Pages
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePages" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        @if (auth()->user()->isAdmin())
                            <a class="nav-link" href="{{ route('admin.users.index') }}">User Management</a>
                        @endif
                        <a class="nav-link" href="{{ route('charts') }}">Charts</a>
                        <a class="nav-link" href="{{ route('tables') }}">Tables</a>
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </nav>
                </div>
                <div class="sb-sidenav-menu-heading">Addons</div>
                <a class="nav-link {{ request()->routeIs('charts') ? 'active' : '' }}" href="{{ route('charts') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Charts
                </a>
                <a class="nav-link {{ request()->routeIs('tables') ? 'active' : '' }}" href="{{ route('tables') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Tables
                </a>
                @endif
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            {{ auth()->user()->full_name }}
        </div>
    </nav>
</div>

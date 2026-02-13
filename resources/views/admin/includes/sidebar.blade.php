<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('assets/admin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Sline</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                @can('map-view')
                    <li class="nav-item">
                        <a href="{{ route('map') }}" class="nav-link {{ request()->routeIs('map') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>{{ __('messages.live_map') }}</p>
                        </a>
                    </li>
                @endcan

                @canany(['driverNotified-table', 'driverNotified-add', 'driverNotified-edit', 'driverNotified-delete'])
                    <li class="nav-item">
                        <a href="{{ route('admin.driver-notified.index') }}" class="nav-link {{ request()->routeIs('admin.driver-notified*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bell"></i>
                            <p>{{ __('messages.driver_notified') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(['city-table', 'city-add', 'city-edit', 'city-delete'])
                    <li class="nav-item">
                        <a href="{{ route('cities.index') }}" class="nav-link {{ request()->routeIs('cities*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-city"></i>
                            <p>{{ __('messages.cities') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(['user-table', 'user-add', 'user-edit', 'user-delete'])
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>{{ __('messages.users') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(['driver-table', 'driver-add', 'driver-edit', 'driver-delete'])
                    <li class="nav-item">
                        <a href="{{ route('drivers.index') }}" class="nav-link {{ request()->routeIs('drivers*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-car"></i>
                            <p>{{ __('messages.drivers') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(['wallet-table', 'wallet-add', 'wallet-edit', 'wallet-delete'])
                    <li class="nav-item">
                        <a href="{{ route('wallets.index') }}" class="nav-link {{ request()->routeIs('wallets*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-wallet"></i>
                            <p>{{ __('messages.wallets') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(['orderTodaytable', 'orderToday-add', 'orderToday-edit', 'orderToday-delete'])
                    <li class="nav-item">
                        <a href="{{ route('orders.today') }}" class="nav-link {{ request()->routeIs('orders.today') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-day"></i>
                            <p>{{ __('messages.Orders Todays') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(['order-table', 'order-add', 'order-edit', 'order-delete'])
                    <li class="nav-item">
                        <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-receipt"></i>
                            <p>{{ __('messages.Orders') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(['notification-table', 'notification-add', 'notification-edit', 'notification-delete'])
                    <li class="nav-item">
                        <a href="{{ route('notifications.create') }}" class="nav-link {{ request()->routeIs('notifications*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-paper-plane"></i>
                            <p>{{ __('messages.notifications') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(['page-table', 'page-add', 'page-edit', 'page-delete'])
                    <li class="nav-item">
                        <a href="{{ route('pages.index') }}" class="nav-link {{ request()->routeIs('pages*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>{{ __('messages.Pages') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(['setting-table', 'setting-add', 'setting-edit', 'setting-delete'])
                    <li class="nav-item">
                        <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>{{ __('messages.settings') }}</p>
                        </a>
                    </li>
                @endcanany

                <li class="nav-item">
                    <a href="{{ route('admin.login.edit', auth()->user()->id) }}" class="nav-link {{ request()->routeIs('admin.login.edit') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-circle"></i>
                        <p>{{ __('messages.Admin_account') }}</p>
                    </a>
                </li>

                @canany(['role-table', 'role-add', 'role-edit', 'role-delete'])
                    <li class="nav-item">
                        <a href="{{ route('admin.role.index') }}" class="nav-link {{ request()->routeIs('admin.role*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p>{{ __('messages.Roles') }}</p>
                        </a>
                    </li>
                @endcanany

                @canany(['employee-table', 'employee-add', 'employee-edit', 'employee-delete'])
                    <li class="nav-item">
                        <a href="{{ route('admin.employee.index') }}" class="nav-link {{ request()->routeIs('admin.employee*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-id-badge"></i>
                            <p>{{ __('messages.Employee') }}</p>
                        </a>
                    </li>
                @endcanany

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('assets/admin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Ayla</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
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
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                @if (
                $user->can('user-table') ||
                $user->can('user-add') ||
                $user->can('user-edit') ||
                $user->can('user-delete'))
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> {{__('messages.users')}} </p>
                    </a>
                </li>
                @endif

              @if (
                $user->can('driver-table') ||
                $user->can('driver-add') ||
                $user->can('driver-edit') ||
                $user->can('driver-delete'))
                <li class="nav-item">
                    <a href="{{ route('drivers.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> {{__('messages.drivers')}} </p>
                    </a>
                </li>
                @endif

                   @if (
                    $user->can('wallet-table') ||
                        $user->can('wallet-add') ||
                        $user->can('wallet-edit') ||
                        $user->can('wallet-delete'))
                    <li class="nav-item">
                        <a href="{{ route('wallets.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> {{__('messages.wallets')}}  </p>
                        </a>
                    </li>
                @endif


                @if (
                $user->can('orderTodaytable') ||
                $user->can('orderToday-add') ||
                $user->can('orderToday-edit') ||
                $user->can('orderToday-delete'))
                <li class="nav-item">
                    <a href="{{ route('orders.today') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> {{__('messages.Orders Todays')}} </p>
                    </a>
                </li>
                @endif
              
              @if (
                $user->can('order-table') ||
                $user->can('order-add') ||
                $user->can('order-edit') ||
                $user->can('order-delete'))
                <li class="nav-item">
                    <a href="{{ route('orders.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> {{__('messages.Orders')}} </p>
                    </a>
                </li>
                @endif
         

                @if (
                $user->can('notification-table') ||
                $user->can('notification-add') ||
                $user->can('notification-edit') ||
                $user->can('notification-delete'))
                <li class="nav-item">
                    <a href="{{ route('notifications.create') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p> {{__('messages.notifications')}} </p>
                    </a>
                </li>
                @endif


                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                            {{ __('messages.reports') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    {{-- <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('inventory_report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.inventory_report_with_costs') }} </p>
                            </a>
                        </li>
                       
                    </ul> --}}
                </li>

                @if (
                    $user->can('page-table') ||
                        $user->can('page-add') ||
                        $user->can('page-edit') ||
                        $user->can('page-delete'))
                    <li class="nav-item">
                        <a href="{{ route('pages.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>{{__('messages.Pages')}} </p>
                        </a>
                    </li>
                    @endif
          

                <li class="nav-item">
                    <a href="{{ route('settings.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{__('messages.settings')}} </p>
                    </a>
                </li>



                <li class="nav-item">
                    <a href="{{ route('admin.login.edit',auth()->user()->id) }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{__('messages.Admin_account')}} </p>
                    </a>
                </li>

                @if ($user->can('role-table') || $user->can('role-add') || $user->can('role-edit') ||
                $user->can('role-delete'))
                <li class="nav-item">
                    <a href="{{ route('admin.role.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <span>{{__('messages.Roles')}} </span>
                    </a>
                </li>
                @endif

                @if (
                $user->can('employee-table') ||
                $user->can('employee-add') ||
                $user->can('employee-edit') ||
                $user->can('employee-delete'))
                <li class="nav-item">
                    <a href="{{ route('admin.employee.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <span> {{__('messages.Employee')}} </span>
                    </a>
                </li>
                @endif

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

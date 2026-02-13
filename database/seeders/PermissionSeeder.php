<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permissions_admin = [


            'role-table',
            'role-add',
            'role-edit',
            'role-delete',

            'employee-table',
            'employee-add',
            'employee-edit',
            'employee-delete',

            'user-table',
            'user-add',
            'user-edit',
            'user-delete',


            'order-table',
            'order-add',
            'order-edit',
            'order-delete',


            'notification-table',
            'notification-add',
            'notification-edit',
            'notification-delete',

            'setting-table',
            'setting-add',
            'setting-edit',
            'setting-delete',

            'wallet-table',
            'wallet-add',
            'wallet-edit',
            'wallet-delete',

            'driver-table',
            'driver-add',
            'driver-edit',
            'driver-delete',

            'city-table',
            'city-add',
            'city-edit',
            'city-delete',

            'map-view',

            'page-table',
            'page-add',
            'page-edit',
            'page-delete',

            'driverNotified-table',
            'driverNotified-add',
            'driverNotified-edit',
            'driverNotified-delete',

            'dashboard-view',

        ];

         foreach ($permissions_admin as $permission_ad) {
            Permission::firstOrCreate(['name' => $permission_ad, 'guard_name' => 'admin']);
        }
    }
}

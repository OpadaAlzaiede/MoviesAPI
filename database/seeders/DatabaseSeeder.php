<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => Config::get('constants.USER_ROLE'), 'guard_name' => 'api']);
        $adminRole = Role::create(['name' => Config::get('constants.ADMIN_ROLE'), 'guard_name' => 'api']);


        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.admin',
            'password' => Hash::make('adminadmin')
        ]);

        $admin->assignRole($adminRole);
    }
}

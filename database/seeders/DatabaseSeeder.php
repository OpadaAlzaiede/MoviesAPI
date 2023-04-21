<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
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
        Role::create(['name' => Config::get('constants.ADMIN_ROLE'), 'guard_name' => 'api']);
    }
}

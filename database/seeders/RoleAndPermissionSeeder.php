<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'edit roles']);
        Permission::create(['name' => 'edit categories']);
        Permission::create(['name' => 'edit labels']);
        Permission::create(['name' => 'create tickets']);
        Permission::create(['name' => 'look tickets']);
        Permission::create(['name' => 'edit tickets']);
        Permission::create(['name' => 'look ticket logs']);

        Role::create(['name' => 'admin'])
            ->givePermissionTo(Permission::all());

        Role::create(['name' => 'agent'])
            ->givePermissionTo(['edit tickets', 'look tickets']);

        Role::create(['name' => 'customer'])
            ->givePermissionTo(['create tickets', 'look tickets']);
    }
}

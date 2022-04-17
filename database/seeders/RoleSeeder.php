<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roladmin = Role::create(['name' => 'Admin']);
        $roluser = Role::create(['name' => 'User']);
        $rolclient = Role::create(['name' => 'Client']);

        $roladmin->givePermissionTo(Permission::all()->pluck('id'));


        //
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Role;
class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create permissions
        /*Permission::updateOrCreate(['name' => 'create candidate']);//C
        Permission::updateOrCreate(['name' => 'get candidate']);//R
        Permission::updateOrCreate(['name' => 'edit candidate']);//U
        Permission::updateOrCreate(['name' => 'delete candidate']);//D
        Permission::updateOrCreate(['name' => 'list candidates']);*/

        $role_manager = Role::firstOrCreate(['name' => 'manager']);
        /*$role_manager->givePermissionTo('create candidate')
            ->givePermissionTo('get candidate')
            ->givePermissionTo('edit candidate')
            ->givePermissionTo('delete candidate')
            ->givePermissionTo('list candidates');*/
        $role_agent = Role::firstOrCreate(['name' => 'agent']);
        /*$role_agent->givePermissionTo('get candidate');*/

    }
}

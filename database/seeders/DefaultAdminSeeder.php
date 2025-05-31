<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DefaultAdminSeeder extends Seeder
{
    public $permissions = [
        'view-server', 'add-server', 'edit-server', 'delete-server',
        'view-configs', 'add-configs', 'edit-configs', 'delete-configs',
        'view-user', 'add-user', 'edit-user', 'delete-user',
        'view-news', 'add-news', 'edit-news', 'delete-news',
        'view-notifications', 'add-notifications', 'edit-notifications', 'delete-notifications',
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::findOrCreate('admin', 'web');
        $role->updateQuietly(['title' => 'مدیر']);

        foreach ($this->permissions as $item) {
            if (! Permission::where('name', $item)->exists()) {
                $attrs = explode('-', $item, 2);
                $permission = Permission::create(['name' => $item, 'title' => __("permissions.attributes.{$attrs[0]}", ['name' => __("permissions.{$attrs[1]}")])]);
                $role->givePermissionTo($permission);
                // DB::table('model_has_permissions')->insert(
                //     [
                //         'permission_id'=>$permission->id,
                //         'model_type'=>'App\\Models\\Role',
                //         'model_id'=>1
                //     ]
                // );
            }
        }
        $admin = User::findOrCreate('admin@gmail.com', [
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('@12345Bb'),
        ]);
        $admin->assignRole('admin');

        $user = Role::findOrCreate('user', 'web');
        $user->updateQuietly(['title' => 'مشتری']);
    }
}

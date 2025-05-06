<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DefaultAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::findOrCreate('admin@gmail.com', [
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('@12345Bb'),
        ]);

        $permissions = [
            'view-server', 'add-server', 'edit-server', 'delete-server',
            'view-user', 'add-user', 'edit-user', 'delete-user',
            'view-news', 'add-news', 'edit-news', 'delete-news',
            'view-notifications', 'add-notifications', 'edit-notifications', 'delete-notifications',
        ];

        $adminRole = Role::findOrCreate('admin', 'web');
        $adminRole->updateQuietly(['title' => 'مدیر']);
        foreach ($permissions as $item) {
            if (! Permission::where('name', $item)->exists()) {
                $attrs = explode('-', $item, 2);
                Permission::create(['name' => $item, 'title' => __("permissions.attributes.{$attrs[0]}", ['name' => __("permissions.{$attrs[1]}")])]);
                $adminRole->givePermissionTo($item);
            }
        }
        $admin->assignRole('admin');

        $user = Role::findOrCreate('user', 'web');
        $user->updateQuietly(['title' => 'مشتری']);
    }
}

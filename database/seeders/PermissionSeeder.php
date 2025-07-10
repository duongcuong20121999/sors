<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Danh sách quyền và nhóm quyền
         $permissions = [
            ['name' => 'dashboard'],
            ['name' => 'dashboard.show'],
            ['name' => 'dashboard.handle.citizen-service'],
            ['name' => 'dashboard.update.citizen-service'],
            ['name' => 'dashboard.destroy.process'],
            ['name' => 'dashboard.citizen-service.update-status'],

            ['name' => 'service-configurations.index'],
            ['name' => 'service-configurations.create'],
            ['name' => 'service-configurations.store'],
            ['name' => 'service-configurations.edit'],
            ['name' => 'service-configurations.update'],

            ['name' => 'service-configurations.index'],
            ['name' => 'service-configurations.create'],
            ['name' => 'service-configurations.store'],
            ['name' => 'service-configurations.edit'],
            ['name' => 'service-configurations.update'],
            ['name' => 'service-configurations.destroy'],

            ['name' => 'posts.index'],
            ['name' => 'posts.create'],
            ['name' => 'posts.store'],
            ['name' => 'posts.edit'],
            ['name' => 'posts.update'],
            ['name' => 'posts.destroy'],

            ['name' => 'accounts-manager.index'],
            ['name' => 'accounts-manager.create'],
            ['name' => 'accounts-manager.store'],
            ['name' => 'accounts-manager.edit'],
            ['name' => 'accounts-manager.update'],
            ['name' => 'accounts-manager.destroy'],

            ['name' => 'user-roles-manager.index'],
            ['name' => 'user-roles-manager.create'],
            ['name' => 'user-roles-manager.store'],
            ['name' => 'user-roles-manager.edit'],
            ['name' => 'user-roles-manager.update'],
            ['name' => 'user-roles-manager.destroy'],

            ['name' => 'request-history.index'],

            ['name' => 'dashboard.show'],
            ['name' => 'dashboard.handle.citizen-service'],
            ['name' => 'dashboard.update.citizen-service'],
            ['name' => 'dashboard.destroy.process'],
            ['name' => 'dashboard.citizen-service.update-status'],
            ['name' => 'request-history.index'],

            

            ['name' => 'posts.index'],
            ['name' => 'posts.edit'],
            ['name' => 'posts.update'],

            ['name' => 'user-logs.index'],
      
            ['name' => 'posts.index'],
            ['name' => 'posts.store'],
            ['name' => 'posts.create'],
            ['name' => 'posts.edit'],

            ['name' => 'settings.index'],
            ['name' => 'settings.store'],
            ['name' => 'service-kiosk-manager.index'],
            ['name' => 'service-kiosk-manager.show'],
            ['name' => 'service-kiosk-manager.get-number']



        ];

 
        foreach ($permissions as $perm) {
            // Kiểm tra có tồn tại cùng name và name_role không
            $exists = Permission::where('name', $perm['name'])
                ->where('guard_name', 'web')
                ->exists();
        
            // Nếu không tồn tại, ta tiến hành tạo mới
            if (!$exists) {
                Permission::create([
                    'name' => $perm['name'],
                    'guard_name' => 'web'
                ]);
            }
        
        }
    }
}

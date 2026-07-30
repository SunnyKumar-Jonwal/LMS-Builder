<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public const PERMISSIONS = [
        'manage-users',
        'manage-roles',
        'manage-courses-own',
        'manage-courses-any',
        'manage-sections',
        'manage-content',
        'manage-assignments',
        'grade-submissions',
        'manage-attendance',
        'view-audit-logs',
        'manage-system-settings',
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (self::PERMISSIONS as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $roles = [
            'super_admin' => self::PERMISSIONS,
            'professor' => [
                'manage-courses-own',
                'manage-sections',
                'manage-content',
                'manage-assignments',
                'grade-submissions',
                'manage-attendance',
            ],
            'teacher' => [
                'manage-sections',
                'manage-content',
                'manage-assignments',
                'grade-submissions',
                'manage-attendance',
            ],
            'student' => [],
        ];

        foreach ($roles as $roleName => $permissions) {
            Role::findOrCreate($roleName, 'web')->syncPermissions($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}

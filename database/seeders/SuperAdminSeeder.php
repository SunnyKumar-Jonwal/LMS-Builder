<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('lms.super_admin.email');
        $password = config('lms.super_admin.password');

        if (! $email || ! $password) {
            $this->command?->warn('SUPER_ADMIN_EMAIL and SUPER_ADMIN_PASSWORD are required to seed a super admin.');

            return;
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            ['name' => 'Super Admin', 'password' => Hash::make($password), 'email_verified_at' => now()],
        );

        $hadRole = $user->hasRole('super_admin');
        $user->assignRole('super_admin');

        if (! $hadRole) {
            AuditLog::create([
                'actor_id' => $user->id,
                'action' => 'super_admin_seeded',
                'auditable_type' => User::class,
                'auditable_id' => $user->id,
                'old_values' => ['roles' => []],
                'new_values' => ['roles' => ['super_admin']],
                'ip_address' => null,
            ]);
        }
    }
}

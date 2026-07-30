<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
});

function userWithRole(string $role): User
{
    $user = User::factory()->create(['email_verified_at' => now()]);
    $user->assignRole($role);

    return $user;
}

it('prevents students from accessing admin and professor routes', function (): void {
    $student = userWithRole('student');

    $this->actingAs($student)->get('/admin/dashboard')->assertForbidden();
    $this->actingAs($student)->get('/professor/dashboard')->assertForbidden();
});

it('prevents teachers from accessing professor-only routes', function (): void {
    $teacher = userWithRole('teacher');

    $this->actingAs($teacher)->get('/professor/dashboard')->assertForbidden();
});

it('allows each role to access its own dashboard', function (string $role, string $uri): void {
    $this->actingAs(userWithRole($role))->get($uri)->assertOk();
})->with([
    ['super_admin', '/admin/dashboard'],
    ['professor', '/professor/dashboard'],
    ['teacher', '/teacher/dashboard'],
    ['student', '/student/dashboard'],
]);

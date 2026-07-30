<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class AppLayout extends Component
{
    public function navigation(): array
    {
        $user = auth()->user();

        return array_values(array_filter([
            ['label' => 'Dashboard', 'url' => route('dashboard'), 'show' => true],
            ['label' => 'Admin', 'url' => route('admin.dashboard'), 'show' => $user?->hasRole('super_admin')],
            ['label' => 'Professor', 'url' => route('professor.dashboard'), 'show' => $user?->hasRole('professor')],
            ['label' => 'Teacher', 'url' => route('teacher.dashboard'), 'show' => $user?->hasRole('teacher')],
            ['label' => 'Student', 'url' => route('student.dashboard'), 'show' => $user?->hasRole('student')],
            ['label' => 'Users', 'url' => '/admin/users', 'show' => $user?->can('manage-users')],
            ['label' => 'Roles', 'url' => '/admin/roles', 'show' => $user?->can('manage-roles')],
            ['label' => 'Audit Logs', 'url' => '/admin/audit-logs', 'show' => $user?->can('view-audit-logs')],
            ['label' => 'System Settings', 'url' => '/admin/system-settings', 'show' => $user?->can('manage-system-settings')],
        ], fn (array $item): bool => (bool) $item['show']));
    }

    public function render(): View
    {
        return view('livewire.app-layout', ['items' => $this->navigation()]);
    }
}

<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\User;

class AssignmentPolicy
{
    public function create(User $user, int $sectionId): bool
    {
        return $user->hasRole('teacher')
            && $user->sectionsTeaching()->whereKey($sectionId)->exists();
    }

    public function submit(User $user, Assignment $assignment): bool
    {
        return $user->hasRole('student')
            && $assignment->acceptsSubmission()
            && $assignment->section->enrollments()
                ->where('student_id', $user->id)
                ->where('status', Enrollment::STATUS_ACTIVE)
                ->exists();
    }
}

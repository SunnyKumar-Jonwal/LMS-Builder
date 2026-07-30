<?php

namespace App\Policies;

use App\Models\AssignmentSubmission;
use App\Models\Enrollment;
use App\Models\User;

class AssignmentSubmissionPolicy
{
    public function grade(User $user, AssignmentSubmission $submission): bool
    {
        $section = $submission->assignment->section;

        return $user->hasRole('teacher')
            && $section->teacher_id === $user->id
            && $section->enrollments()
                ->where('student_id', $submission->student_id)
                ->where('status', Enrollment::STATUS_ACTIVE)
                ->exists();
    }
}

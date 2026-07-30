<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\User;

class QuizPolicy
{
    public function create(User $user, int $sectionId): bool
    {
        return $user->hasRole('teacher')
            && $user->sectionsTeaching()->whereKey($sectionId)->exists();
    }

    public function attempt(User $user, Quiz $quiz): bool
    {
        return $user->hasRole('student')
            && $quiz->section->enrollments()
                ->where('student_id', $user->id)
                ->where('status', Enrollment::STATUS_ACTIVE)
                ->exists();
    }
}

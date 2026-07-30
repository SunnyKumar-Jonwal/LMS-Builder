<?php

namespace App\Policies;

use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\User;

class AttendanceRecordPolicy
{
    public function mark(User $user, CourseSection $section): bool
    {
        return $user->hasRole('teacher') && $section->teacher_id === $user->id;
    }

    public function viewSection(User $user, CourseSection $section): bool
    {
        if ($user->hasRole('professor') && $section->course->professor_id === $user->id) {
            return true;
        }

        if ($user->hasRole('teacher') && $section->teacher_id === $user->id) {
            return true;
        }

        return $user->hasRole('student')
            && $section->enrollments()
                ->where('student_id', $user->id)
                ->where('status', Enrollment::STATUS_ACTIVE)
                ->exists();
    }
}

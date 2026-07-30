<?php

namespace App\Policies;

use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\User;

class CourseSectionPolicy
{
    public function manageLessons(User $user, CourseSection $section): bool
    {
        return $user->hasRole('teacher') && $section->teacher_id === $user->id;
    }

    public function manageMaterials(User $user, CourseSection $section): bool
    {
        return $this->manageLessons($user, $section);
    }

    public function viewLessons(User $user, CourseSection $section): bool
    {
        if ($user->hasRole('teacher') && $section->teacher_id === $user->id) {
            return true;
        }

        return $user->hasRole('student')
            && $section->enrollments()
                ->where('student_id', $user->id)
                ->where('status', Enrollment::STATUS_ACTIVE)
                ->exists();
    }

    public function enroll(User $user, CourseSection $section): bool
    {
        return $user->hasRole('student')
            && $section->course()->where('is_published', true)->exists();
    }
}

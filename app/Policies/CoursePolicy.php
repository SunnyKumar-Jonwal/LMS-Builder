<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('professor');
    }

    public function update(User $user, Course $course): bool
    {
        return $user->hasRole('professor') && $course->professor_id === $user->id;
    }

    public function delete(User $user, Course $course): bool
    {
        return $this->update($user, $course);
    }
}

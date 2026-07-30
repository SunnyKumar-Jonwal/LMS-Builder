<?php

namespace Database\Factories;

use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => User::factory(),
            'course_section_id' => CourseSection::factory(),
            'status' => Enrollment::STATUS_ACTIVE,
            'enrolled_at' => now(),
        ];
    }
}

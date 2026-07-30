<?php

namespace Database\Factories;

use App\Models\CourseSection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceRecordFactory extends Factory
{
    public function definition(): array
    {
        return [
            'course_section_id' => CourseSection::factory(),
            'student_id' => User::factory(),
            'date' => now()->toDateString(),
            'status' => 'present',
            'marked_by' => User::factory(),
        ];
    }
}

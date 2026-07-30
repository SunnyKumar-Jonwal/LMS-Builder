<?php

namespace Database\Factories;

use App\Models\CourseSection;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    public function definition(): array
    {
        return [
            'course_section_id' => CourseSection::factory(),
            'title' => fake()->sentence(3),
            'time_limit_minutes' => 30,
            'max_attempts' => 1,
        ];
    }
}

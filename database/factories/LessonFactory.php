<?php

namespace Database\Factories;

use App\Models\CourseSection;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    public function definition(): array
    {
        return [
            'course_section_id' => CourseSection::factory(),
            'title' => fake()->sentence(4),
            'position' => fake()->unique()->numberBetween(1, 999),
            'content' => '<p>'.fake()->paragraph().'</p>',
            'is_published' => true,
        ];
    }
}

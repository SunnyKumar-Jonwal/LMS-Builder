<?php

namespace Database\Factories;

use App\Models\CourseSection;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'course_section_id' => CourseSection::factory(),
            'title' => fake()->sentence(4),
            'instructions' => '<p>'.fake()->paragraph().'</p>',
            'due_at' => now()->addWeek(),
            'reopened_until' => null,
            'max_points' => 100,
        ];
    }
}

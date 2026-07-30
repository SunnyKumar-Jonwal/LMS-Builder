<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignmentSubmissionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'assignment_id' => Assignment::factory(),
            'student_id' => User::factory(),
            'file_path' => 'assignment-submissions/example.pdf',
            'submitted_at' => now(),
            'grade' => null,
            'feedback' => null,
            'graded_by' => null,
            'graded_at' => null,
        ];
    }
}

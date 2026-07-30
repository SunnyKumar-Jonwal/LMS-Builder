<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizAttemptFactory extends Factory
{
    public function definition(): array
    {
        return [
            'quiz_id' => Quiz::factory(),
            'student_id' => User::factory(),
            'started_at' => now(),
            'submitted_at' => null,
            'score' => null,
        ];
    }
}

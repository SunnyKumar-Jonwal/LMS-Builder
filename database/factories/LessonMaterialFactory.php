<?php

namespace Database\Factories;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonMaterialFactory extends Factory
{
    public function definition(): array
    {
        return [
            'lesson_id' => Lesson::factory(),
            'file_path' => 'lesson-materials/example.pdf',
            'original_filename' => 'example.pdf',
            'mime_type' => 'application/pdf',
            'size' => 1024,
        ];
    }
}

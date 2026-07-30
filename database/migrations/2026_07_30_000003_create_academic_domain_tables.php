<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->foreignId('professor_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        Schema::create('course_sections', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->unsignedInteger('capacity');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
            $table->index(['teacher_id', 'course_id']);
        });

        Schema::create('enrollments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_section_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['active', 'completed', 'dropped'])->default('active');
            $table->timestamp('enrolled_at')->useCurrent();
            $table->timestamps();
            $table->unique(['student_id', 'course_section_id']);
            $table->index(['course_section_id', 'status']);
        });

        Schema::create('lessons', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_section_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->unsignedInteger('position');
            $table->longText('content')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->unique(['course_section_id', 'position']);
        });

        Schema::create('lesson_materials', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_materials');
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('course_sections');
        Schema::dropIfExists('courses');
    }
};

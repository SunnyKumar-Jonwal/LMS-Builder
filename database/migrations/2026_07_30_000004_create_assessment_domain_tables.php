<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_section_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->longText('instructions')->nullable();
            $table->timestamp('due_at');
            $table->timestamp('reopened_until')->nullable();
            $table->unsignedDecimal('max_points', 8, 2);
            $table->timestamps();
        });

        Schema::create('assignment_submissions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->string('file_path');
            $table->timestamp('submitted_at');
            $table->unsignedDecimal('grade', 8, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();
            $table->unique(['assignment_id', 'student_id']);
        });

        Schema::create('quizzes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_section_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->unsignedInteger('time_limit_minutes')->nullable();
            $table->unsignedInteger('max_attempts')->default(1);
            $table->timestamps();
        });

        Schema::create('quiz_questions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->longText('question_text');
            $table->enum('type', ['mcq', 'true_false', 'short_answer']);
            $table->unsignedDecimal('points', 8, 2);
            $table->timestamps();
        });

        Schema::create('quiz_options', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('quiz_question_id')->constrained()->cascadeOnDelete();
            $table->string('option_text');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });

        Schema::create('quiz_attempts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('started_at');
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedDecimal('score', 8, 2)->nullable();
            $table->timestamps();
            $table->index(['quiz_id', 'student_id']);
        });

        Schema::create('quiz_answers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('quiz_attempt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quiz_question_id')->constrained()->cascadeOnDelete();
            $table->longText('answer_text')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->timestamps();
            $table->unique(['quiz_attempt_id', 'quiz_question_id']);
        });

        Schema::create('attendance_records', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'excused']);
            $table->foreignId('marked_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['course_section_id', 'student_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
        Schema::dropIfExists('quiz_answers');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_options');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
        Schema::dropIfExists('assignment_submissions');
        Schema::dropIfExists('assignments');
    }
};

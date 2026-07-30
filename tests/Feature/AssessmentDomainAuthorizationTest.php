<?php

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Services\QuizAttemptService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
});

function assessmentUser(string $role): User
{
    $user = User::factory()->create(['email_verified_at' => now()]);
    $user->assignRole($role);

    return $user;
}

it('prevents teachers from grading submissions outside their section', function (): void {
    $teacher = assessmentUser('teacher');
    $otherTeacher = assessmentUser('teacher');
    $student = assessmentUser('student');
    $section = CourseSection::factory()->create(['teacher_id' => $otherTeacher->id]);
    $assignment = Assignment::factory()->create(['course_section_id' => $section->id]);

    Enrollment::factory()->create([
        'student_id' => $student->id,
        'course_section_id' => $section->id,
        'status' => Enrollment::STATUS_ACTIVE,
    ]);

    $submission = AssignmentSubmission::factory()->create([
        'assignment_id' => $assignment->id,
        'student_id' => $student->id,
    ]);

    expect(Gate::forUser($teacher)->allows('grade', $submission))->toBeFalse();
});

it('prevents students from submitting assignments after due date without reopening', function (): void {
    $student = assessmentUser('student');
    $section = CourseSection::factory()->create();
    $assignment = Assignment::factory()->create([
        'course_section_id' => $section->id,
        'due_at' => now()->subDay(),
        'reopened_until' => null,
    ]);

    Enrollment::factory()->create([
        'student_id' => $student->id,
        'course_section_id' => $section->id,
        'status' => Enrollment::STATUS_ACTIVE,
    ]);

    expect(Gate::forUser($student)->allows('submit', $assignment))->toBeFalse();

    $assignment->forceFill(['reopened_until' => now()->addDay()])->save();

    expect(Gate::forUser($student)->allows('submit', $assignment->fresh()))->toBeTrue();
});

it('enforces quiz max attempts through the server-side query path', function (): void {
    $student = assessmentUser('student');
    $section = CourseSection::factory()->create();
    $quiz = Quiz::factory()->create(['course_section_id' => $section->id, 'max_attempts' => 1]);

    Enrollment::factory()->create([
        'student_id' => $student->id,
        'course_section_id' => $section->id,
        'status' => Enrollment::STATUS_ACTIVE,
    ]);

    QuizAttempt::factory()->create(['quiz_id' => $quiz->id, 'student_id' => $student->id]);

    app(QuizAttemptService::class)->start($student, $quiz);
})->throws(ValidationException::class, 'Maximum attempts reached.');

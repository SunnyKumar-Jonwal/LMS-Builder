<?php

use App\Livewire\CourseEnrollmentBrowser;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonMaterial;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
});

function roleUser(string $role): User
{
    $user = User::factory()->create(['email_verified_at' => now()]);
    $user->assignRole($role);

    return $user;
}

it('allows professors to edit only their own courses', function (): void {
    $professor = roleUser('professor');
    $otherProfessor = roleUser('professor');

    $ownCourse = Course::factory()->create(['professor_id' => $professor->id]);
    $otherCourse = Course::factory()->create(['professor_id' => $otherProfessor->id]);

    expect(Gate::forUser($professor)->allows('update', $ownCourse))->toBeTrue()
        ->and(Gate::forUser($professor)->allows('update', $otherCourse))->toBeFalse();
});

it('prevents teachers from editing sections they are not assigned to', function (): void {
    $teacher = roleUser('teacher');
    $otherTeacher = roleUser('teacher');
    $section = CourseSection::factory()->create(['teacher_id' => $otherTeacher->id]);

    expect(Gate::forUser($teacher)->allows('manageLessons', $section))->toBeFalse()
        ->and(Gate::forUser($teacher)->allows('manageMaterials', $section))->toBeFalse();
});

it('prevents students from enrolling past capacity', function (): void {
    $student = roleUser('student');
    $enrolledStudent = roleUser('student');
    $course = Course::factory()->create(['is_published' => true]);
    $section = CourseSection::factory()->create(['course_id' => $course->id, 'capacity' => 1]);

    Enrollment::factory()->create([
        'student_id' => $enrolledStudent->id,
        'course_section_id' => $section->id,
        'status' => Enrollment::STATUS_ACTIVE,
    ]);

    Livewire::actingAs($student)
        ->test(CourseEnrollmentBrowser::class)
        ->call('enroll', $section->id)
        ->assertHasErrors(['section']);

    expect($section->enrollments()->where('status', Enrollment::STATUS_ACTIVE)->count())->toBe(1);
});

it('prevents students from viewing materials for sections they are not enrolled in', function (): void {
    $student = roleUser('student');
    $lesson = Lesson::factory()->create(['is_published' => true]);
    $material = LessonMaterial::factory()->create(['lesson_id' => $lesson->id]);

    $this->actingAs($student)
        ->get(route('lesson-materials.show', $material))
        ->assertForbidden();
});

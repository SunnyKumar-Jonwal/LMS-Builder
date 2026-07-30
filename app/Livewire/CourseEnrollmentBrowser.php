<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Enrollment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CourseEnrollmentBrowser extends Component
{
    public function enroll(int $sectionId): void
    {
        $user = auth()->user();
        abort_unless($user !== null && $user->hasRole('student'), 403);

        DB::transaction(function () use ($sectionId, $user): void {
            $section = CourseSection::query()
                ->with('course')
                ->whereKey($sectionId)
                ->lockForUpdate()
                ->firstOrFail();

            Gate::authorize('enroll', $section);

            $activeCount = $section->enrollments()
                ->where('status', Enrollment::STATUS_ACTIVE)
                ->lockForUpdate()
                ->count();

            if ($activeCount >= $section->capacity) {
                throw ValidationException::withMessages([
                    'section' => 'This section is already at capacity.',
                ]);
            }

            Enrollment::updateOrCreate(
                ['student_id' => $user->id, 'course_section_id' => $section->id],
                ['status' => Enrollment::STATUS_ACTIVE, 'enrolled_at' => now()],
            );
        });
    }

    public function render(): View
    {
        $courses = Course::query()
            ->where('is_published', true)
            ->with(['sections' => function ($query): void {
                $query->withCount(['enrollments as active_enrollments_count' => fn ($enrollments) => $enrollments->where('status', Enrollment::STATUS_ACTIVE)])
                    ->having('active_enrollments_count', '<', DB::raw('capacity'));
            }])
            ->orderBy('title')
            ->get();

        return view('livewire.course-enrollment-browser', ['courses' => $courses]);
    }
}

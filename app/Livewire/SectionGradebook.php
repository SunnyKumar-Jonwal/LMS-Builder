<?php

namespace App\Livewire;

use App\Models\CourseSection;
use App\Models\Enrollment;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SectionGradebook extends Component
{
    public CourseSection $section;

    public function mount(CourseSection $section): void
    {
        abort_unless($this->canView($section), 403);
        $this->section = $section;
    }

    public function render(): View
    {
        abort_unless($this->canView($this->section), 403);

        $students = $this->section->enrollments()
            ->with('student')
            ->where('status', Enrollment::STATUS_ACTIVE)
            ->when(auth()->user()?->hasRole('student'), fn ($query) => $query->where('student_id', auth()->id()))
            ->get()
            ->pluck('student');

        $assignments = $this->section->assignments()->with('submissions')->get();
        $quizzes = $this->section->quizzes()->with('attempts')->get();

        return view('livewire.section-gradebook', compact('students', 'assignments', 'quizzes'));
    }

    private function canView(CourseSection $section): bool
    {
        $user = auth()->user();

        if ($user?->hasRole('teacher') && $section->teacher_id === $user->id) {
            return true;
        }

        if ($user?->hasRole('professor') && $section->course->professor_id === $user->id) {
            return true;
        }

        return $user?->hasRole('student')
            && $section->enrollments()
                ->where('student_id', $user->id)
                ->where('status', Enrollment::STATUS_ACTIVE)
                ->exists();
    }
}

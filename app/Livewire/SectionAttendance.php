<?php

namespace App\Livewire;

use App\Models\AttendanceRecord;
use App\Models\CourseSection;
use App\Models\Enrollment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SectionAttendance extends Component
{
    public CourseSection $section;
    public string $date;
    public array $statuses = [];

    public function mount(CourseSection $section): void
    {
        abort_unless(Gate::allows('viewSection', [AttendanceRecord::class, $section]), 403);
        $this->section = $section;
        $this->date = now()->toDateString();
    }

    public function mark(): void
    {
        Gate::authorize('mark', [AttendanceRecord::class, $this->section]);

        $this->validate([
            'date' => ['required', 'date'],
            'statuses' => ['array'],
            'statuses.*' => ['required', Rule::in(AttendanceRecord::STATUSES)],
        ]);

        foreach ($this->statuses as $studentId => $status) {
            AttendanceRecord::updateOrCreate(
                ['course_section_id' => $this->section->id, 'student_id' => $studentId, 'date' => $this->date],
                ['status' => $status, 'marked_by' => auth()->id()],
            );
        }
    }

    public function render(): View
    {
        abort_unless(Gate::allows('viewSection', [AttendanceRecord::class, $this->section]), 403);

        $students = $this->section->enrollments()
            ->with('student')
            ->where('status', Enrollment::STATUS_ACTIVE)
            ->when(auth()->user()?->hasRole('student'), fn ($query) => $query->where('student_id', auth()->id()))
            ->get()
            ->pluck('student');

        $records = $this->section->attendanceRecords()->whereDate('date', $this->date)->get()->keyBy('student_id');

        return view('livewire.section-attendance', compact('students', 'records'));
    }
}

<?php
use App\Http\Controllers\AssignmentSubmissionController;
use App\Http\Controllers\LessonMaterialController;
use App\Livewire\CourseEnrollmentBrowser;
use App\Livewire\SectionAttendance;
use App\Livewire\SectionGradebook;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::middleware(['auth','verified'])->group(function (): void {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::prefix('admin')->middleware('role:super_admin')->group(function (): void { Route::view('/dashboard', 'dashboard.admin')->name('admin.dashboard'); });
    Route::prefix('professor')->middleware('role:professor')->group(function (): void { Route::view('/dashboard', 'dashboard.professor')->name('professor.dashboard'); });
    Route::prefix('teacher')->middleware('role:teacher')->group(function (): void { Route::view('/dashboard', 'dashboard.teacher')->name('teacher.dashboard'); });
    Route::prefix('student')->middleware('role:student')->group(function (): void {
        Route::view('/dashboard', 'dashboard.student')->name('student.dashboard');
        Route::get('/courses', CourseEnrollmentBrowser::class)->name('student.courses.index');
    });
    Route::view('/account/two-factor-authentication', 'profile.two-factor-authentication')->name('profile.two-factor');
    Route::get('/lesson-materials/{material}', [LessonMaterialController::class, 'show'])->name('lesson-materials.show');
    Route::post('/lessons/{lesson}/materials', [LessonMaterialController::class, 'store'])->middleware('role:teacher')->name('lessons.materials.store');
    Route::post('/assignments/{assignment}/submissions', [AssignmentSubmissionController::class, 'store'])->middleware('role:student')->name('assignments.submissions.store');
    Route::get('/sections/{section}/gradebook', SectionGradebook::class)->name('sections.gradebook');
    Route::get('/sections/{section}/attendance', SectionAttendance::class)->name('sections.attendance');
});

<?php
codex/establish-project-context-for-lms-development-adf64r
use App\Http\Controllers\LessonMaterialController;
use App\Livewire\CourseEnrollmentBrowser;
main
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::middleware(['auth','verified'])->group(function (): void {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::prefix('admin')->middleware('role:super_admin')->group(function (): void { Route::view('/dashboard', 'dashboard.admin')->name('admin.dashboard'); });
    Route::prefix('professor')->middleware('role:professor')->group(function (): void { Route::view('/dashboard', 'dashboard.professor')->name('professor.dashboard'); });
    Route::prefix('teacher')->middleware('role:teacher')->group(function (): void { Route::view('/dashboard', 'dashboard.teacher')->name('teacher.dashboard'); });
codex/establish-project-context-for-lms-development-adf64r
    Route::prefix('student')->middleware('role:student')->group(function (): void {
        Route::view('/dashboard', 'dashboard.student')->name('student.dashboard');
        Route::get('/courses', CourseEnrollmentBrowser::class)->name('student.courses.index');
    });
    Route::view('/account/two-factor-authentication', 'profile.two-factor-authentication')->name('profile.two-factor');
    Route::get('/lesson-materials/{material}', [LessonMaterialController::class, 'show'])->name('lesson-materials.show');
    Route::post('/lessons/{lesson}/materials', [LessonMaterialController::class, 'store'])->middleware('role:teacher')->name('lessons.materials.store');
    Route::prefix('student')->middleware('role:student')->group(function (): void { Route::view('/dashboard', 'dashboard.student')->name('student.dashboard'); });
    Route::view('/account/two-factor-authentication', 'profile.two-factor-authentication')->name('profile.two-factor');
main
});

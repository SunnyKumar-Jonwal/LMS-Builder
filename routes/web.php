<?php
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::middleware(['auth','verified'])->group(function (): void {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::prefix('admin')->middleware('role:super_admin')->group(function (): void { Route::view('/dashboard', 'dashboard.admin')->name('admin.dashboard'); });
    Route::prefix('professor')->middleware('role:professor')->group(function (): void { Route::view('/dashboard', 'dashboard.professor')->name('professor.dashboard'); });
    Route::prefix('teacher')->middleware('role:teacher')->group(function (): void { Route::view('/dashboard', 'dashboard.teacher')->name('teacher.dashboard'); });
    Route::prefix('student')->middleware('role:student')->group(function (): void { Route::view('/dashboard', 'dashboard.student')->name('student.dashboard'); });
    Route::view('/account/two-factor-authentication', 'profile.two-factor-authentication')->name('profile.two-factor');
});

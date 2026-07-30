<?php

namespace App\Providers;

codex/establish-project-context-for-lms-development-jszrnb
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\AttendanceRecord;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\LessonMaterial;
use App\Models\Quiz;
use App\Policies\AssignmentPolicy;
use App\Policies\AssignmentSubmissionPolicy;
use App\Policies\AttendanceRecordPolicy;
use App\Policies\CoursePolicy;
use App\Policies\CourseSectionPolicy;
use App\Policies\LessonMaterialPolicy;
use App\Policies\QuizPolicy;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\LessonMaterial;
use App\Policies\CoursePolicy;
use App\Policies\CourseSectionPolicy;
use App\Policies\LessonMaterialPolicy;
main
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
codex/establish-project-context-for-lms-development-jszrnb
        Assignment::class => AssignmentPolicy::class,
        AssignmentSubmission::class => AssignmentSubmissionPolicy::class,
        AttendanceRecord::class => AttendanceRecordPolicy::class,
        Course::class => CoursePolicy::class,
        CourseSection::class => CourseSectionPolicy::class,
        LessonMaterial::class => LessonMaterialPolicy::class,
        Quiz::class => QuizPolicy::class,
        Course::class => CoursePolicy::class,
        CourseSection::class => CourseSectionPolicy::class,
        LessonMaterial::class => LessonMaterialPolicy::class,
main
    ];
}

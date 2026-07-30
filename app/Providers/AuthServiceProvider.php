<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\LessonMaterial;
use App\Policies\CoursePolicy;
use App\Policies\CourseSectionPolicy;
use App\Policies\LessonMaterialPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Course::class => CoursePolicy::class,
        CourseSection::class => CourseSectionPolicy::class,
        LessonMaterial::class => LessonMaterialPolicy::class,
    ];
}

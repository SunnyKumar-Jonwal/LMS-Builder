<?php

namespace App\Policies;

use App\Models\LessonMaterial;
use App\Models\User;

class LessonMaterialPolicy
{
    public function view(User $user, LessonMaterial $material): bool
    {
        $section = $material->lesson()->with('section')->firstOrFail()->section;

        return app(CourseSectionPolicy::class)->viewLessons($user, $section);
    }

    public function create(User $user, LessonMaterial $material): bool
    {
        $section = $material->lesson()->with('section')->firstOrFail()->section;

        return app(CourseSectionPolicy::class)->manageMaterials($user, $section);
    }
}

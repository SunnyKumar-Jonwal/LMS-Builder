<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class AssignmentSubmissionService
{
    public function submit(User $student, Assignment $assignment, UploadedFile $file): AssignmentSubmission
    {
        Gate::forUser($student)->authorize('submit', $assignment);

        $path = $file->store('assignment-submissions/'.$assignment->id.'/'.$student->id, 'private');

        return AssignmentSubmission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'student_id' => $student->id],
            ['file_path' => $path, 'submitted_at' => now()],
        );
    }
}

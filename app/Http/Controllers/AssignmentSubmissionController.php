<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssignmentSubmissionRequest;
use App\Models\Assignment;
use App\Services\AssignmentSubmissionService;
use Illuminate\Http\RedirectResponse;

class AssignmentSubmissionController extends Controller
{
    public function store(StoreAssignmentSubmissionRequest $request, Assignment $assignment, AssignmentSubmissionService $submissions): RedirectResponse
    {
        $submissions->submit($request->user(), $assignment, $request->file('submission'));

        return back()->with('status', 'Assignment submitted.');
    }
}

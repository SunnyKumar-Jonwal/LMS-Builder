<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLessonMaterialRequest;
use App\Models\Lesson;
use App\Models\LessonMaterial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LessonMaterialController extends Controller
{
    public function store(StoreLessonMaterialRequest $request, Lesson $lesson): RedirectResponse
    {
        $file = $request->file('material');
        $path = $file->store('lesson-materials/'.$lesson->id, 'private');

        $lesson->materials()->create([
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
            'size' => $file->getSize(),
        ]);

        return back()->with('status', 'Material uploaded.');
    }

    public function show(LessonMaterial $material): StreamedResponse
    {
        $this->authorize('view', $material);

        return Storage::disk('private')->download(
            $material->file_path,
            $material->original_filename,
            ['Content-Type' => $material->mime_type],
        );
    }
}

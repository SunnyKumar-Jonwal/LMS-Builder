<?php

namespace App\Http\Requests;

use App\Models\CourseSection;
use Illuminate\Foundation\Http\FormRequest;

class StoreLessonMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var CourseSection|null $section */
        $section = $this->route('lesson')?->section;

        return $section !== null && $this->user()?->can('manageMaterials', $section);
    }

    public function rules(): array
    {
        return [
            'material' => ['required', 'file', 'extensions:pdf,docx,pptx,mp4,png,jpg', 'max:51200'],
        ];
    }
}

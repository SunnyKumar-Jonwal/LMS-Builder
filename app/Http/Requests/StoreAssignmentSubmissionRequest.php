<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('submit', $this->route('assignment')) ?? false;
    }

    public function rules(): array
    {
        return [
            'submission' => ['required', 'file', 'extensions:pdf,docx,pptx,mp4,png,jpg', 'max:51200'],
        ];
    }
}

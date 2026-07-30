<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonMaterial extends Model
{
    use HasFactory;

    protected $fillable = ['lesson_id', 'file_path', 'original_filename', 'mime_type', 'size'];

    protected function casts(): array
    {
        return ['size' => 'integer'];
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}

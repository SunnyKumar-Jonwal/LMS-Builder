<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = ['course_section_id', 'title', 'position', 'content', 'is_published'];

    protected function casts(): array
    {
        return ['position' => 'integer', 'is_published' => 'boolean'];
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(LessonMaterial::class);
    }

    public function setContentAttribute(?string $value): void
    {
        $this->attributes['content'] = $value === null ? null : clean($value);
    }
}

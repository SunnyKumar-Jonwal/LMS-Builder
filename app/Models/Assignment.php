<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = ['course_section_id', 'title', 'instructions', 'due_at', 'reopened_until', 'max_points'];

    protected function casts(): array
    {
        return ['due_at' => 'datetime', 'reopened_until' => 'datetime', 'max_points' => 'decimal:2'];
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function acceptsSubmission(): bool
    {
        $deadline = collect([$this->due_at, $this->reopened_until])->filter()->max();

        return now()->lessThanOrEqualTo($deadline);
    }

    public function setInstructionsAttribute(?string $value): void
    {
        $this->attributes['instructions'] = $value === null ? null : clean($value);
    }
}

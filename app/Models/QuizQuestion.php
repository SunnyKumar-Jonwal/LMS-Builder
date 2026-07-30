<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    use HasFactory;

    public const TYPE_MCQ = 'mcq';
    public const TYPE_TRUE_FALSE = 'true_false';
    public const TYPE_SHORT_ANSWER = 'short_answer';

    protected $fillable = ['quiz_id', 'question_text', 'type', 'points'];

    protected function casts(): array
    {
        return ['points' => 'decimal:2'];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuizOption::class);
    }
}

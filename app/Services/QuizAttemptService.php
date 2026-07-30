<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class QuizAttemptService
{
    public function start(User $student, Quiz $quiz): QuizAttempt
    {
        Gate::forUser($student)->authorize('attempt', $quiz);

        return DB::transaction(function () use ($student, $quiz): QuizAttempt {
            $lockedQuiz = Quiz::query()->whereKey($quiz->id)->lockForUpdate()->firstOrFail();
            $attempts = QuizAttempt::query()
                ->where('quiz_id', $lockedQuiz->id)
                ->where('student_id', $student->id)
                ->lockForUpdate()
                ->count();

            if ($attempts >= $lockedQuiz->max_attempts) {
                throw ValidationException::withMessages(['quiz' => 'Maximum attempts reached.']);
            }

            return QuizAttempt::create([
                'quiz_id' => $lockedQuiz->id,
                'student_id' => $student->id,
                'started_at' => now(),
            ]);
        });
    }

    public function submit(QuizAttempt $attempt, array $answers): QuizAttempt
    {
        $score = 0;
        $needsManualGrading = false;

        $attempt->quiz->questions()->with('options')->get()->each(function (QuizQuestion $question) use ($attempt, $answers, &$score, &$needsManualGrading): void {
            $answerText = (string) ($answers[$question->id] ?? '');
            $isCorrect = null;

            if (in_array($question->type, [QuizQuestion::TYPE_MCQ, QuizQuestion::TYPE_TRUE_FALSE], true)) {
                $isCorrect = $question->options->contains(fn ($option): bool => $option->is_correct && (string) $option->id === $answerText);
                $score += $isCorrect ? (float) $question->points : 0;
            } else {
                $needsManualGrading = true;
            }

            $attempt->answers()->updateOrCreate(
                ['quiz_question_id' => $question->id],
                ['answer_text' => $answerText, 'is_correct' => $isCorrect],
            );
        });

        $attempt->forceFill([
            'submitted_at' => now(),
            'score' => $needsManualGrading ? null : $score,
        ])->save();

        return $attempt;
    }
}

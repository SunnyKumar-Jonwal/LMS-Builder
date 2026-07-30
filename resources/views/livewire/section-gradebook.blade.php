<div class="overflow-x-auto rounded bg-white p-6 shadow">
    <h2 class="mb-4 text-xl font-semibold">Gradebook</h2>
    <table class="min-w-full divide-y divide-gray-200">
        <thead><tr><th class="p-2 text-left">Student</th>@foreach ($assignments as $assignment)<th class="p-2">{{ $assignment->title }}</th>@endforeach @foreach ($quizzes as $quiz)<th class="p-2">{{ $quiz->title }}</th>@endforeach</tr></thead>
        <tbody>
            @foreach ($students as $student)
                <tr>
                    <td class="p-2">{{ $student->name }}</td>
                    @foreach ($assignments as $assignment)
                        <td class="p-2 text-center">{{ optional($assignment->submissions->firstWhere('student_id', $student->id))->grade ?? '—' }}</td>
                    @endforeach
                    @foreach ($quizzes as $quiz)
                        <td class="p-2 text-center">{{ optional($quiz->attempts->where('student_id', $student->id)->sortByDesc('score')->first())->score ?? '—' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

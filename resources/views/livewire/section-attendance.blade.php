<div class="rounded bg-white p-6 shadow">
    <h2 class="mb-4 text-xl font-semibold">Attendance</h2>
    <input type="date" wire:model.live="date" class="mb-4 rounded border-gray-300" />
    <div class="space-y-2">
        @foreach ($students as $student)
            <div class="flex items-center justify-between rounded border p-3">
                <span>{{ $student->name }}</span>
                @if (auth()->user()?->hasRole('teacher'))
                    <select wire:model="statuses.{{ $student->id }}" class="rounded border-gray-300">
                        @foreach (\App\Models\AttendanceRecord::STATUSES as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                @else
                    <span>{{ optional($records->get($student->id))->status ?? '—' }}</span>
                @endif
            </div>
        @endforeach
    </div>
    @if (auth()->user()?->hasRole('teacher'))
        <button wire:click="mark" class="mt-4 rounded bg-blue-600 px-4 py-2 text-white">Save attendance</button>
    @endif
</div>

<div class="space-y-6">
    @foreach ($courses as $course)
        <section class="rounded bg-white p-6 shadow">
            <h2 class="text-xl font-semibold">{{ $course->title }}</h2>
            <div class="prose mt-2">{!! $course->description !!}</div>
            <div class="mt-4 space-y-3">
                @foreach ($course->sections as $section)
                    <div class="flex items-center justify-between rounded border p-3">
                        <div>
                            <p class="font-medium">{{ $section->name }}</p>
                            <p class="text-sm text-gray-600">{{ $section->active_enrollments_count }} / {{ $section->capacity }} enrolled</p>
                        </div>
                        <button type="button" wire:click="enroll({{ $section->id }})" class="rounded bg-blue-600 px-4 py-2 text-white">Enroll</button>
                    </div>
                @endforeach
            </div>
        </section>
    @endforeach
</div>

<div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: true }">
    <aside class="fixed inset-y-0 left-0 w-64 bg-slate-900 text-white p-6" x-show="sidebarOpen">
        <h1 class="text-xl font-bold">LMS Builder</h1>
        <nav class="mt-8 space-y-2">
            @foreach ($items as $item)
                <a href="{{ $item['url'] }}" class="block rounded px-3 py-2 hover:bg-slate-700">{{ $item['label'] }}</a>
            @endforeach
        </nav>
    </aside>
    <main class="ml-64 p-8">
        {{ $slot ?? '' }}
    </main>
</div>

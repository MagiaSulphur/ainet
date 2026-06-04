<x-layouts::app :title="__('Course showcase')">
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($courses as $course)
            <a href="{{ route('courses.show', $course) }}" class="rounded-xl border border-neutral-200 p-4 hover:bg-neutral-50">
                <flux:heading>{{ $course->name }}</flux:heading>
                <flux:text>{{ $course->abbreviation }} · {{ $course->type }}</flux:text>
            </a>
        @endforeach
    </div>
</x-layouts::app>

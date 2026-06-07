@csrf

<div class="grid gap-4">
    <flux:input name="name" :label="__('Name')" value="{{ old('name', $category->name) }}" required />
    <flux:input name="image" :label="__('Category image')" type="file" accept="image/*" />

    @if ($category->image_url)
        <img
            src="{{ asset('storage/categories/'.$category->image_url) }}"
            alt="{{ $category->name }}"
            class="size-32 rounded-lg border border-neutral-200 object-cover dark:border-neutral-700"
        >
    @endif
</div>

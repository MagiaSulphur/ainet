@csrf

<div class="grid gap-4">
    <flux:input name="name" :label="__('Name')" value="{{ old('name', $image->name) }}" required />

    <flux:textarea name="description" :label="__('Description')">{{ old('description', $image->description) }}</flux:textarea>

    <flux:select name="category_id" :label="__('Category')">
        <option value="">{{ __('Without category') }}</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $image->category_id) == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </flux:select>

    <flux:input name="image" :label="__('Image file')" type="file" accept="image/*" @required(! $image->exists) />

    @if ($image->exists)
        <img
            src="{{ asset('storage/tshirt_images/'.$image->image_url) }}"
            alt="{{ $image->name }}"
            class="size-32 rounded-lg border border-neutral-200 bg-white object-contain p-2 dark:border-neutral-700"
        >
    @endif
</div>

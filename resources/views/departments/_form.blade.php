@csrf

<div class="grid gap-4 md:grid-cols-2">
    @if (! isset($department))
        <flux:input name="abbreviation" :label="__('Code')" :value="old('abbreviation')" required />
    @endif

    <flux:input name="name" :label="__('Name')" :value="old('name', $department->name ?? '')" required />
    <flux:input name="name_pt" :label="__('Portuguese name')" :value="old('name_pt', $department->name_pt ?? '')" required />
</div>

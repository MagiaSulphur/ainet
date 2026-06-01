@csrf

<div class="grid gap-4 md:grid-cols-2">
    @if (! isset($course))
        <flux:input name="abbreviation" :label="__('Code')" :value="old('abbreviation')" required />
    @endif

    <flux:input name="name" :label="__('Name')" :value="old('name', $course->name ?? '')" required />
    <flux:input name="name_pt" :label="__('Portuguese name')" :value="old('name_pt', $course->name_pt ?? '')" required />
    <flux:input name="type" :label="__('Type')" :value="old('type', $course->type ?? 'Degree')" required />
    <flux:input name="semesters" :label="__('Semesters')" type="number" :value="old('semesters', $course->semesters ?? 6)" required />
    <flux:input name="ECTS" :label="__('ECTS')" type="number" :value="old('ECTS', $course->ECTS ?? 180)" required />
    <flux:input name="places" :label="__('Places')" type="number" :value="old('places', $course->places ?? 0)" required />
    <flux:input name="contact" :label="__('Contact email')" type="email" :value="old('contact', $course->contact ?? '')" required />
</div>

<div class="grid gap-4 md:grid-cols-2">
    <flux:textarea name="objectives" :label="__('Objectives')" required>{{ old('objectives', $course->objectives ?? '') }}</flux:textarea>
    <flux:textarea name="objectives_pt" :label="__('Portuguese objectives')" required>{{ old('objectives_pt', $course->objectives_pt ?? '') }}</flux:textarea>
</div>

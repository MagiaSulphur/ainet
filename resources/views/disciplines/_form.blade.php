@csrf

<div class="grid gap-4 md:grid-cols-2">
    <flux:input name="abbreviation" :label="__('Code')" :value="old('abbreviation', $discipline->abbreviation ?? '')" required />
    <flux:input name="name" :label="__('Name')" :value="old('name', $discipline->name ?? '')" required />
    <flux:input name="name_pt" :label="__('Portuguese name')" :value="old('name_pt', $discipline->name_pt ?? '')" required />
    <flux:input name="course" :label="__('Course code')" :value="old('course', $discipline->course ?? '')" required />
    <flux:input name="year" :label="__('Year')" type="number" :value="old('year', $discipline->year ?? 1)" required />
    <flux:input name="semester" :label="__('Semester')" type="number" :value="old('semester', $discipline->semester ?? 1)" required />
    <flux:input name="ECTS" :label="__('ECTS')" type="number" :value="old('ECTS', $discipline->ECTS ?? 6)" required />
    <flux:input name="hours" :label="__('Hours')" type="number" :value="old('hours', $discipline->hours ?? 60)" required />
    <input type="hidden" name="optional" value="0">
    <flux:checkbox name="optional" value="1" :checked="old('optional', $discipline->optional ?? false)" :label="__('Optional')" />
</div>

<?php

namespace App\Http\Controllers;

use App\Http\Requests\DisciplineFormRequest;
use App\Models\Course;
use App\Models\Discipline;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DisciplineController extends Controller
{
    public function index(): View
    {
        return view('disciplines.index', [
            'disciplines' => Discipline::query()->with('courseModel')->orderBy('course')->orderBy('year')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('disciplines.create', [
            'courses' => Course::query()->orderBy('abbreviation')->get(),
        ]);
    }

    public function store(DisciplineFormRequest $request): RedirectResponse
    {
        $discipline = Discipline::create($request->validated());

        return redirect()->route('disciplines.show', $discipline);
    }

    public function show(Discipline $discipline): View
    {
        return view('disciplines.show', [
            'discipline' => $discipline->load('courseModel'),
        ]);
    }

    public function edit(Discipline $discipline): View
    {
        return view('disciplines.edit', [
            'discipline' => $discipline,
            'courses' => Course::query()->orderBy('abbreviation')->get(),
        ]);
    }

    public function update(DisciplineFormRequest $request, Discipline $discipline): RedirectResponse
    {
        $discipline->update($request->validated());

        return redirect()->route('disciplines.show', $discipline);
    }

    public function destroy(Discipline $discipline): RedirectResponse
    {
        $discipline->delete();

        return redirect()->route('disciplines.index');
    }
}

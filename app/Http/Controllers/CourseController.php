<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseFormRequest;
use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CourseController extends Controller
{
    public function index(): View
    {
        return view('courses.index', [
            'courses' => Course::query()->orderBy('abbreviation')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('courses.create');
    }

    public function store(CourseFormRequest $request): RedirectResponse
    {
        $course = Course::create($request->validated());

        return redirect()->route('courses.show', $course);
    }

    public function show(Course $course): View
    {
        return view('courses.show', [
            'course' => $course,
        ]);
    }

    public function edit(Course $course): View
    {
        return view('courses.edit', [
            'course' => $course,
        ]);
    }

    public function update(CourseFormRequest $request, Course $course): RedirectResponse
    {
        $course->update($request->validated());

        return redirect()->route('courses.show', $course);
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()->route('courses.index');
    }

    public function showCase(): View
    {
        return view('courses.showcase', [
            'courses' => Course::query()->orderBy('name')->get(),
        ]);
    }

    public function showCurriculum(Course $course): View
    {
        return view('courses.curriculum', [
            'course' => $course->load('disciplines'),
        ]);
    }
}

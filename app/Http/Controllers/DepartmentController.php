<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentFormRequest;
use App\Models\Department;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DepartmentController extends Controller
{
    public function index(): View
    {
        return view('departments.index', [
            'departments' => Department::query()->orderBy('abbreviation')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('departments.create');
    }

    public function store(DepartmentFormRequest $request): RedirectResponse
    {
        $department = Department::create($request->validated());

        return redirect()->route('departments.show', $department);
    }

    public function show(Department $department): View
    {
        return view('departments.show', [
            'department' => $department,
        ]);
    }

    public function edit(Department $department): View
    {
        return view('departments.edit', [
            'department' => $department,
        ]);
    }

    public function update(DepartmentFormRequest $request, Department $department): RedirectResponse
    {
        $department->update($request->validated());

        return redirect()->route('departments.show', $department);
    }

    public function destroy(Department $department): RedirectResponse
    {
        $department->delete();

        return redirect()->route('departments.index');
    }
}

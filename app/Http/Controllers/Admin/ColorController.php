<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ColorController extends Controller
{
    public function index(): View
    {
        $this->authorizeAdmin();

        return view('admin.colors.index', [
            'colors' => Color::query()
                ->withCount('orderItems')
                ->orderBy('name')
                ->paginate(20),
        ]);
    }

    public function create(): View
    {
        $this->authorizeAdmin();

        return view('admin.colors.create', ['color' => new Color()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $this->validated($request);
        unset($validated['base_image']);

        Color::create($validated);
        $this->storeBaseImage($request, $validated['code']);

        return redirect()->route('admin.colors.index')->with('status', __('Color created.'));
    }

    public function edit(Color $color): View
    {
        $this->authorizeAdmin();

        return view('admin.colors.edit', compact('color'));
    }

    public function update(Request $request, Color $color): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'base_image' => ['nullable', 'image', 'mimes:jpg,jpeg', 'max:8192'],
        ]);

        $color->update(['name' => $validated['name']]);
        $this->storeBaseImage($request, $color->code);

        return redirect()->route('admin.colors.index')->with('status', __('Color updated.'));
    }

    public function destroy(Color $color): RedirectResponse
    {
        $this->authorizeAdmin();

        $color->delete();

        return redirect()->route('admin.colors.index')->with('status', __('Color removed.'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:50', 'regex:/^#?[A-Za-z0-9_-]+$/', Rule::unique(Color::class, 'code')],
            'name' => ['required', 'string', 'max:255'],
            'base_image' => ['nullable', 'image', 'mimes:jpg,jpeg', 'max:8192'],
        ]);
    }

    private function storeBaseImage(Request $request, string $code): void
    {
        if (! $request->hasFile('base_image')) {
            return;
        }

        Storage::disk('public')->delete('tshirt_base/'.$code.'.jpg');
        $request->file('base_image')->storeAs('tshirt_base', $code.'.jpg', 'public');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }
}

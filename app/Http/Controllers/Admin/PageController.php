<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $pages = Page::orderBy('display_order')->orderBy('created_at', 'desc')->paginate($perPage);
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200|unique:pages,slug',
            'content' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['is_active'] = $request->has('is_active');

        Page::create($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully!');
    }

    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200|unique:pages,slug,' . $id,
            'content' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['is_active'] = $request->has('is_active');

        $page->update($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully!');
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully!');
    }
}

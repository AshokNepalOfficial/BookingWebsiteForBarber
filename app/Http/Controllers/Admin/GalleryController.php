<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 30);
        $items = GalleryItem::orderBy('display_order')->orderBy('created_at', 'desc')->paginate($perPage);
        return view('admin.gallery.index', compact('items'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'image' => 'required|image|max:5120',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['category'] = $validated['category'] ?? 'general';

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('gallery', 'public');
        }

        unset($validated['image']);
        GalleryItem::create($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item added successfully!');
    }

    public function edit($id)
    {
        $item = GalleryItem::findOrFail($id);
        return view('admin.gallery.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = GalleryItem::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['category'] = $validated['category'] ?? 'general';

        if ($request->hasFile('image')) {
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('gallery', 'public');
        }

        unset($validated['image']);
        $item->update($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item updated successfully!');
    }

    public function destroy($id)
    {
        $item = GalleryItem::findOrFail($id);
        
        if ($item->image_path) {
            Storage::disk('public')->delete($item->image_path);
        }
        
        $item->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item deleted successfully!');
    }

    public function reorder(Request $request)
    {
        $items = $request->input('items');
        
        foreach ($items as $item) {
            GalleryItem::where('id', $item['id'])->update(['display_order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('display_order')->get();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_title' => 'nullable|string|max:255',
            'testimonial' => 'required|string',
            'customer_image' => 'nullable|image|max:5120',
            'rating' => 'required|integer|min:1|max:5',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('customer_image')) {
            $validated['customer_image'] = $request->file('customer_image')->store('testimonials', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['display_order'] = $validated['display_order'] ?? Testimonial::max('display_order') + 1;

        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial created successfully!');
    }

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_title' => 'nullable|string|max:255',
            'testimonial' => 'required|string',
            'customer_image' => 'nullable|image|max:5120',
            'rating' => 'required|integer|min:1|max:5',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('customer_image')) {
            // Delete old image
            if ($testimonial->customer_image) {
                Storage::disk('public')->delete($testimonial->customer_image);
            }
            $validated['customer_image'] = $request->file('customer_image')->store('testimonials', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated successfully!');
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        // Delete image if exists
        if ($testimonial->customer_image) {
            Storage::disk('public')->delete($testimonial->customer_image);
        }

        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted successfully!');
    }
}

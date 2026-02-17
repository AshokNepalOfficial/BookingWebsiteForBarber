<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CmsController extends Controller
{
    public function index()
    {
        $sections = [
            'hero' => 'Hero Section',
            'about' => 'About Section',
            'services_intro' => 'Services Introduction',
            'testimonials' => 'Testimonials Section',
            'contact' => 'Contact Information',
            'footer' => 'Footer Content',
        ];

        $contents = CmsContent::orderBy('section')->orderBy('order')->get()->groupBy('section');

        return view('admin.cms.index', compact('sections', 'contents'));
    }

    public function edit($section)
    {
        $sectionNames = [
            'hero' => 'Hero Section',
            'about' => 'About Section',
            'services_intro' => 'Services Introduction',
            'testimonials' => 'Testimonials Section',
            'contact' => 'Contact Information',
            'footer' => 'Footer Content',
        ];

        $sectionName = $sectionNames[$section] ?? ucfirst($section);
        $contents = CmsContent::where('section', $section)->orderBy('order')->get();

        // Define section fields
        $fields = $this->getSectionFields($section);

        return view('admin.cms.edit', compact('section', 'sectionName', 'contents', 'fields'));
    }

    public function update(Request $request, $section)
    {
        $validated = $request->validate([
            'contents' => 'required|array',
            'contents.*.key' => 'required|string',
            'contents.*.value' => 'nullable',
            'contents.*.type' => 'required|string',
        ]);

        foreach ($validated['contents'] as $order => $content) {
            $value = null;
            
            // Handle image uploads
            if ($content['type'] === 'image' && $request->hasFile("contents.{$order}.value")) {
                $path = $request->file("contents.{$order}.value")->store('cms', 'public');
                $value = $path;
            } elseif ($content['type'] === 'image' && !$request->hasFile("contents.{$order}.value")) {
                // Keep existing image if no new upload
                $existing = CmsContent::where('section', $section)
                    ->where('key', $content['key'])
                    ->first();
                $value = $existing ? $existing->value : null;
            } else {
                // For text, textarea, url fields
                $value = $content['value'] ?? null;
            }

            CmsContent::updateOrCreate(
                [
                    'section' => $section,
                    'key' => $content['key'],
                ],
                [
                    'value' => $value,
                    'type' => $content['type'],
                    'order' => $order,
                ]
            );
        }

        return redirect()->route('admin.cms.index')
            ->with('success', 'Content updated successfully!');
    }

    private function getSectionFields($section)
    {
        $fields = [
            'hero' => [
                ['key' => 'title', 'label' => 'Main Title', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea'],
                ['key' => 'cta_text', 'label' => 'Button Text', 'type' => 'text'],
                ['key' => 'background_image', 'label' => 'Background Image', 'type' => 'image'],
            ],
            'about' => [
                ['key' => 'title', 'label' => 'Section Title', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                ['key' => 'image', 'label' => 'Section Image', 'type' => 'image'],
            ],
            'services_intro' => [
                ['key' => 'title', 'label' => 'Section Title', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea'],
            ],
            'testimonials' => [
                ['key' => 'title', 'label' => 'Section Title', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Subtitle', 'type' => 'text'],
            ],
            'contact' => [
                ['key' => 'phone', 'label' => 'Phone Number', 'type' => 'text'],
                ['key' => 'email', 'label' => 'Email Address', 'type' => 'text'],
                ['key' => 'address', 'label' => 'Address', 'type' => 'textarea'],
                ['key' => 'hours', 'label' => 'Business Hours', 'type' => 'textarea'],
            ],
            'footer' => [
                ['key' => 'copyright', 'label' => 'Copyright Text', 'type' => 'text'],
                ['key' => 'facebook', 'label' => 'Facebook URL', 'type' => 'url'],
                ['key' => 'instagram', 'label' => 'Instagram URL', 'type' => 'url'],
                ['key' => 'twitter', 'label' => 'Twitter URL', 'type' => 'url'],
            ],
        ];

        return $fields[$section] ?? [];
    }
}

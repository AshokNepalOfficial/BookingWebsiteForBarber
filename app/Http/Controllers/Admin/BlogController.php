<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $posts = BlogPost::with('author')->orderBy('created_at', 'desc')->paginate($perPage);
        return view('admin.blogs.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200|unique:blog_posts,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['author_id'] = Auth::id();
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_published'] = $request->has('is_published');
        $validated['published_at'] = $validated['is_published'] ? now() : null;

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog-images', 'public');
        }

        BlogPost::create($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post created successfully!');
    }

    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        return view('admin.blogs.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200|unique:blog_posts,slug,' . $id,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_published'] = $request->has('is_published');

        if ($validated['is_published'] && !$post->published_at) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('blog-images', 'public');
        }

        $post->update($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated successfully!');
    }

    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);
        
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        
        $post->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post deleted successfully!');
    }
}

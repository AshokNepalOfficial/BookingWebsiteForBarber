<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::withCount('bookings')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'sub_title' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        Service::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully!');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'sub_title' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $service->update($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully!');
    }

    public function toggleStatus($id)
    {
        $service = Service::findOrFail($id);
        $service->is_active = !$service->is_active;
        $service->save();

        $status = $service->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Service {$status} successfully!");
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        
        // Check if service has bookings
        if ($service->bookings()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete service with existing bookings. Please deactivate instead.');
        }
        
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully!');
    }
    
    public function performance()
    {
        $services = Service::withCount(['bookings as total_bookings' => function($query) {
                $query->whereMonth('created_at', now()->month);
            }])
            ->withCount(['bookings as completed_bookings' => function($query) {
                $query->where('status', 'completed')
                      ->whereMonth('created_at', now()->month);
            }])
            ->get();
        
        return view('admin.services.performance', compact('services'));
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $staff = User::with('roleDetails')
            ->whereIn('role', ['admin', 'receptionist', 'staff', 'manager', 'barber'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        $roles = Role::where('is_active', true)->get();
        return view('admin.staff.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone_no' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,receptionist,staff,manager,barber',
            'role_id' => 'nullable|exists:roles,id',
            'position' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric|min:0',
            'hire_date' => 'nullable|date',
            'can_accept_bookings' => 'nullable|boolean',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['can_accept_bookings'] = $request->has('can_accept_bookings');
        $validated['is_active'] = true;
        $validated['is_guest'] = false;
        $validated['loyalty_points'] = 0;

        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('staff', 'public');
        }

        $staff = User::create($validated);

        return redirect()->route('admin.staff.show', $staff->id)
            ->with('success', 'Staff member created successfully!');
    }

    public function show($id)
    {
        $staff = User::with(['roleDetails', 'staffBookings' => function($query) {
            $query->orderBy('appointment_date', 'desc')->limit(10);
        }])->findOrFail($id);

        $stats = [
            'total_bookings' => $staff->staffBookings()->count(),
            'pending_bookings' => $staff->staffBookings()->where('status', 'pending')->count(),
            'completed_bookings' => $staff->staffBookings()->where('status', 'completed')->count(),
        ];

        return view('admin.staff.show', compact('staff', 'stats'));
    }

    public function edit($id)
    {
        $staff = User::findOrFail($id);
        $roles = Role::where('is_active', true)->get();
        
        return view('admin.staff.edit', compact('staff', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $staff = User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_no' => 'required|string|max:20',
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,receptionist,staff,manager,barber',
            'role_id' => 'nullable|exists:roles,id',
            'position' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric|min:0',
            'hire_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'can_accept_bookings' => 'nullable|boolean',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['can_accept_bookings'] = $request->has('can_accept_bookings');

        if ($request->hasFile('profile_image')) {
            // Delete old image
            if ($staff->profile_image) {
                Storage::disk('public')->delete($staff->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('staff', 'public');
        }

        $staff->update($validated);

        return redirect()->route('admin.staff.show', $staff->id)
            ->with('success', 'Staff member updated successfully!');
    }

    public function destroy($id)
    {
        $staff = User::findOrFail($id);

        // Delete profile image if exists
        if ($staff->profile_image) {
            Storage::disk('public')->delete($staff->profile_image);
        }

        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $staff = User::findOrFail($id);
        $staff->is_active = !$staff->is_active;
        $staff->save();

        return redirect()->back()
            ->with('success', 'Staff status updated successfully!');
    }
}

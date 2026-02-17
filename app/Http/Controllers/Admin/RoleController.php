<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $roles = Role::withCount(['staff', 'permissions'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('group')->orderBy('display_name')->get()->groupBy('group');
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

        if (isset($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return redirect()->route('admin.roles.show', $role->id)
            ->with('success', 'Role created successfully!');
    }

    public function show($id)
    {
        $role = Role::with(['permissions', 'staff'])->findOrFail($id);
        
        $permissionsByGroup = $role->permissions->groupBy('group');

        return view('admin.roles.show', compact('role', 'permissionsByGroup'));
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::orderBy('group')->orderBy('display_name')->get()->groupBy('group');
        
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name,' . $id,
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        if (isset($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        } else {
            $role->permissions()->sync([]);
        }

        return redirect()->route('admin.roles.show', $role->id)
            ->with('success', 'Role updated successfully!');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Check if role has staff members
        if ($role->staff()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete role that has staff members assigned to it.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $role = Role::findOrFail($id);
        $role->is_active = !$role->is_active;
        $role->save();

        return redirect()->back()
            ->with('success', 'Role status updated successfully!');
    }
}

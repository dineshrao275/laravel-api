<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\RoleCreateRequest;
use App\Models\Role;

class RoleController extends BaseController
{
    /**
     * Display a listing of roles.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();

        return $this->res('Roles fetched successfully', true, $roles);
    }

    /**
     * Store a newly created role.
     */
    public function store(RoleCreateRequest $request)
    {
        $slug = strtolower(str_replace(' ', '-', $request->name));

        if (Role::where('slug', $slug)->exists()) {
            return $this->res('This role already exists', false, [], 422);
        }

        $role = Role::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description ?? null, 
        ]);

        // Optionally attach permissions if sent in request
        if ($request->has('permission_ids')) {
            $role->permissions()->sync($request->permission_ids);
            $role->load('permissions');
        }

        return $this->res('Role created successfully', true, $role, 201);
    }

    /**
     * Display the specified role.
     */
    public function show($id)
    {
        $role = Role::with('permissions', 'users')->find($id);

        if (!$role) {
            return $this->res('Role not found', false, [], 404);
        }

        return $this->res('Role fetched successfully', true, $role);
    }

    /**
     * Update the specified role.
     */
    public function update(RoleCreateRequest $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return $this->res('Role not found', false, [], 404);
        }

        $slug = strtolower(str_replace(' ', '-', $request->name));

        // Check if slug is taken by another role
        if (Role::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            return $this->res('A role with this name already exists', false, [], 422);
        }

        $role->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description ?? null,
        ]);

        // Sync permissions if provided
        if ($request->has('permission_ids')) {
            $role->permissions()->sync($request->permission_ids);
        }

        $role->load('permissions', 'users');

        return $this->res('Role updated successfully', true, $role->refresh());
    }

    /**
     * Remove the specified role.
     */
    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return $this->res('Role not found', false, [], 404);
        }

        $role->delete();

        return $this->res('Role deleted successfully', true, [], 200);
    }
}
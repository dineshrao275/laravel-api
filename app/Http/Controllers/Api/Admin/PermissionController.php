<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\PermissionCreateRequest;
use App\Models\Permission;


class PermissionController extends BaseController
{
    /**
     * Display a listing of permissions.
     */
    public function index()
    {
        $permissions = Permission::all();

        return $this->res('Permissions fetched successfully', true, $permissions);
    }

    /**
     * Store a newly created permission.
     */
    public function store(PermissionCreateRequest $request)
    {
        $slug = strtolower(str_replace(' ', '-', $request->name));

        if (Permission::where('slug', $slug)->exists()) {
            return $this->res('This permission already exists', false, [], 422);
        }

        $permission = Permission::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
        ]);

        return $this->res('Permission created successfully', true, $permission, 201);
    }

    /**
     * Update the specified permission.
     */
    public function update(PermissionCreateRequest $request, $id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return $this->res('Permission not found', false, [], 404);
        }
        $slug = strtolower(str_replace(' ', '-', $request->name));

        if (Permission::where('slug', $slug)->where('id', '!=', $request->id)->exists()) {
            return $this->res('A permission with this name already exists', false, [], 422);
        }

        $permission->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
        ]);

        return $this->res('Permission updated successfully', true, $permission->refresh());
    }

    /**
     * Remove the specified permission.
     */
    public function destroy($id) 
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return $this->res('Permission not found', false, [], 404);
        }

        $permission->delete();

        return $this->res('Permission deleted successfully', true, [], 200);
    }
}
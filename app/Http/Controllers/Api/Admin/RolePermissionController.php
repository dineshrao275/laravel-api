<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\AttachRolePermissionsRequest;
use App\Http\Requests\DetachRolePermissionsRequest;
use App\Http\Requests\SyncRolePermissionsRequest;
use App\Models\Role;

class RolePermissionController extends BaseController
{
    public function sync(SyncRolePermissionsRequest $request, Role $role)
    {
        $role->permissions()->sync($request->permission_ids);
        $role->load('permissions');

        return $this->res('Permissions synced successfully', true, $role);
    }

    public function attach(AttachRolePermissionsRequest $request, Role $role)
    {
        $role->permissions()->syncWithoutDetaching($request->permission_ids);
        $role->load('permissions');

        return $this->res('Permissions attached successfully', true, $role);
    }

    public function detach(DetachRolePermissionsRequest $request, Role $role)
    {
        $role->permissions()->detach($request->permission_ids);
        $role->load('permissions');

        return $this->res('Permissions detached successfully', true, $role);
    }
}
<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\AttachUserRolesRequest;
use App\Http\Requests\DetachUserRolesRequest;
use App\Http\Requests\SyncUserRolesRequest;
use App\Models\User;

class UserRoleController extends BaseController
{
    public function sync(SyncUserRolesRequest $request, User $user)
    {
        $user->roles()->sync($request->role_ids);
        $user->load('roles');

        return $this->res('User roles synced successfully', true, $user);
    }

    public function attach(AttachUserRolesRequest $request, User $user)
    {
        $user->roles()->syncWithoutDetaching($request->role_ids);
        $user->load('roles');

        return $this->res('Roles attached successfully', true, $user);
    }

    public function detach(DetachUserRolesRequest $request, User $user)
    {
        $user->roles()->detach($request->role_ids);
        $user->load('roles');

        return $this->res('Roles detached successfully', true, $user);
    }
}
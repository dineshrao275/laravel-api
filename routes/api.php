<?php

use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\PasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\PermissionController;
use App\Http\Controllers\Api\Admin\RolePermissionController;
use App\Http\Controllers\Api\BaseController;

Route::get('/login', function () {
    return BaseController::res('You are not logged in, please login', true);
})->name('login');
/** Auth Routes Start */
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [PasswordController::class, 'forgotPassword']);
Route::post('/reset-password', [PasswordController::class, 'resetPassword']);
Route::post('/logout', [AuthController::class, 'logout']);
/** Auth Routes End */

Route::middleware('auth:sanctum')->group(function () {
    /** Admin Routes Start */
    Route::group(['middleware' => 'permission:all'], function () {
        
        Route::get('/permissions',[PermissionController::class,'index']);
        Route::post('/permissions',[PermissionController::class,'store']);
        Route::put('/permissions/{id}',[PermissionController::class,'update']);
        Route::delete('/permissions/{id}',[PermissionController::class,'destroy']);

        Route::get('/roles',[RoleController::class,'index']);
        Route::post('/roles',[RoleController::class,'store']);
        Route::put('/roles/{id}',[RoleController::class,'update']);
        Route::delete('/roles/{id}',[RoleController::class,'destroy']);

        Route::post('roles/{role}/permissions/sync', [RolePermissionController::class, 'sync']);
        Route::post('roles/{role}/permissions/attach', [RolePermissionController::class, 'attach']);
        Route::post('roles/{role}/permissions/detach', [RolePermissionController::class, 'detach']);

        // Route::post('users/{user}/roles/sync', [UserRoleController::class, 'sync']);
        // Route::post('users/{user}/roles/attach', [UserRoleController::class, 'attach']);
        // Route::post('users/{user}/roles/detach', [UserRoleController::class, 'detach']);
        // Route::get('users/{user}/roles-permissions', [UserRoleController::class, 'show']);

    });
    /** Admin Routes End */
});

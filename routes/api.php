<?php

use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Users routes
    Route::get('/users', [UserController::class, 'index'])->name('api.v1.users.index');
    Route::post('/users', [UserController::class, 'store'])->name('api.v1.users.store');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('api.v1.users.show');
    Route::match(['put', 'patch'], '/users/{id}', [UserController::class, 'update'])->name('api.v1.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('api.v1.users.destroy');

    // Roles routes
    Route::get('/roles', [RoleController::class, 'index'])->name('api.v1.roles.index');
    Route::post('/roles', [RoleController::class, 'store'])->name('api.v1.roles.store');
    Route::get('/roles/{id}', [RoleController::class, 'show'])->name('api.v1.roles.show');
    Route::match(['put', 'patch'], '/roles/{id}', [RoleController::class, 'update'])->name('api.v1.roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('api.v1.roles.destroy');
    Route::post('/roles/{id}/permissions', [RoleController::class, 'syncPermissions'])->name('api.v1.roles.sync-permissions');

    // Permissions routes
    Route::get('/permissions', [PermissionController::class, 'index'])->name('api.v1.permissions.index');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('api.v1.permissions.store');
    Route::get('/permissions/{id}', [PermissionController::class, 'show'])->name('api.v1.permissions.show');
    Route::match(['put', 'patch'], '/permissions/{id}', [PermissionController::class, 'update'])->name('api.v1.permissions.update');
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('api.v1.permissions.destroy');
});

// Root /api aliases for direct access
Route::apiResource('users', UserController::class);
Route::apiResource('roles', RoleController::class);
Route::post('roles/{id}/permissions', [RoleController::class, 'syncPermissions'])->name('roles.sync-permissions');
Route::apiResource('permissions', PermissionController::class);

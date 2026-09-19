<?php

use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ProductController;
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

    // Categories routes
    Route::get('/categories/slug/{slug}', [CategoryController::class, 'showBySlug'])->name('api.v1.categories.slug');
    Route::get('/categories', [CategoryController::class, 'index'])->name('api.v1.categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('api.v1.categories.store');
    Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('api.v1.categories.show');
    Route::match(['put', 'patch'], '/categories/{id}', [CategoryController::class, 'update'])->name('api.v1.categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('api.v1.categories.destroy');

    // Products routes
    Route::get('/products/sku/{sku}', [ProductController::class, 'showBySku'])->name('api.v1.products.sku');
    Route::get('/products', [ProductController::class, 'index'])->name('api.v1.products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('api.v1.products.store');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('api.v1.products.show');
    Route::match(['put', 'patch'], '/products/{id}', [ProductController::class, 'update'])->name('api.v1.products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('api.v1.products.destroy');

    // Clients routes
    Route::get('/clients/domain/{domain}', [ClientController::class, 'showByDomain'])->name('api.v1.clients.domain');
    Route::get('/clients', [ClientController::class, 'index'])->name('api.v1.clients.index');
    Route::post('/clients', [ClientController::class, 'store'])->name('api.v1.clients.store');
    Route::get('/clients/{id}', [ClientController::class, 'show'])->name('api.v1.clients.show');
    Route::match(['put', 'patch'], '/clients/{id}', [ClientController::class, 'update'])->name('api.v1.clients.update');
    Route::delete('/clients/{id}', [ClientController::class, 'destroy'])->name('api.v1.clients.destroy');

    // Blogs routes
    Route::get('/blogs/slug/{slug}', [BlogController::class, 'showBySlug'])->name('api.v1.blogs.slug');
    Route::get('/blogs', [BlogController::class, 'index'])->name('api.v1.blogs.index');
    Route::post('/blogs', [BlogController::class, 'store'])->name('api.v1.blogs.store');
    Route::get('/blogs/{id}', [BlogController::class, 'show'])->name('api.v1.blogs.show');
    Route::match(['put', 'patch'], '/blogs/{id}', [BlogController::class, 'update'])->name('api.v1.blogs.update');
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy'])->name('api.v1.blogs.destroy');

    // Carts routes
    Route::get('/carts/user/{userId}', [CartController::class, 'userCart'])->name('api.v1.carts.user');
    Route::delete('/carts/user/{userId}/clear', [CartController::class, 'clear'])->name('api.v1.carts.clear');
    Route::get('/carts', [CartController::class, 'index'])->name('api.v1.carts.index');
    Route::post('/carts', [CartController::class, 'store'])->name('api.v1.carts.store');
    Route::get('/carts/{id}', [CartController::class, 'show'])->name('api.v1.carts.show');
    Route::match(['put', 'patch'], '/carts/{id}', [CartController::class, 'update'])->name('api.v1.carts.update');
    Route::delete('/carts/{id}', [CartController::class, 'destroy'])->name('api.v1.carts.destroy');

    // Orders routes
    Route::get('/orders/number/{orderNumber}', [OrderController::class, 'showByNumber'])->name('api.v1.orders.number');
    Route::get('/orders/user/{userId}', [OrderController::class, 'userOrders'])->name('api.v1.orders.user');
    Route::get('/orders', [OrderController::class, 'index'])->name('api.v1.orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('api.v1.orders.store');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('api.v1.orders.show');
    Route::match(['put', 'patch'], '/orders/{id}', [OrderController::class, 'update'])->name('api.v1.orders.update');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('api.v1.orders.destroy');

    // Payments routes
    Route::get('/payments/order/{orderId}', [PaymentController::class, 'byOrder'])->name('api.v1.payments.order');
    Route::get('/payments', [PaymentController::class, 'index'])->name('api.v1.payments.index');
    Route::post('/payments', [PaymentController::class, 'store'])->name('api.v1.payments.store');
    Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('api.v1.payments.show');
    Route::match(['put', 'patch'], '/payments/{id}', [PaymentController::class, 'update'])->name('api.v1.payments.update');
    Route::delete('/payments/{id}', [PaymentController::class, 'destroy'])->name('api.v1.payments.destroy');

    // Contacts routes
    Route::get('/contacts/client/{clientId}', [ContactController::class, 'byClient'])->name('api.v1.contacts.client');
    Route::get('/contacts', [ContactController::class, 'index'])->name('api.v1.contacts.index');
    Route::post('/contacts', [ContactController::class, 'store'])->name('api.v1.contacts.store');
    Route::get('/contacts/{id}', [ContactController::class, 'show'])->name('api.v1.contacts.show');
    Route::match(['put', 'patch'], '/contacts/{id}', [ContactController::class, 'update'])->name('api.v1.contacts.update');
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy'])->name('api.v1.contacts.destroy');
});

// Root /api aliases for direct access
Route::apiResource('users', UserController::class);
Route::apiResource('roles', RoleController::class);
Route::post('roles/{id}/permissions', [RoleController::class, 'syncPermissions'])->name('roles.sync-permissions');
Route::apiResource('permissions', PermissionController::class);
Route::apiResource('categories', CategoryController::class);
Route::get('categories/slug/{slug}', [CategoryController::class, 'showBySlug'])->name('categories.slug');
Route::apiResource('products', ProductController::class);
Route::get('products/sku/{sku}', [ProductController::class, 'showBySku'])->name('products.sku');
Route::apiResource('clients', ClientController::class);
Route::get('clients/domain/{domain}', [ClientController::class, 'showByDomain'])->name('clients.domain');
Route::apiResource('blogs', BlogController::class);
Route::get('blogs/slug/{slug}', [BlogController::class, 'showBySlug'])->name('blogs.slug');
Route::apiResource('carts', CartController::class);
Route::get('carts/user/{userId}', [CartController::class, 'userCart'])->name('carts.user');
Route::delete('carts/user/{userId}/clear', [CartController::class, 'clear'])->name('carts.clear');
Route::apiResource('orders', OrderController::class);
Route::get('orders/number/{orderNumber}', [OrderController::class, 'showByNumber'])->name('orders.number');
Route::get('orders/user/{userId}', [OrderController::class, 'userOrders'])->name('orders.user');
Route::apiResource('payments', PaymentController::class);
Route::get('payments/order/{orderId}', [PaymentController::class, 'byOrder'])->name('payments.order');
Route::apiResource('contacts', ContactController::class);
Route::get('contacts/client/{clientId}', [ContactController::class, 'byClient'])->name('contacts.client');

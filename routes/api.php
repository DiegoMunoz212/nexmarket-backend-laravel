<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ShipmentTrackingController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SponsoredProductController;
use App\Http\Controllers\Api\SellerAnalyticController;
use App\Http\Controllers\Api\ReturnRequestController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\UserController;

// ── Rutas públicas ──────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

// Productos públicos
Route::get('products',             [ProductController::class, 'index']);
Route::get('products/popular',     [ProductController::class, 'popular']);
Route::get('products/featured',    [ProductController::class, 'featured']);
Route::get('products/{product}',   [ProductController::class, 'show']);

// Categorías públicas
Route::get('categories',           [CategoryController::class, 'index']);
Route::get('categories/{category}',[CategoryController::class, 'show']);

// Vendedores públicos
Route::get('sellers',              [UserController::class, 'sellers']);
Route::get('sellers/{id}',         [UserController::class, 'seller']);

// ── Rutas protegidas ────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('auth/logout',     [AuthController::class, 'logout']);
    Route::get('auth/me',          [AuthController::class, 'me']);

    // Usuarios
    Route::get('users',            [UserController::class, 'index']);
    Route::get('users/{id}',       [UserController::class, 'show']);
    Route::put('users/{id}',       [UserController::class, 'update']);

    // Productos (CRUD vendedor)
    Route::post('products',        [ProductController::class, 'store']);
    Route::put('products/{product}',[ProductController::class, 'update']);
    Route::delete('products/{product}',[ProductController::class, 'destroy']);

    // Categorías (admin)
    Route::post('categories',           [CategoryController::class, 'store']);
    Route::put('categories/{category}', [CategoryController::class, 'update']);
    Route::delete('categories/{category}',[CategoryController::class, 'destroy']);

    // Órdenes
    Route::apiResource('orders', OrderController::class);

    // Descuentos
    Route::apiResource('discounts', DiscountController::class);
    Route::post('discounts/validate', [DiscountController::class, 'validate']);

    // Reseñas
    Route::apiResource('reviews', ReviewController::class);

    // Wishlist
    Route::get('wishlist',              [WishlistController::class, 'index']);
    Route::post('wishlist',             [WishlistController::class, 'store']);
    Route::delete('wishlist/{product}', [WishlistController::class, 'destroy']);

    // Notificaciones
    Route::get('notifications',          [NotificationController::class, 'index']);
    Route::put('notifications/{id}/read',[NotificationController::class, 'markAsRead']);
    Route::put('notifications/read-all', [NotificationController::class, 'markAllAsRead']);

    // Tracking envíos
    Route::apiResource('shipment-trackings', ShipmentTrackingController::class);

    // Métodos de pago
    Route::apiResource('payment-methods', PaymentMethodController::class);

    // Pagos
    Route::apiResource('payments', PaymentController::class);

    // Productos patrocinados
    Route::apiResource('sponsored-products', SponsoredProductController::class);

    // Analytics vendedor
    Route::get('seller-analytics',        [SellerAnalyticController::class, 'index']);
    Route::get('seller-analytics/summary',[SellerAnalyticController::class, 'summary']);

    // Devoluciones
    Route::apiResource('returns', ReturnRequestController::class);

    // Conversaciones
    Route::get('conversations',           [ConversationController::class, 'index']);
    Route::post('conversations',          [ConversationController::class, 'store']);
    Route::get('conversations/{id}',      [ConversationController::class, 'show']);

    // Mensajes
    Route::get('conversations/{id}/messages',  [MessageController::class, 'index']);
    Route::post('conversations/{id}/messages', [MessageController::class, 'store']);

    // Direcciones
    Route::apiResource('addresses', \App\Http\Controllers\Api\AddressController::class);
});
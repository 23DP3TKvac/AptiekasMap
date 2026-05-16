<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\SearchHistoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StatisticsController;

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login',    [AuthController::class, 'login']);
Route::get('/medicines',         [MedicineController::class, 'index']);
Route::get('/medicines/search',  [MedicineController::class, 'search']);
Route::get('/medicines/forms',   [MedicineController::class, 'forms']);
Route::get('/medicines/{id}',    [MedicineController::class, 'show']);
Route::get('/pharmacies',        [PharmacyController::class, 'index']);
Route::get('/pharmacies/{id}',   [PharmacyController::class, 'show']);
Route::get('/statistics',        [StatisticsController::class, 'index']);
Route::get('/availability/{medicine_id}', [AvailabilityController::class, 'byMedicine']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/user',         [AuthController::class, 'user']);
    Route::get('/user/history',  [SearchHistoryController::class, 'index']);
    Route::post('/user/history', [SearchHistoryController::class, 'store']);
    Route::get('/user/notifications',             [NotificationController::class, 'index']);
    Route::patch('/user/notifications/{id}/read', [NotificationController::class, 'markRead']);
    // Admin CRUD
    Route::post('/medicines',        [MedicineController::class, 'store']);
    Route::put('/medicines/{id}',    [MedicineController::class, 'update']);
    Route::delete('/medicines/{id}', [MedicineController::class, 'destroy']);
    Route::get('/admin/users',       [AuthController::class, 'users']);
});

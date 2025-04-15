<?php

use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful as Stateful;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\JobController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\API\UserController;

Route::middleware([Stateful::class])->group(function () {

    /* 公開 API */
    Route::prefix('api')->group(function () {
        Route::get('/jobs',      [JobController::class, 'index']);
        Route::get('/jobs/{id}', [JobController::class, 'show']);
    });

    /* 登入 */
    Route::post('/login', [AuthController::class, 'login']);

    /* 註冊 */
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/me', function (Request $request) {
        if (Auth::check()) {
            return response()->json([
                'isLoggedIn' => true,
                'user' => Auth::user(),
            ]);
        }
    
        return response()->json([
            'isLoggedIn' => false,
            'user' => null,
        ]);
    });

    /* 需要登入 */
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::prefix('api/interviews')->group(function () {
            Route::get('/',       [InterviewController::class, 'index']);
            Route::post('/',      [InterviewController::class, 'store']);
            Route::get('{id}',    [InterviewController::class, 'show']);
            Route::put('{id}',    [InterviewController::class, 'update']);
            Route::delete('{id}', [InterviewController::class, 'destroy']);
        });

        // 用戶管理 
        Route::get('/api/admin/users', [UserController::class, 'index']);
        Route::put('/api/admin/users/{id}', [UserController::class, 'update']); 
        Route::delete('/api/admin/users/{id}', [UserController::class, 'destroy']);
    });
});

/* Vue SPA 入口 */
Route::get('/{any}', fn () => view('app'))->where('any', '.*');

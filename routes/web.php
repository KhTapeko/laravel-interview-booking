<?php

use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful as Stateful;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\JobController;
use App\Http\Controllers\InterviewController;

Route::middleware([Stateful::class])->group(function () {

    /* 公開 API */
    Route::prefix('api')->group(function () {
        Route::get('/jobs',      [JobController::class, 'index']);
        Route::get('/jobs/{id}', [JobController::class, 'show']);
    });

    /* 登入 */
    Route::post('/login', [AuthController::class, 'login']);

    /* 需要登入 */
    Route::middleware('auth')->group(function () {
        Route::get('/me',      [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::prefix('api/interviews')->group(function () {
            Route::get('/',       [InterviewController::class, 'index']);
            Route::post('/',      [InterviewController::class, 'store']);
            Route::get('{id}',    [InterviewController::class, 'show']);
            Route::put('{id}',    [InterviewController::class, 'update']);
            Route::delete('{id}', [InterviewController::class, 'destroy']);
        });
    });
});

/* Vue SPA 入口 */
Route::get('/{any}', fn () => view('app'))->where('any', '.*');

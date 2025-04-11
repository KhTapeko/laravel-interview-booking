<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\JobController;


// ✅ API 路由區塊（prefix + middleware）
Route::middleware('api')->prefix('api')->group(function () {
    Route::get('/jobs', [JobController::class, 'index']);
    Route::get('/jobs/{id}', [JobController::class, 'show']);
});

// ✅ 前端 Vue SPA 路由保留不變
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

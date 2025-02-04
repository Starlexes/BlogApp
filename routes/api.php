<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TranslateController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => 'api'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'get']);
        Route::get('{id}', [UserController::class, 'getById']);
        Route::post('register', [UserController::class, 'register']);
        Route::put('{id}', [UserController::class, 'update']);
        Route::delete('{id}', [UserController::class, 'destroy']);
    });

    Route::group(['prefix' => 'article'], function () {
        Route::get('/', [ArticleController::class, 'get']);
        Route::get('{id}', [ArticleController::class, 'getById']);
        Route::post('/', [ArticleController::class, 'create']);
        Route::put('{id}', [ArticleController::class, 'update']);
        Route::delete('{id}', [ArticleController::class, 'destroy']);
    });

    Route::post('translate', [TranslateController::class, 'translate']);
});

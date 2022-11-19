<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\StatsController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/*
Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);
    return ['token' => $token->plainTextToken];
});
*/

//public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

//protected routes
Route::group(['middleware'=>['auth:sanctum']], function(){
    Route::resource('/tags', TagsController::class);
    Route::get('/posts/trashed', [PostsController::class, 'trashed'])->name('posts.trashed');
    Route::post('/posts/{id}/restore', [PostsController::class, 'restore'])->name('posts.restore');
    Route::resource('/posts', PostsController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/stats', [StatsController::class, 'allStats']);
});

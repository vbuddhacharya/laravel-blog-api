<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\PostCommentController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\PostTagController;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Opt to defining routes myself instead of apiResource in order to have granular control and implement middleware to individual routes

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('admin');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->middleware('admin');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware('admin');

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store'])->middleware('admin'); //only admins can create user manually. others would access register route instead
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('admin'); // only allow admins to delete user for now
    
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update'])->middleware('can:update,post');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->middleware('can:delete,post');

    Route::get('/posts/{post}/comments', [PostCommentController::class, 'index']);
    Route::post('/posts/{post}/comments', [PostCommentController::class, 'store']);

    Route::get('/comments/{comment}', [CommentController::class, 'show']);
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->middleware('can:update,comment');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->middleware('can:delete,comment');

    Route::get('/tags', [TagController::class, 'index']);
    Route::get('/tags/{tag}', [TagController::class, 'show']);
    Route::post('/tags', [TagController::class, 'store'])->middleware('admin');
    Route::put('/tags/{tag}', [TagController::class, 'update'])->middleware('admin');
    Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->middleware('admin');

    Route::get('/posts/{post}/tags', [PostTagController::class, 'index']);
    Route::post('/posts/{post}/tags', [PostTagController::class, 'store'])->middleware('can:update,post');
    Route::delete('/posts/{post}/tags/{tag}', [PostTagController::class, 'destroy'])->middleware('can:update,post');
});

<?php

use App\Http\Controllers\EmbeddingController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::view("/", "welcome");
Route::post("/embedding", [EmbeddingController::class, 'store']);
Route::get("/chat", [MessageController::class, 'index'])->name('chat.index');
Route::post("/chat", [MessageController::class, 'store'])->name('chat.store');
Route::get("/chat/{id}", [MessageController::class, 'show'])->name('chat.show');

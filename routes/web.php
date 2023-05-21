<?php

use App\Http\Controllers\EmbeddingController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome', [
        'source' => 'landing_page'
    ]);
});

Route::post("/embedding", [EmbeddingController::class, 'store']);
Route::post("/chat", [MessageController::class, 'store'])->name('chat.store');
Route::get("/chat/{id}", [MessageController::class, 'index'])->name('chat.show');

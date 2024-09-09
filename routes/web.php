<?php

use App\Models\Item;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authController;
use App\Http\Controllers\gameController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\questController;
use App\Http\Controllers\battleController;
use App\Http\Controllers\inventoryController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/test', [homeController::class, 'index'])->name('home');
Route::get('/test', function () {
    return view('profile', [
        'user' => Auth::user()
    ]);
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [authController::class, 'login'])->name('login');
    Route::get('/register', [authController::class, 'register']);
    Route::post('/register', [authController::class, 'store']);
    Route::post('/login', [authController::class, 'auth']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/quest', [questController::class, 'index'])->name('quest');
    Route::get('/battle', [battleController::class, 'index'])->name('battle');
    Route::get('/inventory', [inventoryController::class, 'index'])->name('inventory');
    Route::get('/home', [homeController::class, 'index'])->name('home');
    Route::get('/logout', [authController::class, 'logout']);
    Route::get('/game/{gameCode}', [gameController::class, 'index']);
    Route::get('/battle/room_list', [battleController::class, 'getRoomList']);
    Route::get('/check-room', [gameController::class, 'checkRoom']);
    Route::get('get-cards', [gameController::class, 'getCards']);
    Route::get('/start-game', [gameController::class, 'getGame']);
    Route::get('/game-status', [gameController::class, 'getGameStatus']);
    Route::post('/create-game', [battleController::class, 'createGame']);
    Route::post('/joinGame/{gameCode}', [battleController::class, 'joinGame']);
    Route::post('/start-game', [gameController::class, 'startGame']);
    Route::post('/update-game-status/{type}', [gameController::class, 'updateGameStatus']);
});

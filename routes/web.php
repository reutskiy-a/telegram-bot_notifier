<?php

use App\Modules\Tgbot\Controllers\TgController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    echo 'qweqweqweqweqweqweqweqweqweqwe';
});


Route::prefix('tg')->group(function () {
    Route::any('', [TgController::class, 'index']);
    Route::any('install', [TgController::class, 'install']);
    Route::any('handle', [TgController::class, 'handle']);
});

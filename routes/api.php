<?php

use App\Http\Controllers\ConfigController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\HomeSliderController;
use App\Http\Controllers\MenuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('V2')->group(function () {
    Route::get('basic_setting', [ConfigController::class, 'setting'])->name('setting');
    Route::get('menu_type', [MenuController::class, 'index'])->name('menu');
    Route::get('get-sliders-by-menu/{menu_id}', [HomeSliderController::class, 'index'])->name('get-sliders-by-menu');
    Route::get('list-genre-by-menu/{menu_id}', [GenreController::class, 'index'])->name('get-list-genre-by-menu');
});

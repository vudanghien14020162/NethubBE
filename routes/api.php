<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\HomeSliderController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NotificationController;
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
Route::prefix('authV1')->group(function (){
    Route::post('signup', [AuthController::class, 'signup'])->name('signup');
});

Route::prefix('V2')->group(function () {
    Route::get('basic_setting', [ConfigController::class, 'setting'])->name('setting');
    Route::get('menu_type', [MenuController::class, 'index'])->name('menu');
    Route::get('get-sliders-by-menu/{menu_id}', [HomeSliderController::class, 'index'])->name('get-sliders-by-menu');
    Route::get('get-genre-by-menu/{menu_id}', [GenreController::class, 'index'])->name('get-list-genre-by-menu');
    Route::get('get-data-by-genre/{genre_id}', [MovieController::class, 'index'])->name('get-data-by-genre');
    Route::get('movie-info/{movie_id}', [MovieController::class, 'movieInfo'])->name('get-movie-info');
    Route::get('link-movie/{movie_id}', [MovieController::class, 'movieLink'])->name('get-movie-link');
    Route::get('all-channel', [MovieController::class, 'allMovie'])->name('get-all-movie');
    Route::get('list-notification', [NotificationController::class, 'listNotification'])->name('list-notification');
    Route::get('count-not-view-notification', [NotificationController::class, 'countNotification'])->name('count-notification');
});

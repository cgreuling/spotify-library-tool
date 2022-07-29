<?php

use App\Http\Controllers\SpotifyController;
use App\Http\Controllers\SpotifyLibraryController;
use Illuminate\Support\Facades\Route;
use League\OAuth2\Client\Provider\GenericProvider;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    echo '<a href="' . route('spotify') . '">Auhorize</a>';
});

Route::get('/spotify', [SpotifyController::class, 'index'])
    ->name('spotify');

Route::get('/spotify/library', [SpotifyLibraryController::class, 'index'])->name('spotify-library.index');
Route::post('/spotify/library/remove-albums', [SpotifyLibraryController::class, 'removeAlbum'])->name('spotify-library.remove-album');

<?php

use App\Http\Controllers\UrlController;
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

Route::group([
    'namespace' => 'v1/statistics/url',
    'prefix' => 'v1/statistics/url',
    'as' => 'statistics.',
], function () {
    Route::post('', [UrlController::class, 'createShortUrl'])->name('create-url');

    Route::get('get/{uid}', [UrlController::class, 'getUrlStatistics'])->name('get-statistics');
});


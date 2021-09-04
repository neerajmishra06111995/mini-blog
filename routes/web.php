<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DailyUpdateEmailController;
use App\Http\Controllers\CountThePairController;
use App\Http\Controllers\ArraySortController;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('blog')->as('blog.')->group(function () {
    Route::get('/index', [BlogController::class, "index"])->name('index');
    Route::post('/show', [BlogController::class, "show"])->name('show');

    Route::post('/create', [BlogController::class, "store"])->name('create');
    Route::post('/edit', [BlogController::class, "edit"])->name('edit');
    Route::post('/update', [BlogController::class, "update"])->name('update');
    Route::post('/delete', [BlogController::class, "delete"])->name('delete');
});

Route::prefix('blog')->as('blog.')->group(function () {
    Route::get('/daily-update', [DailyUpdateEmailController::class, "index"])->name('daily-update');
    Route::get('/send-email', [DailyUpdateEmailController::class, "sendEmail"])->name('send-email');
});

Route::prefix('blog')->as('blog.')->group(function () {
    Route::get('/count-the-pair', [CountThePairController::class, "index"])->name('count-the-pair');
    Route::post('/create-the-pair', [CountThePairController::class, "store"])->name('create-the-pair');
    Route::get('/show-count-pair', [CountThePairController::class, "show"])->name('show-count-pair');
});

Route::prefix('blog')->as('blog.')->group(function () {
    Route::get('/array-sort', [ArraySortController::class, "index"])->name('array-sort');
    Route::post('/create-array-sort', [ArraySortController::class, "store"])->name('create-array-sort');
    Route::get('/show-array-sort', [ArraySortController::class, "show"])->name('show-array-sort');
});

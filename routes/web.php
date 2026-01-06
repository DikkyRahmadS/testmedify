<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MasterItemsController;
use App\Http\Controllers\KategoriController;

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
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
/*
|--------------------------------------------------------------------------
| Master Items
|--------------------------------------------------------------------------
*/
Route::prefix('master-items')->controller(MasterItemsController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/search', 'search');

    Route::get('/form/{method}/{id?}', 'formView');
    Route::post('/form/{method}/{id?}', 'formSubmit');

    Route::get('/view/{kode}', 'singleView');
    Route::get('/delete/{id}', 'delete');

    Route::get('/update-random-data', 'updateRandomData');
    Route::get('/export', 'exportExcel');
});

/*
|--------------------------------------------------------------------------
| Kategori
|--------------------------------------------------------------------------
*/
Route::prefix('kategori')->controller(KategoriController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/search', 'search');

    Route::get('/form/{method}/{id?}', 'formView');
    Route::post('/form/{method}/{id?}', 'formSubmit');

    Route::get('/view/{kode}', 'singleView');
    Route::get('/delete/{id}', 'delete');
    Route::get('/print/{kode}', 'printPdf');
});
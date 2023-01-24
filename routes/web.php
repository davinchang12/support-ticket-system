<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TicketController;

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

Route::redirect('/', '/login');

Auth::routes();

Route::group([
    'middleware' => 'auth',
    'prefix' => 'home',
    'as' => 'home.',
], function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');

    Route::group(['middleware' => 'permission:create tickets'], function () {
        Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    });
    
    Route::resource('/tickets', TicketController::class)->except(['create', 'store', 'destroy']);
});

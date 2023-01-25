<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CategoryController;

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

    Route::group(['middleware' => 'permission:edit tickets'], function () {
        Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    });
    
    Route::resource('/tickets', TicketController::class)->only(['index', 'show']);

    Route::group(['middleware' => 'role:superadmin|admin'], function() {
        Route::resource('/categories', CategoryController::class);
    });
});

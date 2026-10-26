<?php

use App\Http\Controllers\BookingValidationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\SecurityLabController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginSqlLabController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
Route::post('/tickets/{ticket}/mark-paid', [TicketController::class, 'markPaid'])->name('tickets.mark-paid');
Route::post('/tickets/{ticket}/send', [TicketController::class, 'send'])->name('tickets.send');

Route::get('/validate', [BookingValidationController::class, 'form'])->name('validation.form');
Route::post('/validate', [BookingValidationController::class, 'validateBooking'])->name('validation.submit');
Route::post('/tickets/{ticket}/check-in', [BookingValidationController::class, 'checkIn'])->name('tickets.check-in');

Route::get('/gateway', [GatewayController::class, 'index'])->name('gateway.index');

Route::get('/lab/login', [LoginSqlLabController::class, 'index'])
    ->name('lab.login');

Route::post('/lab/login/simulate', [LoginSqlLabController::class, 'simulate'])
    ->name('lab.login.simulate');

// Teaching simulations are deliberately limited to APP_ENV=local and loopback requests.
Route::get('/lab', [SecurityLabController::class, 'index'])->name('lab.index');
Route::post('/lab/sql-simulation', [SecurityLabController::class, 'sqlSimulation'])->name('lab.sql');
Route::post('/lab/command-simulation', [SecurityLabController::class, 'commandSimulation'])->name('lab.command');

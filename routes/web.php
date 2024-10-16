<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/data', [AppointmentController::class, 'getAppointmentsData'])->name('appointments.data');
    Route::get('book/appointments', [AppointmentController::class, 'createAppointment'])->name('create.appointments');
    Route::post('store/appointments', [AppointmentController::class, 'storeAppointment']);
    Route::get('/appointments/{id}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{id}', [AppointmentController::class, 'updateAppointmentStatus'])->name('appointments.update');;
    Route::post('/appointments/approve/{id}', [AppointmentController::class, 'approveAppointment'])->name('appointments.approve');
    Route::post('/appointments/postpone/{id}', [AppointmentController::class, 'postponeAppointment'])->name('appointments.postpone');
    Route::post('/appointments/cancel/{id}', [AppointmentController::class, 'cancelAppointment'])->name('appointments.cancel');
    Route::post('/appointments/reject/{id}', [AppointmentController::class, 'rejectAppointment'])->name('appointments.reject');
});

require __DIR__.'/auth.php';

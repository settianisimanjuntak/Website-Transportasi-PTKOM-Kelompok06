<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\BusController as AdminBusController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth (guest)
|--------------------------------------------------------------------------
*/

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1')->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.attempt');

    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::get('/auth/google', [SocialLoginController::class, 'redirect'])->name('google.mock');

/*
|--------------------------------------------------------------------------
| Public: pencarian & detail jadwal
|--------------------------------------------------------------------------
*/

Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/results', [SearchController::class, 'results'])->name('results');
Route::get('/schedules/{schedule}', [ScheduleController::class, 'show'])->name('schedules.show');

/*
|--------------------------------------------------------------------------
| Authenticated: booking → pembayaran → e-tiket → profil
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/schedules/{schedule}/seat', [BookingController::class, 'seat'])->name('schedules.seat');
    Route::post('/schedules/{schedule}/bookings', [BookingController::class, 'store'])->name('bookings.store');

    Route::get('/bookings/{booking}/payment', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/bookings/{booking}/payment/simulate', [PaymentController::class, 'simulate'])->name('payment.simulate');
    Route::get('/bookings/{booking}/payment-status', [PaymentController::class, 'status'])->name('payment.status');
    Route::get('/bookings/{booking}/tickets', [TicketController::class, 'index'])->name('tickets.index');

    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/pdf', [TicketController::class, 'pdf'])->name('tickets.pdf');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/buses', [AdminBusController::class, 'index'])->name('buses.index');
    Route::get('/buses/create', [AdminBusController::class, 'create'])->name('buses.create');
    Route::post('/buses', [AdminBusController::class, 'store'])->name('buses.store');
    Route::get('/buses/{bus}/edit', [AdminBusController::class, 'edit'])->name('buses.edit');
    Route::put('/buses/{bus}', [AdminBusController::class, 'update'])->name('buses.update');
    Route::delete('/buses/{bus}', [AdminBusController::class, 'destroy'])->name('buses.destroy');

    Route::get('/schedules', [AdminScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/create', [AdminScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/schedules', [AdminScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/schedules/{schedule}', [AdminScheduleController::class, 'show'])->name('schedules.show');
    Route::get('/schedules/{schedule}/edit', [AdminScheduleController::class, 'edit'])->name('schedules.edit');
    Route::put('/schedules/{schedule}', [AdminScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [AdminScheduleController::class, 'destroy'])->name('schedules.destroy');

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');

    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile.index');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AdminProfileController::class, 'changePassword'])->name('profile.password');
});

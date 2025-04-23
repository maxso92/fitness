<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\CustomRegisterController;

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
/*
Route::get('/', function () {
    return view('index');
});

Route::get('/index', function () {
    return view('index');
})->name('index');
*/



Route::redirect('/', '/login');
Route::redirect('/index', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/register', [CustomRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [CustomRegisterController::class, 'register']);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard/', \App\Http\Livewire\DashboardStats::class)->name('dashboard');
    Route::get('/partners/', \App\Http\Livewire\Partners::class)->name('partners');
    Route::post('/user/activity', [UserController::class,  'updateUserActivity'])->name('user.activity.update');
});



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::post('/trainings/store', [\App\Http\Controllers\TrainingController::class, 'store'])->name('trainings.store');
    Route::get('/gyms', \App\Http\Livewire\GymCrud::class)->name('gyms');
    Route::get('/schedules', \App\Http\Livewire\TrainingSchedule::class)->name('schedules');
    Route::get('/subscriptions', \App\Http\Livewire\SubscriptionCrud::class)->name('subscriptions');
    Route::get('/users/{user}/download-qr', [UserController::class, 'downloadQr'])
        ->name('users.download-qr')
        ->where('user', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');


    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::get('/users/{id}/view', [UserController::class, 'view'])->name('users.view');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    // Route::get('/users/{id}', [UserController::class, 'view'])->name('users.view');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::put('/users/toggle-block/{id}', [UserController::class, 'toggleBlock'])->name('users.toggleBlock');

    Route::delete('/users/soft-delete/{user}', [UserController::class, 'softDelete'])
        ->name('users.softDelete');

    Route::put('/users/restore/{user}', [UserController::class, 'restore'])
        ->name('users.restore');

    Route::get('/scan-qr', [UserController::class, 'showScanForm'])->name('scan.qr.form');
    Route::post('/scan-qr', [UserController::class, 'processScan'])->name('scan.qr.process');
    Route::post('/visits/{user}/allow', [UserController::class, 'allowEntry'])
        ->name('visits.allow');

});




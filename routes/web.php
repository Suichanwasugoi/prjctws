<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::prefix('api')->group(function () {
    Route::delete('/events/{id}', [EventsController::class, 'destroy']);
    Route::get('/events', [EventsController::class, 'index']);
    Route::get('/events/monthly-stats', [EventsController::class, 'monthlyStats']);
    Route::get('/users', [UsersController::class, 'index']);
});
Route::get('/events', [EventsController::class, 'index'])->name('events.index');
Route::get('/eventscreate', [EventsController::class, 'create']);
Route::post('/events', [EventsController::class, 'store']);
Route::get('/events/{id}/edit', [EventsController::class, 'edit']);
Route::put('/events/{id}', [EventsController::class, 'update']);
Route::delete('/events/{id}', [EventsController::class, 'destroy']);



Route::get('password/forgot', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('password/email', [PasswordResetLinkController::class, 'store'])->name('password.email');

Route::get('password/secret', function () {
    return Inertia::render('Auth/SecretKeyInput');
})->name('password.secret.view');

Route::post('password/secret', [PasswordResetLinkController::class, 'verifySecretKey'])->name('password.secret');

Route::get('/eventscreate', function () {
 return Inertia:: render('EventsCreate');
});
Route::get('/events', function () {
    return Inertia::render('Events');
});

Route::get('/events', function () {
    return Inertia::render('Events');
})->name('events.index');


Route::get('/users/create', function () {
    return Inertia:: render('UsersCreate');
   });

    Route::get('/users/{id}/edit', [UsersController::class, 'edit']);
    Route::put('/users/{id}', [UsersController::class, 'update']);


   

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::get('/users', function () {
        return Inertia::render('Users');
    })->name('users.index');
    
     // Route for Users Edit Page
     Route::get('/users/{id}/edit', [UsersController::class, 'edit']);

     // Route for Updating Users
     Route::put('/users/{id}', [UsersController::class, 'update']);
 
     // Route for Deleting Users
     Route::delete('/users/{id}', [UsersController::class, 'destroy']);
 
     // Route for Logging out Users
     Route::post('/users/{id}/logout', [UsersController::class, 'logout']);
 
     // Route for Users Create Page
     Route::get('/users/create', function () {
         return Inertia::render('UsersCreate');
     });
 
     // Route for Creating Users
     Route::post('/users', [UsersController::class, 'create']);
 });





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

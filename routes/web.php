<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('/auth/login');
});


// Rutas para las tareas
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [TaskController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/task', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/dashboard/task', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/dashboard/task/{id}/edit',[TaskController::class,'edit'])->name('tasks.edit');
    Route::put('/dashboard/task/{id}', [TaskController::class,'update'])->name('tasks.update');
    Route::delete('/dashboard/task/{id}', [TaskController::class,'destroy'])->name('tasks.destroy');
});

require __DIR__.'/auth.php';

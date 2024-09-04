<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DepartmentController;


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
    return view('auth.login');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [ProjectController::class, 'index'])->name('dashboard');
    Route::get('/logs/{id}', [ProjectController::class, 'logs'])->name('logs');
    Route::resource('projects', ProjectController::class);
    Route::resource('tasks', TaskController::class)->except(['show']);
    Route::resource('departments', DepartmentController::class);
    Route::get('departments/{id}/users', [DepartmentController::class, 'show'])->name('departments.users');
    Route::patch('/projects/{project}/update-status', [ProjectController::class, 'updateStatus'])->name('projects.updateStatus');
    Route::put('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::get('/staff',[StaffController::class , 'index'])->name('staff');
    Route::get('/staff/create',[StaffController::class , 'create'])->name('staff.create');
    Route::post('/staff/create',[StaffController::class , 'store'])->name('staff.store');
    Route::get('/role',[RoleController::class , 'index'])->name('role');
    Route::post('/role/create',[RoleController::class , 'store'])->name('role.store');
    Route::delete('/staff/{user}',[StaffController::class,'destroy'])->name('staff.destroy');
    Route::delete('/role/{role}',[RoleController::class,'destroy'])->name('role.destroy');
    Route::get('/roles/{role}/users', [RoleController::class, 'getUsers']);
    Route::get('/staff/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::get('/staff/track/{id}', [StaffController::class, 'track'])->name('staff.track');
    Route::get('/tasks/search', [TaskController::class, 'search'])->name('tasks.search');


});

require __DIR__.'/auth.php';

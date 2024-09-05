<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ListItemController;


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
    Route::get('departments/{department}/users', [DepartmentController::class, 'getUsersByDepartment']);
    Route::get('/departments/{id}/users', [DepartmentController::class, 'getUsers']);

    Route::get('/staff/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::get('/staff/track/{id}', [StaffController::class, 'track'])->name('staff.track');
    Route::get('/tasks/search', [TaskController::class, 'search'])->name('tasks.search');
    Route::get('/log/{id}' , [ProjectController::class , 'clear'])->name('log.clear');

    Route::get('/tasks/{task}/list-items', [ListItemController::class, 'index']);
    Route::post('/tasks/{task}/list-items', [ListItemController::class, 'store']);
    Route::patch('/list-items/{listItem}', [ListItemController::class, 'update']);
    Route::post('/tasks/{task}/comments', [TaskController::class, 'storeComment'])->name('tasks.comments.store');
    Route::get('/tasks/{task}/comments', [TaskController::class, 'getComments']);
    

   // Project Routes
Route::post('/projects/{project}/upload-files', [ProjectController::class, 'uploadFiles'])->name('projects.uploadFiles');
Route::post('/projects/{project}/upload-photos', [ProjectController::class, 'uploadPhotos'])->name('projects.uploadPhotos');
Route::get('/projects/download-file/{filename}', [ProjectController::class, 'downloadFile'])->name('projects.downloadFile');
Route::get('/projects/download-photo/{photoname}', [ProjectController::class, 'downloadPhoto'])->name('projects.downloadPhoto');

// Task Routes
Route::post('/tasks/{task}/upload-files', [TaskController::class, 'uploadFiles'])->name('tasks.uploadFiles');
Route::post('/tasks/{task}/upload-photos', [TaskController::class, 'uploadPhotos'])->name('tasks.uploadPhotos');
Route::get('/tasks/download-file/{filename}', [TaskController::class, 'downloadFile'])->name('tasks.downloadFile');
Route::get('/tasks/download-photo/{photoname}', [TaskController::class, 'downloadPhoto'])->name('tasks.downloadPhoto');

});

require __DIR__.'/auth.php';

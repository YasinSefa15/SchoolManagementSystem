<?php

use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\FacultyController;
use App\Http\Controllers\Api\LectureController;
use App\Http\Controllers\Api\OfferedLectureController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\SupervisorController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserToLectureController;
use App\Http\Controllers\Api\UserTypeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Module\ModuleController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('login',[LoginController::class,'read'])->name('user.login');

Route::controller(ModuleController::class)->name('module.')->prefix('modules')->group(function () {
    Route::get('/create', 'create')->name('create');
    Route::get('', 'read')->name('read');
    Route::get('/update/{id}', 'update')->name('update');
    Route::get('/delete/{id}', 'delete')->name('delete');
    Route::get('/{id}', 'view')->name('view');
});

Route::controller(UserController::class)->name('user.')->prefix('users')->group(function () {
    Route::get('/create', 'create')->name('create');
    Route::get('', 'read')->name('read');
    Route::get('/update/{id}', 'update')->name('update');
    Route::get('view/{id}', 'view')->name('view');
});

Route::controller(FacultyController::class)->name('faculty.')->prefix('faculties')->group(function () {
    Route::get('/create', 'create')->name('create');
    Route::get('', 'read')->name('read');
    Route::get('/update/{id}', 'update')->name('update');
    Route::get('view/{id}', 'view')->name('view');
});
Route::controller(DepartmentController::class)->name('department.')->prefix('departments')->group(function () {
    Route::get('/create', 'create')->name('create');
    Route::get('', 'read')->name('read');
    Route::get('/update/{id}', 'update')->name('update');
    Route::get('view/{id}', 'view')->name('view');
});
//->middleware('confirm')
Route::controller(LectureController::class)->name('lecture.')->prefix('lectures')->group(function () {
    Route::get('/create', 'create')->name('create');
    Route::get('', 'read')->name('read');
    Route::get('/update/{id}', 'update')->name('update');
    Route::get('view/{id}', 'view')->name('view');
});

Route::controller(OfferedLectureController::class)->name('offeredlecture.')->prefix('lecture/offered')->group(function () {
    Route::get('/create', 'create')->name('create'); //kullanmadım şimdilik
    Route::get('', 'read')->name('read');
    Route::get('/update', 'update')->name('update');
    Route::get('/view/{id}','view')->name('view'); // o kullanıcının alabileceği dersler listenmeli
});  //->middleware('confirm')

//user route u içerisinden yapıyoruz zaten bunu. Ve buradaki tüm işlemlere gerek yok en nihayetinde.
Route::controller(UserTypeController::class)->name('user-type.')->prefix('user-type')->group(function () {
    Route::get('/create', 'create')->name('create');
    Route::get('/update/{id}', 'update')->name('update');
    Route::get('/delete/{id}', 'delete')->name('delete');
    Route::get('view/{id}', 'view')->name('view');
});

//gözden geçirilecek
Route::controller(SupervisorController::class)->name('supervisor.')->prefix('supervisors')->group(function () {
    Route::get('/create', 'create')->name('create');
    Route::get('', 'read')->name('read');
    Route::get('/update/{id}', 'update')->name('update');
    Route::get('/delete/{id}', 'delete')->name('delete');
    Route::get('view/{id}', 'view')->name('view');
});


Route::controller(UserToLectureController::class)->name('user-to-lecture.')->prefix('lecture/user')->group(function () {
    Route::get('/create', 'create')->name('create');
    Route::get('/update', 'update')->name('update');
    Route::get('/delete', 'delete')->name('delete');
});
//??
//Route::controller(UserToLectureController::class)->middleware('confirm')->name('user-to-lecture.')->prefix('user-to-lectures')->group(function () {
//    Route::get('/create', 'create')->name('create');
//    Route::get('view/{user_id}', 'view')->name('view');
//    Route::get('/delete/lecture/{lecture_id}/user/{user_id}', 'delete')->name('delete');
//    Route::get('/update/lecture/{lecture_id}/user/{user_id}', 'update')->name('update');
//});


<?php

use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\FacultyController;
use App\Http\Controllers\Api\LectureController;
use App\Http\Controllers\Api\LectureToExamController;
use App\Http\Controllers\Api\OfferedLectureController;
use App\Http\Controllers\Api\SupervisorController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserToGradeController;
use App\Http\Controllers\Api\UserToLectureController;
use App\Http\Controllers\Api\UserToLetterGradeController;
use App\Http\Controllers\Api\UserTypeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Module\ModuleController;
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

Route::controller(LectureController::class)->name('lecture.')->prefix('lectures')->group(function () {
    Route::get('/create', 'create')->name('create');
    Route::get('', 'read')->name('read'); //sunulan dersler
    Route::get('/update/{id}', 'update')->name('update');
    Route::get('view/{id}', 'view')->name('view'); //hoca görüntüleyecek. Dersi alanlar, sınavlar
});

Route::controller(OfferedLectureController::class)->name('offeredlecture.')->prefix('lecture/offered')->group(function () {
    Route::get('/create', 'create')->name('create');
    Route::get('', 'read')->name('read');
    Route::get('/update', 'update')->name('update');
    Route::get('/show','show')->name('show'); // o kullanıcının alabileceği dersler listenmeli
    Route::get('/view/{id}','view')->name('view'); // o kullanıcının onaya gönderdiği dersler
});  //->middleware('confirm')

//kullanıcının aldığı dersleri göstersin
Route::controller(UserToLectureController::class)->name('user-to-lecture.')->prefix('user-to-lecture')->group(function () {
    Route::get('/create', 'create')->name('create'); //approving demand
    Route::get('/update', 'update')->name('update'); //updating status
    Route::get('/delete', 'delete')->name('delete'); //dropping lecture
});

//gözden geçirilecek
Route::controller(SupervisorController::class)->name('supervisor.')->prefix('supervisors')->group(function () {
    Route::get('/create', 'create')->name('create');
    Route::get('', 'read')->name('read');
    Route::get('/update', 'update')->name('update');
    Route::get('view/{id}', 'view')->name('view');
});

Route::controller(LectureToExamController::class)->name('lecture-to-exam.')->prefix('lecture-to-exam')->group(function () {
    Route::get('/create', 'create')->name('create'); //sınav oluşturur
    Route::get('/delete/{id}', 'delete')->name('delete'); //sınav siler
    Route::get('{id}', 'read')->name('read'); //verilen hocaya ait tüm dersler sınavlarla birlikte gösterilir
    Route::get('/update/{id}', 'update')->name('update'); //verilen id deli exami günceller
    Route::get('view/{id}', 'view')->name('view'); //o dersin examlerini gösterir
});

//bazı routelar ileride kullanılmayabilir.
Route::controller(UserTypeController::class)->name('user-type.')->prefix('user-type')->group(function () {
    Route::get('/create', 'create')->name('create');
    Route::get('/update/{id}', 'update')->name('update');
    Route::get('/delete/{id}', 'delete')->name('delete');
    Route::get('view/{id}', 'view')->name('view');
});

Route::controller(UserToGradeController::class)->name('user-to-grade.')->prefix('user-to-grade')->group(function () {
    Route::get('/create', 'create')->name('create'); //kullanıcıya not girişi yapılır
    Route::get('/update', 'update')->name('update'); //kullanıcının notu düzenler
//    Route::get('/delete/{id}', 'delete')->name('delete'); kullanılmadı
//    Route::get('view/{id}', 'view')->name('view');
});

Route::controller(UserToLetterGradeController::class)->name('letter-grade.')->prefix('letter-grade')->group(function () {
    Route::get('/create', 'create')->name('create');
    Route::get('/update', 'update')->name('update');
});


//??
//Route::controller(UserToLectureController::class)->middleware('confirm')->name('user-to-lecture.')->prefix('user-to-lectures')->group(function () {
//    Route::get('/create', 'create')->name('create');
//    Route::get('view/{user_id}', 'view')->name('view');
//    Route::get('/delete/lecture/{lecture_id}/user/{user_id}', 'delete')->name('delete');
//    Route::get('/update/lecture/{lecture_id}/user/{user_id}', 'update')->name('update');
//});


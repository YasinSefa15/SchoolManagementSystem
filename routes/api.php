<?php

use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\FacultyController;
use App\Http\Controllers\Api\LectureController;
use App\Http\Controllers\Api\LectureToExamController;
use App\Http\Controllers\Api\OfferedLectureController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\StudentToSupervisorController;
use App\Http\Controllers\Api\SupervisorController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserToGradeController;
use App\Http\Controllers\Api\UserToLectureController;
use App\Http\Controllers\Api\UserToLetterGradeController;
use App\Http\Controllers\Api\UserTypeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Module\ModuleController;
use App\Http\Controllers\NoteController;
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
Route::get('login',[LoginController::class,'read'])
    ->middleware('guest')
    ->name('user.login');

Route::controller(PermissionController::class)
    ->middleware('authenticated')
    ->name('permission.')
    ->prefix('permissions')
    ->group(function () {
        Route::get('', 'read')->name('read');
        Route::get('/create', 'create')->name('create');
        Route::get('/update', 'update')->name('update');
        Route::get('/delete', 'delete')->name('delete');
    });


Route::controller(ModuleController::class)
    ->middleware('authenticated')
    ->name('module.')
    ->prefix('modules')
    ->group(function () {
        Route::get('/create', 'create')->name('create');
});

Route::controller(UserTypeController::class)
    ->middleware('authenticated')
    ->name('user-type.')
    ->prefix('user-type')
    ->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::get('', 'read')->name('read');
        Route::get('/update/{id}', 'update')->name('update');
});

Route::controller(UserController::class)
    ->middleware('authenticated')
    ->name('user.')
    ->prefix('users')
    ->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::get('', 'read')->name('read');
        Route::get('/update/{id}', 'update')->name('update');
        Route::get('view/{id}', 'view')->name('view');
});

Route::controller(FacultyController::class)
    ->middleware('authenticated')
    ->name('faculty.')
    ->prefix('faculties')
    ->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::get('', 'read')->name('read');
        Route::get('/update/{id}', 'update')->name('update');
        Route::get('view/{id}', 'view')->name('view');
});
Route::controller(DepartmentController::class)
    ->middleware('authenticated')
    ->name('department.')
    ->prefix('departments')
    ->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::get('', 'read')->name('read');
        Route::get('/update/{id}', 'update')->name('update');
        Route::get('view/{id}', 'view')->name('view');
});

Route::controller(SupervisorController::class)
    ->middleware('authenticated')
    ->name('supervisor.')
    ->prefix('supervisors')
    ->group(function () {
        Route::get('/create', 'create')->name('create');//creates supervisors
        Route::get('', 'read')->name('read'); //read the given department_id supervisors
        Route::get('/update', 'update')->name('update');
        Route::get('view/{id}', 'view')->name('view');//supervisor with students
});

Route::controller(StudentToSupervisorController::class)
    ->middleware('authenticated')
    ->name('user-to-supervisor.')
    ->prefix('user-to-supervisor')
    ->group(function () {
        Route::get('', 'read')->name('read');
        Route::get('/update', 'update')->name('update');//updating student's supervisor
});

Route::controller(OfferedLectureController::class)
    ->middleware('authenticated')
    ->name('offeredlecture.')
    ->prefix('lectures-offered')
    ->group(function () {
        Route::get('/create', 'create')->name('create'); //creates the rolling time of lectures
        Route::get('', 'read')->name('read'); //lists the lectures' user can choose
        Route::get('show', 'show')->name('show'); //lists the offered lectures in admin panel
        Route::get('/update', 'update')->name('update'); //updates the rolling time of lectures
        Route::get('/view/{id}','view')->name('view'); //lists the lectures' user chose
});

Route::controller(LectureController::class)
    ->middleware('authenticated')
    ->name('lecture.')
    ->prefix('lectures')
    ->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::get('', 'read')->name('read'); //lists the all offered lectures
        Route::get('/update/{id}', 'update')->name('update');
        Route::get('/{lecture_id}', 'view')->name('view');//lists exams-users for lecture for lecturers
});

Route::controller(LectureToExamController::class)
    ->middleware('authenticated')
    ->name('exam.')
    ->prefix('lectures/{lecture_id}/exams')
    ->group(function () {
        Route::get('/create', 'create')->name('create'); //creates exams
        Route::get('/delete/{exam_id}', 'delete')->name('delete');
        Route::get('', 'read')->name('read'); //lecturer sees all lectures belongs s/he
        Route::get('/update/{exam_id}', 'update')->name('update'); //updates given exam_id exam
});

Route::controller(UserToLectureController::class)
    ->middleware('authenticated')
    ->name('user-to-lecture.')
    ->prefix('user-to-lecture')
    ->group(function () {
        Route::get('/create', 'create')->name('create'); //demanding lecture ++student can access
        Route::get('/update/{lecture_id}', 'update')->name('update'); //updating status ++lecturer can access
        Route::get('/read', 'read')->name('read'); //shows users lecture status for supervisors
});

Route::controller(UserToGradeController::class)
    ->middleware('authenticated')
    ->name('user-to-grade.')->prefix('user-to-grade')->group(function () {
    Route::get('/create', 'create')->name('create'); //lecturer enters note to user
    Route::get('/update', 'update')->name('update');
});

Route::controller(UserToLetterGradeController::class)
    ->middleware('authenticated')
    ->name('letter-grade.')
    ->prefix('letter-grade')
    ->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::get('/update', 'update')->name('update');
});

Route::get('/users/{user_id}/note',[NoteController::class,'read'])
    ->middleware('authenticated')
    ->name('note.read');

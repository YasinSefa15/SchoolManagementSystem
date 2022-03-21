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
Route::any('login',[LoginController::class,'read'])
    ->middleware('guest')
    ->name('login');


Route::controller(PermissionController::class)
    ->middleware('authenticated')
    ->name('permission.')
    ->prefix('permissions')
    ->group(function () {
        Route::any('', 'read')->name('read');
        Route::any('/create', 'create')->name('create');
        Route::any('/update', 'update')->name('update');
        Route::any('/delete', 'delete')->name('delete');
    });


Route::controller(ModuleController::class)
    ->middleware('authenticated')
    ->name('module.')
    ->prefix('modules')
    ->group(function () {
        Route::any('/create', 'create')->name('create');
});

Route::controller(UserTypeController::class)
    ->middleware('authenticated')
    ->name('user-type.')
    ->prefix('user-type')
    ->group(function () {
        Route::any('/create', 'create')->name('create');
        Route::any('', 'read')->name('read');
        Route::any('/update/{id}', 'update')->name('update');
});

Route::controller(UserController::class)
    ->middleware('authenticated')
    ->name('user.')
    ->prefix('users')
    ->group(function () {
        Route::any('/create', 'create')->name('create');
        Route::any('', 'read')->name('read');
        Route::any('/update/{id}', 'update')->name('update');
        Route::any('view/{id}', 'view')->name('view');
});

Route::controller(FacultyController::class)
    ->middleware('authenticated')
    ->name('faculty.')
    ->prefix('faculties')
    ->group(function () {
        Route::any('/create', 'create')->name('create');
        Route::any('', 'read')->name('read');
        Route::any('/update/{id}', 'update')->name('update');
        Route::any('view/{id}', 'view')->name('view');
});
Route::controller(DepartmentController::class)
    ->middleware('authenticated')
    ->name('department.')
    ->prefix('departments')
    ->group(function () {
        Route::any('/create', 'create')->name('create');
        Route::any('', 'read')->name('read');
        Route::any('/update/{id}', 'update')->name('update');
        Route::any('view/{id}', 'view')->name('view');
});

Route::controller(SupervisorController::class)
    ->middleware('authenticated')
    ->name('supervisor.')
    ->prefix('supervisors')
    ->group(function () {
        Route::any('/create', 'create')->name('create');//creates supervisors
        Route::any('', 'read')->name('read'); //read the given department_id supervisors
        Route::any('/update', 'update')->name('update');
        Route::any('view/{id}', 'view')->name('view');//supervisor with students
});

Route::controller(StudentToSupervisorController::class)
    ->middleware('authenticated')
    ->name('user-to-supervisor.')
    ->prefix('user-to-supervisor')
    ->group(function () {
        Route::any('', 'read')->name('read');
        Route::any('/update', 'update')->name('update');//updating student's supervisor
});

Route::controller(OfferedLectureController::class)
    ->middleware('authenticated')
    ->name('offeredlecture.')
    ->prefix('lectures-offered')
    ->group(function () {
        Route::any('/create', 'create')->name('create'); //creates the rolling time of lectures
        Route::any('', 'read')->name('read'); //lists the lectures' user can choose
        Route::any('show', 'show')->name('show'); //lists the offered lectures in admin panel
        Route::any('/update', 'update')->name('update'); //updates the rolling time of lectures
        Route::any('/view/{id}','view')->name('view'); //lists the lectures' user chose
});

Route::controller(LectureController::class)
    ->middleware('authenticated')
    ->name('lecture.')
    ->prefix('lectures')
    ->group(function () {
        Route::any('/create', 'create')->name('create');
        Route::any('', 'read')->name('read'); //lists the all offered lectures
        Route::any('/update/{id}', 'update')->name('update');
        Route::any('/{lecture_id}', 'view')->name('view');//lists exams-users for lecture for lecturers
});

Route::controller(LectureToExamController::class)
    ->middleware('authenticated')
    ->name('exam.')
    ->prefix('lectures/{lecture_id}/exams')
    ->group(function () {
        Route::any('/create', 'create')->name('create'); //creates exams
        Route::any('/delete/{exam_id}', 'delete')->name('delete');
        Route::any('', 'read')->name('read'); //lecturer sees all lectures belongs s/he
        Route::any('/update/{exam_id}', 'update')->name('update'); //updates given exam_id exam
});

Route::controller(UserToLectureController::class)
    ->middleware('authenticated')
    ->name('user-to-lecture.')
    ->prefix('user-to-lecture')
    ->group(function () {
        Route::any('/create', 'create')->name('create'); //demanding lecture ++student can access
        Route::any('/update/{lecture_id}', 'update')->name('update'); //updating status ++lecturer can access
        Route::any('/read', 'read')->name('read'); //shows users lecture status for supervisors
});

Route::controller(UserToGradeController::class)
    ->middleware('authenticated')
    ->name('user-to-grade.')->prefix('user-to-grade')->group(function () {
    Route::any('/create', 'create')->name('create'); //lecturer enters note to user
    Route::any('/update', 'update')->name('update');
});

Route::controller(UserToLetterGradeController::class)
    ->middleware('authenticated')
    ->name('letter-grade.')
    ->prefix('letter-grade')
    ->group(function () {
        Route::any('/create', 'create')->name('create');
        Route::any('/update', 'update')->name('update');
});

Route::get('/users/{user_id}/note',[NoteController::class,'read'])
    ->middleware('authenticated')
    ->name('note.read');

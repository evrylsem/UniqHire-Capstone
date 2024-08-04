<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PwdController;
use App\Http\Controllers\UserInfoController;
use App\Http\Controllers\NotificationController;
use App\Models\UserInfo;
use App\Models\TrainingProgram;
use Illuminate\Support\Facades\Route;



Route::get('/login', [AuthController::class, 'showLogin'])->name('login-page');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/forgot-password', [AuthController::class, 'showForgotPass'])->name('forgot-password');


Route::get('/register', [AuthController::class, 'showRegistration'])->name('register-form');
Route::post('/register', [AuthController::class, 'register']);


Route::get('/logout', [AuthController::class, 'logout']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/home', [AuthController::class, 'showHomePage'])->name('home');
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'editProfile'])->name('edit-profile');
    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.getNotifications');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::get('/show-program/{id}', [AgencyController::class, 'showProgramDetails'])->name('programs-show');
    
    Route::get('/user/{id}', [AgencyController::class, 'showEnrolleeProfile'])->name('show-profile');


    //Admin Middleware
    Route::get('/pwd/all', [AdminController::class, 'showPwds'])->middleware('role:Admin')->name('pwd-list');
    Route::get('/training-agency/all', [AdminController::class, 'showTrainers'])->middleware('role:Admin')->name('trainer-list');
    Route::get('/employee/all', [AdminController::class, 'showEmployers'])->middleware('role:Admin')->name('employee-list');
    Route::get('/sponsor/all', [AdminController::class, 'showSponsors'])->middleware('role:Admin')->name('sponsor-list');


    //Trainer Middleware
    Route::get('/manage-program', [AgencyController::class, 'showPrograms'])->middleware('role:Trainer')->name('programs-manage');
    Route::get('/add-program', [AgencyController::class, 'showAddForm'])->middleware('role:Trainer')->name('programs-add');
    Route::post('/add-program', [AgencyController::class, 'addProgram'])->middleware('role:Trainer');
    Route::get('/show-program/{id}', [AgencyController::class, 'showProgramDetails'])->middleware('role:Trainer')->name('programs-show');
    Route::delete('/delete-program/{id}', [AgencyController::class, 'deleteProgram'])->middleware('role:Trainer')->name('programs-delete');
    Route::get('/edit-program/{id}', [AgencyController::class, 'editProgram'])->middleware('role:Trainer')->name('programs-edit');
    Route::put('/edit-program/{id}', [AgencyController::class, 'updateProgram'])->middleware('role:Trainer');
    Route::get('/agency/calendar', [AgencyController::class, 'showCalendar'])->middleware('role:Trainer')->name('agency-calendar');
    // Route::post('/agency/action', [AgencyController::class, 'action'])->middleware('role:Trainer')->name('agency-action');
    Route::post('/agency/accept', [AgencyController::class, 'accept'])->middleware('role:Trainer')->name('agency-accept');    



    // PWD Middleware
    Route::get('/browse/training-programs', [PwdController::class, 'showPrograms'])->middleware('role:PWD')->name('pwd-list-program');
    Route::get('/training-details/{id}', [PwdController::class, 'showDetails'])->middleware('role:PWD')->name('training-details');
    Route::post('/training-details/{id}', [PwdController::class, 'showDetails'])->middleware('role:PWD')->name('training-details');
    Route::get('/pwd/calendar', [PwdController::class, 'showCalendar'])->middleware('role:PWD')->name('pwd-calendar');
    // Route::post('/pwd/action', [PwdController::class, 'action'])->middleware('role:PWD')->name('pwd-action');
    Route::post('/training-program/apply', [PwdController::class, 'application'])->middleware('role:PWD')->name('pwd-application');
    Route::get('/training-programs', [PwdController::class, 'showTrainings'])->middleware('role:PWD')->name('trainings');
    Route::get('/training-program/{id}', [PwdController::class, 'showDetails'])->middleware('role:PWD')->name('show-details');
    Route::post('/training-program/rate', [PwdController::class, 'rateProgram'])->middleware('role:PWD')->name('rate-program');
});
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\SupportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// routes/web.php
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::match(['patch','put'], '/profile', [ProfileController::class, 'update'])->name('profile.update'); // ← add put
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

//AboutUs
Route::get('/about-us', function () {
    return view('aboutUs.index');
})->name('about');





      Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

      //Help Center
Route::view('/support', 'support.index')->name('support');

Route::post('/support', [SupportController::class, 'submit'])
    ->name('support.submit');


    // stage view (map of levels + pre/post buttons)
    Route::get('/stages/{stage}', [StageController::class,'show'])->name('stages.show');

    // assessments
    Route::get('/assessments/{assessment}', [AssessmentController::class,'show'])->name('assessments.show');
    Route::post('/assessments/{assessment}', [AssessmentController::class,'submit'])->name('assessments.submit');

    // levels
    Route::get('/levels/{level}', [LevelController::class,'show'])->name('levels.show');
    Route::post('/levels/{level}', [LevelController::class,'submit'])->name('levels.submit');
Route::get('/levels/{level}/fill', [LevelController::class, 'fill'])->name('levels.fill');




    Route::get('/stages/{stage}/enter', [StageController::class, 'enter'])->name('stages.enter');
});

Route::view('/contact', 'contact')->name('contact');
require __DIR__.'/auth.php';

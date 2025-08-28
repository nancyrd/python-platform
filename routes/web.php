<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\Admin\AdminStageController;
use App\Http\Controllers\Admin\AdminLevelController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminAssessmentController;
use App\Models\Assessment;

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
Route::get('/assessments/post1', function () {
    // Pick whichever "post" assessment you want to submit to:
    $assessment = Assessment::where('type', 'post')->firstOrFail();
    return view('assessments.post1', compact('assessment'));
})->name('assessments.post1');

    // assessments
    Route::get('/assessments/{assessment}', [AssessmentController::class,'show'])->name('assessments.show');
    Route::post('/assessments/{assessment}', [AssessmentController::class,'submit'])->name('assessments.submit');

    // levels
    Route::get('/levels/{level}', [LevelController::class,'show'])->name('levels.show');
    Route::post('/levels/{level}', [LevelController::class,'submit'])->name('levels.submit');


    Route::get('/stages/{stage}/enter', [StageController::class, 'enter'])->name('stages.enter');
});
Route::get('/levels/{level}/instructions', [LevelController::class, 'instructions'])->name('levels.instructions');

Route::post('/levels/execute-python', [\App\Http\Controllers\LevelController::class, 'executePython'])
    ->name('levels.executePython')
    ->middleware('throttle:12,1');
Route::get('/debug-level/{level}', function(Level $level) {
    return [
        'id' => $level->id,
        'title' => $level->title,
        'type' => $level->type,
        'content' => $level->content,
        'resolved_view' => app(App\Http\Controllers\LevelController::class)->resolveLevelView($level)
    ];
});
Route::view('/contact', 'contact')->name('contact');





// ---------------------
// ADMIN ROUTES
// ---------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Stage management
    Route::resource('stages', AdminStageController::class);
    Route::post('stages/reorder', [AdminStageController::class, 'reorder'])->name('stages.reorder');

    // Level management
    Route::resource('levels', AdminLevelController::class);
    Route::post('levels/{stage}/reorder', [AdminLevelController::class, 'reorder'])->name('levels.reorder');

    // Assessment management
    Route::resource('assessments', AdminAssessmentController::class);


     Route::resource('stages.levels', \App\Http\Controllers\Admin\AdminLevelController::class);
    Route::post('stages/{stage}/levels/reorder', [\App\Http\Controllers\Admin\AdminLevelController::class, 'reorder'])->name('levels.reorder');
});

require __DIR__.'/auth.php';

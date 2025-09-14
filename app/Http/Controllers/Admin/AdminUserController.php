<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::withCount(['quizAttempts', 'levelProgress', 'stageProgress'])->get();
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $quizAttempts = $user->quizAttempts()->with(['stage','level'])->latest()->get();
        $levelProgress = $user->levelProgress()->with(['stage','level'])->latest()->get();
        $stageProgress = $user->stageProgress()->with(['stage'])->latest()->get();

        return view('admin.users.show', compact('user', 'quizAttempts', 'levelProgress', 'stageProgress'));
    }
}

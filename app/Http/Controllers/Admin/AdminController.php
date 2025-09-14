<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\Level;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stagesCount = Stage::count();
        $levelsCount = Level::count();
        $usersCount  = User::count();
        $completionRate = 89; // placeholder

        return view('admin.dashboard', compact(
            'stagesCount',
            'levelsCount',
            'usersCount',
            'completionRate'
        ));
    }
}

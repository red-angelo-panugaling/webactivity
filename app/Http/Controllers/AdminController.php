<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        if (! $request->user()->isAdmin()) {
            return view('User.dashboard');
        }

        return view('Admin.dashboard', [
            'dashboardStats' => [
                'employees' => Employee::count(),
                'users' => User::count(),
                'activeUsers' => User::where('status', true)->count(),
                'inactiveUsers' => User::where('status', false)->count(),
            ],
        ]);
    }
}

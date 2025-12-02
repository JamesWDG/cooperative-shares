<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        // Get only vendors & normal users
        $users = User::whereIn('role', ['vendor', 'user'])
                    ->orderBy('id', 'desc')
                    ->get();

        return view('screens.admin.users.index', compact('users'));
    }

    public function detail(User $user)
    {
        return view('screens.admin.users.detail', compact('user'));
    }
}

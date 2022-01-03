<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        $users = User::all();
        return $users
            ->makeHidden(['username', 'email', 'role', 'created_at', 'updated_at']);
    }
}

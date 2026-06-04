<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $usuarios = User::paginate(10);
        return view('users.usuarios', compact('usuarios'));
    }
}
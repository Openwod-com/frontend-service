<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()) {
            return view('auth.login');
        }
        return redirect('/');
    }
}

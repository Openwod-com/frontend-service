<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()) {
            return view('auth.login');
        }

        return view('activities.activities', [
            'activities' => [],
            'boxes' => [],
            'is_admin' => true
        ]);
    }
}

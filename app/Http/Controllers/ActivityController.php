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

        // return view('activities', [
        //     'activities' => auth()->user()->activities()->where('start', '>=', Carbon::now('Europe/Stockholm'))->get()->sortBy('start'),
        //     'boxes' => auth()->user()->boxes()->where('box_user.admin', true)->get()->sortBy('name'),
        //     'is_admin' => DB::select('SELECT * FROM box_user where user_id=' . auth()->user()->id . ' AND admin=true')

        // ]);
    }
}

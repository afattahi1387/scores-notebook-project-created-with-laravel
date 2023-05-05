<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function redirect_to_dashboard() {
        if(auth()->user()->type == 'teacher') {
            return redirect()->route('dashboard');
        } else {
            return 'salam';
        }
    }

    public function dashboard() {
        return view('dashboard.dashboard');
    }
}

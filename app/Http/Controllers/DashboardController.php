<?php

namespace App\Http\Controllers;

use App\User;
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
        $user = User::find(auth()->user()->id);
        $lesson_rooms = $user->lesson_rooms();
        return view('dashboard.dashboard', ['lesson_rooms' => $lesson_rooms]);
    }
}

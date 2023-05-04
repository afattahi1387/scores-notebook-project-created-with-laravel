<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function login() {
        if(auth()->check()) {
            return 'slam';
        }

        return view('auth.login');
    }
}

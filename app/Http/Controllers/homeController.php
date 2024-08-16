<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
// HomeController.php
    public function index()
    {
        $user = Auth::user();
        $data = [
            'user' => $user,
        ];
        return view('home', $data);
    }

}

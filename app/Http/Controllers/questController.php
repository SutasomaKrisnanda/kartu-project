<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class questController extends Controller
{
    public function index(){
        $user = Auth::user();
        $data = [
            'user' => $user,
            'quests' => $user->quests
        ];
        return view('quest', $data);
    }
}

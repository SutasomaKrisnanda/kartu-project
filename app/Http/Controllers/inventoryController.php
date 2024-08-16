<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class inventoryController extends Controller
{
    public function index(){
        $user = Auth::user();
        $data = [
            'user' => $user,
            'items' => $user->items
        ];
        return view('inventory', $data);
    }

    // public function sort(Request $request)
    // {
    //     $sortOrder = $request->input('sort');
    //     // Apply sorting logic based on $sortOrder (e.g., using Eloquent orderBy)
    //     $inventory = "Hello World";
    //     return response()->json(['inventory' => $inventory]);
    // }
}

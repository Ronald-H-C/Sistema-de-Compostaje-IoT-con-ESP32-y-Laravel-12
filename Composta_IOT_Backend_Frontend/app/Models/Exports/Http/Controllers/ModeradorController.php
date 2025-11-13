<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ModeradorController extends Controller
{
      public function index()
    {
        $users = User::all();
        return view('moderador.index', compact('users'));
    }
}

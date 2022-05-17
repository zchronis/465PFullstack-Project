<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;

class WelcomeController extends Controller
{
    public function show() {
        return view('welcome');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $title = 'Akun Saya';
        $accounts = User::get();
        return view('account.index', compact(['title', 'accounts']));
    }
}

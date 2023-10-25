<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-management', ['only' => ['index','store']]);
        $this->middleware('permission:user-management-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-management-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-management-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'User Management';
        $users = User::get();
        return view('user_management.index', compact(['title', 'users']));
    }

    
}

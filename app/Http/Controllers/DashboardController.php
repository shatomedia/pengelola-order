<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard', ['only' => ['index']]);
    }

    public function index()
    {
        $title = 'Dashboard';
        $orders = Order::get();
        $products = Product::get();
        return view('dashboard.index', compact('title', 'products', 'orders'));
    }
}

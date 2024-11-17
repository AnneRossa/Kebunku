<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    function index() {
        $orders = Order::with('order_items.product.location', 'order_items.product.category', 'order_items.order')
                       ->where('created_by', Auth::id())  // Filter order berdasarkan created_by
                       ->get();
        return Inertia::render('User/Dashboard', ['orders'=>$orders]);
    }
}

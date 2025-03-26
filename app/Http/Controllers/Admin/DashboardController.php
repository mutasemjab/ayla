<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    // Get total customers (users)
    $totalCustomers = User::where('user_type',1)->count();

    // Get customers who made an order this month
    $customersWithOrdersThisMonth = Order::whereMonth('created_at', now()->month)
        ->distinct('user_id')
        ->count('user_id');

    // Get new users registered this month
    $newUsersThisMonth = User::whereMonth('created_at', now()->month)->count();

    // Get total orders
    $totalOrders = Order::count();

    // Get total orders today for mobile cards (assuming product_id indicates mobile cards)
    $totalOrdersTodayMobile = Order::whereDate('created_at', now())->where('number_of_game',null)
        ->count();

    // Get total orders today for game cards
    $totalOrdersTodayGame = Order::whereDate('created_at', now())
        ->where('number_of_game','!=',null)
        ->count();

    return view('admin.dashboard', compact(
        'totalCustomers',
        'customersWithOrdersThisMonth',
        'newUsersThisMonth',
        'totalOrders',
        'totalOrdersTodayMobile',
        'totalOrdersTodayGame'
    ));
}

}

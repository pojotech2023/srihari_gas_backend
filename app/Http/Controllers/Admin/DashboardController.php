<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = User::where('is_admin', false)->count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        
        $totalRevenue = Order::where('payment_status', 'completed')
            ->sum('total_price');

        $recentOrders = Order::with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Order Status stats for graphs
        $orderStats = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $statuses = ['pending', 'assigned', 'delivered', 'cancelled'];
        $orderStatusStats = [];
        foreach ($statuses as $status) {
            $orderStatusStats[$status] = $orderStats[$status] ?? 0;
        }

        return view('admin.dashboard', compact(
            'totalCustomers',
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'recentOrders',
            'orderStatusStats'
        ));
    }
}

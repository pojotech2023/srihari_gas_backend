<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deliveryman;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = User::where('is_admin', false)->count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalDeliverymen = Deliveryman::count();
        
        $totalRevenue = Order::whereIn('payment_status', ['paid', 'completed'])
            ->sum('total_price');

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
            'totalDeliverymen',
            'totalRevenue',
            'orderStatusStats'
        ));
    }
}

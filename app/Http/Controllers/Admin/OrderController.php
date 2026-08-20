<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Deliveryman;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        
        $query = Order::with(['user', 'deliveryman', 'address'])->latest();
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $orders = $query->get();
        $deliverymen = Deliveryman::all();

        return view('admin.orders.index', compact('orders', 'deliverymen', 'status'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'address', 'deliveryman', 'items.product', 'transaction'])
            ->findOrFail($id);
        $deliverymen = Deliveryman::all();

        return view('admin.orders.show', compact('order', 'deliverymen'));
    }

    public function assignDeliveryman(Request $request, $id)
    {
        $request->validate([
            'deliveryman_id' => 'required|exists:deliverymen,id',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'deliveryman_id' => $request->deliveryman_id,
            'status' => 'assigned' // Auto transition to assigned
        ]);

        return back()->with('success', 'Deliveryman assigned and order status set to Assigned.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,assigned,in progress,cylinder delivered,i got empty cylinder,cylinder delivered in godown,delivered,cancelled',
            'payment_status' => 'required|in:pending,completed,failed',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        return back()->with('success', 'Order status updated successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Deliveryman;
use App\Services\NotificationService;
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
        $deliveryman = Deliveryman::findOrFail($request->deliveryman_id);

        $order->update([
            'deliveryman_id' => $deliveryman->id,
            'status' => 'assigned', // Auto transition to assigned
            'assigned_at' => now(),
        ]);

        // Trigger push notification to assigned deliveryman
        NotificationService::notifyDeliverymanAssigned($order, $deliveryman);

        return back()->with('success', 'Deliveryman assigned and push notification sent.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,assigned,in progress,cylinder delivered,i got empty cylinder,cylinder delivered in godown,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,unpaid,failed',
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $oldPaymentStatus = $order->payment_status;

        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        if ($request->status !== $oldStatus) {
            if ($request->status === 'delivered' || $request->status === 'cylinder delivered') {
                NotificationService::notifyCustomerOrderDelivered($order);
            } elseif ($request->status === 'cancelled') {
                NotificationService::notifyCustomerOrderCancelled($order);
            }
        }

        if ($request->payment_status !== $oldPaymentStatus) {
            if ($request->payment_status === 'paid') {
                NotificationService::notifyCustomerOrderPaid($order);
            } elseif ($request->payment_status === 'failed') {
                NotificationService::notifyCustomerOrderPaymentFailed($order);
            }
        }

        return back()->with('success', 'Order status updated successfully.');
    }
}

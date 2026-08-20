<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:cod,online',
            'razorpay_payment_id' => 'required_if:payment_method,online|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $userId = $request->user()->id;

        // Verify address belongs to user
        $address = Address::where('id', $request->address_id)
            ->where('user_id', $userId)
            ->first();

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid delivery address'
            ], 404);
        }

        // Get user's cart items
        $cartItems = CartItem::with('product')
            ->where('user_id', $userId)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty'
            ], 400);
        }

        // Compute total price
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item->product->price * $item->quantity;
        }

        try {
            $order = DB::transaction(function () use ($userId, $request, $totalPrice, $cartItems) {
                $paymentStatus = $request->payment_method === 'online' ? 'completed' : 'pending';

                // Create order
                $order = Order::create([
                    'user_id' => $userId,
                    'address_id' => $request->address_id,
                    'status' => 'pending',
                    'total_price' => $totalPrice,
                    'payment_status' => $paymentStatus,
                    'payment_method' => $request->payment_method,
                ]);

                // Create order items
                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price,
                    ]);
                }

                // Log payment transaction if online
                if ($request->payment_method === 'online' && $request->razorpay_payment_id) {
                    Transaction::create([
                        'order_id' => $order->id,
                        'user_id' => $userId,
                        'transaction_id' => $request->razorpay_payment_id,
                        'amount' => $totalPrice,
                        'payment_gateway' => 'Razorpay',
                        'status' => 'success',
                        'payload' => [
                            'razorpay_payment_id' => $request->razorpay_payment_id,
                            'method' => 'online'
                        ]
                    ]);
                }

                // Empty the cart
                CartItem::where('user_id', $userId)->delete();

                return $order;
            });

            return response()->json([
                'success' => true,
                'data' => $order->load(['items.product', 'transaction']),
                'message' => 'Order placed successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function transactionCallback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'transaction_id' => 'required|string',
            'amount' => 'required|numeric',
            'payment_gateway' => 'required|string',
            'status' => 'required|string', // e.g. success, failed, pending
            'payload' => 'nullable|array', // Full gateway response
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::transaction(function () use ($request) {
                // Record the transaction
                Transaction::create([
                    'order_id' => $request->order_id,
                    'user_id' => $request->user()?->id, // If authenticated, log user_id
                    'transaction_id' => $request->transaction_id,
                    'amount' => $request->amount,
                    'payment_gateway' => $request->payment_gateway,
                    'status' => $request->status,
                    'payload' => $request->payload,
                ]);

                // Update order payment status
                $order = Order::find($request->order_id);
                if (strtolower($request->status) === 'success' || strtolower($request->status) === 'completed') {
                    $order->update(['payment_status' => 'completed']);
                } else if (strtolower($request->status) === 'failed') {
                    $order->update(['payment_status' => 'failed']);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Transaction details recorded successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record transaction: ' . $e->getMessage()
            ], 500);
        }
    }

    public function listOrders(Request $request)
    {
        $orders = Order::with(['items.product', 'address'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
            'message' => 'Orders retrieved successfully'
        ]);
    }

    public function customerOrders($id)
    {
        $user = \App\Models\User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        $orders = Order::with(['items.product', 'address'])
            ->where('user_id', $id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
            'message' => 'Customer orders retrieved successfully'
        ]);
    }
}

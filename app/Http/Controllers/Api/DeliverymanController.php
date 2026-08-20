<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deliveryman;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DeliverymanController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $deliveryman = Deliveryman::where('mobile_number', $request->mobile_number)->first();

        if (!$deliveryman || !Hash::check($request->password, $deliveryman->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Generate Sanctum token for Deliveryman
        $token = $deliveryman->createToken('deliveryman_token', ['*'])->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'deliveryman' => $deliveryman,
                'token' => $token
            ],
            'message' => 'Login successful'
        ]);
    }

    public function listAssignedOrders(Request $request)
    {
        // $request->user() returns the authenticated Deliveryman instance
        $deliveryman = $request->user();

        if (!($deliveryman instanceof Deliveryman)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $orders = Order::with(['user', 'address'])
            ->where('deliveryman_id', $deliveryman->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
            'message' => 'Assigned orders retrieved successfully'
        ]);
    }

    public function showOrderDetail(Request $request, $id)
    {
        $deliveryman = $request->user();

        if (!($deliveryman instanceof Deliveryman)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $order = Order::with(['user', 'address', 'items.product', 'transaction'])
            ->where('deliveryman_id', $deliveryman->id)
            ->where('id', $id)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or not assigned to you'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Order details retrieved successfully'
        ]);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $deliveryman = $request->user();

        if (!($deliveryman instanceof Deliveryman)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $order = Order::where('deliveryman_id', $deliveryman->id)
            ->where('id', $id)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or not assigned to you'
            ], 404);
        }

        $allowedStatuses = [
            'in progress',
            'cylinder delivered',
            'i got empty cylinder',
            'cylinder delivered in godown',
            'delivered'
        ];

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:' . implode(',', $allowedStatuses),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $order->status = $request->status;

        // Auto update payment if cylinder is delivered
        if ($request->status === 'delivered' || $request->status === 'cylinder delivered') {
            $order->payment_status = 'completed';
        }

        $order->save();

        return response()->json([
            'success' => true,
            'data' => $order->load(['user', 'address', 'items.product', 'transaction']),
            'message' => 'Order status updated successfully'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deliveryman;
use App\Models\Order;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DeliverymanController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|unique:deliverymen,mobile_number',
            'password' => 'required|string|min:6',
            'confirm_password' => 'nullable|string|same:password',
            'fcm_token' => 'nullable|string',
            'cm_firebase_token' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'aadhar_card' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'driving_licence' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pan_card' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'insurance' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rc_book' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vehicle_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $fcmToken = $request->fcm_token ?? $request->cm_firebase_token;

        $deliveryman = new Deliveryman();
        $deliveryman->name = $request->name;
        $deliveryman->mobile_number = $request->mobile_number;
        $deliveryman->password = Hash::make($request->password);
        $deliveryman->fcm_token = $fcmToken;

        if ($request->hasFile('photo')) {
            $deliveryman->photo = $request->file('photo')->store('deliveryman/profile', 'public');
        }
        if ($request->hasFile('aadhar_card')) {
            $deliveryman->aadhar_card = $request->file('aadhar_card')->store('deliveryman/profile', 'public');
        }
        if ($request->hasFile('driving_licence')) {
            $path = $request->file('driving_licence')->store('deliveryman/profile', 'public');
            $deliveryman->driving_licence = $path;
            $deliveryman->pan_card = $path;
        } elseif ($request->hasFile('pan_card')) {
            $path = $request->file('pan_card')->store('deliveryman/profile', 'public');
            $deliveryman->driving_licence = $path;
            $deliveryman->pan_card = $path;
        }
        if ($request->hasFile('insurance')) {
            $path = $request->file('insurance')->store('deliveryman/vehicle', 'public');
            $deliveryman->insurance = $path;
            $deliveryman->rc_book = $path;
        } elseif ($request->hasFile('rc_book')) {
            $path = $request->file('rc_book')->store('deliveryman/vehicle', 'public');
            $deliveryman->insurance = $path;
            $deliveryman->rc_book = $path;
        }
        if ($request->hasFile('vehicle_image')) {
            $deliveryman->vehicle_image = $request->file('vehicle_image')->store('deliveryman/vehicle', 'public');
        }

        $deliveryman->save();

        $token = $deliveryman->createToken('deliveryman_token', ['*'])->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'deliveryman' => $deliveryman,
                'token' => $token
            ],
            'message' => 'Delivery agent registration successful'
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|string',
            'password' => 'required|string',
            'fcm_token' => 'nullable|string',
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

        // Save FCM token if provided on login
        if ($request->filled('fcm_token')) {
            $deliveryman->fcm_token = $request->fcm_token;
            $deliveryman->save();
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

    /**
     * Check if a delivery partner account exists with the given mobile number.
     * POST /api/deliveryman/check-mobile
     */
    public function checkMobile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $deliveryman = Deliveryman::where('mobile_number', $request->mobile_number)->first();

        if (!$deliveryman) {
            return response()->json([
                'success' => false,
                'message' => 'Delivery partner account not found with this mobile number.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Delivery partner mobile number verified successfully.'
        ]);
    }

    /**
     * Reset delivery partner password using mobile number.
     * POST /api/deliveryman/forgot-password or POST /api/deliveryman/reset-password
     */
    public function forgotPassword(Request $request)
    {
        // Support both password/new_password and confirm_password/password_confirmation
        $password = $request->input('password') ?? $request->input('new_password');
        $confirmPassword = $request->input('confirm_password') ?? $request->input('password_confirmation');

        $request->merge([
            'password' => $password,
            'confirm_password' => $confirmPassword,
        ]);

        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|string',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|string|same:password',
        ], [
            'confirm_password.same' => 'The password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $deliveryman = Deliveryman::where('mobile_number', $request->mobile_number)->first();

        if (!$deliveryman) {
            return response()->json([
                'success' => false,
                'message' => 'Delivery partner account not found with this mobile number.'
            ], 404);
        }

        $deliveryman->password = Hash::make($request->password);
        $deliveryman->save();

        // Invalidate previous tokens
        $deliveryman->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully. Please login with your new password.'
        ]);
    }

    /**
     * List all unassigned orders available for self-assignment by delivery personnel.
     */
    public function availableOrders(Request $request)
    {
        $deliveryman = $request->user();

        if (!($deliveryman instanceof Deliveryman)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $orders = Order::with(['user', 'address', 'items.product'])
            ->whereNull('deliveryman_id')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
            'message' => 'Available orders retrieved successfully'
        ]);
    }

    /**
     * Deliveryman self-assigns / accepts an available order.
     */
    public function claimOrder(Request $request, $id)
    {
        $deliveryman = $request->user();

        if (!($deliveryman instanceof Deliveryman)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        if ($order->deliveryman_id !== null) {
            return response()->json([
                'success' => false,
                'message' => 'This order has already been assigned to another delivery agent'
            ], 409);
        }

        $order->deliveryman_id = $deliveryman->id;
        $order->status = 'assigned';
        $order->assigned_at = now();
        $order->save();

        return response()->json([
            'success' => true,
            'data' => $order->load(['user', 'address', 'items.product', 'deliveryman']),
            'message' => 'Order accepted and assigned successfully'
        ]);
    }

    public function listAssignedOrders(Request $request)
    {
        $deliveryman = $request->user();

        if (!($deliveryman instanceof Deliveryman)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $orders = Order::with(['user', 'address', 'items.product'])
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

        // Auto update payment to 'paid' if cylinder is delivered
        $isDelivered = ($request->status === 'delivered' || $request->status === 'cylinder delivered');
        if ($isDelivered) {
            $order->payment_status = 'paid';
        }

        $order->save();

        if ($isDelivered) {
            NotificationService::notifyCustomerOrderDelivered($order);
            NotificationService::notifyCustomerOrderPaid($order);
        }

        return response()->json([
            'success' => true,
            'data' => $order->load(['user', 'address', 'items.product', 'transaction']),
            'message' => 'Order status updated successfully'
        ]);
    }

    /**
     * Endpoint for deliveryman to update payment status:
     * POST /deliveryman/orders/{order_id}/payment-status
     */
    public function updatePaymentStatus(Request $request, $id)
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

        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:pending,paid,unpaid,failed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $order->payment_status = $request->payment_status;
        $order->save();

        if ($request->payment_status === 'paid') {
            NotificationService::notifyCustomerOrderPaid($order);
        } elseif ($request->payment_status === 'failed') {
            NotificationService::notifyCustomerOrderPaymentFailed($order);
        }

        return response()->json([
            'success' => true,
            'data' => $order->load(['user', 'address', 'items.product', 'transaction']),
            'message' => 'Payment status updated successfully'
        ]);
    }

    /**
     * Register or update FCM token for delivery notifications:
     * POST /deliveryman/fcm-token
     */
    public function updateFcmToken(Request $request)
    {
        $deliveryman = $request->user();

        if (!($deliveryman instanceof Deliveryman)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required_without:cm_firebase_token|nullable|string',
            'cm_firebase_token' => 'required_without:fcm_token|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $token = $request->fcm_token ?? $request->cm_firebase_token;
        $deliveryman->fcm_token = $token;
        $deliveryman->save();

        return response()->json([
            'success' => true,
            'data' => $deliveryman,
            'message' => 'FCM token updated successfully'
        ]);
    }

    public function updateVehicleDetails(Request $request)
    {
        $deliveryman = $request->user();

        if (!($deliveryman instanceof Deliveryman)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'rc_book' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'insurance' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vehicle_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle Insurance / RC Book upload (mapped to both fields for full compatibility)
        if ($request->hasFile('insurance')) {
            $path = $request->file('insurance')->store('deliveryman/vehicle', 'public');
            $deliveryman->insurance = $path;
            $deliveryman->rc_book = $path;
        } elseif ($request->hasFile('rc_book')) {
            $path = $request->file('rc_book')->store('deliveryman/vehicle', 'public');
            $deliveryman->insurance = $path;
            $deliveryman->rc_book = $path;
        }

        if ($request->hasFile('vehicle_image')) {
            $deliveryman->vehicle_image = $request->file('vehicle_image')->store('deliveryman/vehicle', 'public');
        }

        $deliveryman->save();

        return response()->json([
            'success' => true,
            'data' => $deliveryman,
            'message' => 'Vehicle details updated successfully'
        ]);
    }

    public function updateProfile(Request $request)
    {
        $deliveryman = $request->user();

        if (!($deliveryman instanceof Deliveryman)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'aadhar_card' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'driving_licence' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pan_card' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'insurance' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rc_book' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('photo')) {
            $deliveryman->photo = $request->file('photo')->store('deliveryman/profile', 'public');
        }

        if ($request->hasFile('aadhar_card')) {
            $deliveryman->aadhar_card = $request->file('aadhar_card')->store('deliveryman/profile', 'public');
        }

        // Handle Driving Licence / PAN card mapping
        if ($request->hasFile('driving_licence')) {
            $path = $request->file('driving_licence')->store('deliveryman/profile', 'public');
            $deliveryman->driving_licence = $path;
            $deliveryman->pan_card = $path;
        } elseif ($request->hasFile('pan_card')) {
            $path = $request->file('pan_card')->store('deliveryman/profile', 'public');
            $deliveryman->driving_licence = $path;
            $deliveryman->pan_card = $path;
        }

        // Handle Insurance / RC Book mapping
        if ($request->hasFile('insurance')) {
            $path = $request->file('insurance')->store('deliveryman/vehicle', 'public');
            $deliveryman->insurance = $path;
            $deliveryman->rc_book = $path;
        } elseif ($request->hasFile('rc_book')) {
            $path = $request->file('rc_book')->store('deliveryman/vehicle', 'public');
            $deliveryman->insurance = $path;
            $deliveryman->rc_book = $path;
        }

        $deliveryman->save();

        return response()->json([
            'success' => true,
            'data' => $deliveryman,
            'message' => 'Profile updated successfully'
        ]);
    }
}

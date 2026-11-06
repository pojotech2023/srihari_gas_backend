<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|unique:users,mobile_number',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|string|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $fcmToken = $request->fcm_token ?? $request->cm_firebase_token;

        $user = User::create([
            'name' => $request->name,
            'mobile_number' => $request->mobile_number,
            'password' => Hash::make($request->password),
            'is_admin' => false,
            'fcm_token' => $fcmToken,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token
            ],
            'message' => 'Registration successful'
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|string',
            'password' => 'required|string',
            'fcm_token' => 'nullable|string',
            'cm_firebase_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('mobile_number', $request->mobile_number)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $fcmToken = $request->fcm_token ?? $request->cm_firebase_token;
        if (!empty($fcmToken)) {
            $user->fcm_token = $fcmToken;
            $user->save();
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token
            ],
            'message' => 'Login successful'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|unique:users,mobile_number,' . $user->id,
            'password' => 'nullable|string|min:6',
            'fcm_token' => 'nullable|string',
            'cm_firebase_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->name = $request->name;
        $user->mobile_number = $request->mobile_number;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $fcmToken = $request->fcm_token ?? $request->cm_firebase_token;
        if (!empty($fcmToken)) {
            $user->fcm_token = $fcmToken;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Profile updated successfully'
        ]);
    }

    /**
     * Register or update FCM token for customer notifications:
     * POST /customer/fcm-token or POST /fcm-token
     */
    public function updateFcmToken(Request $request)
    {
        $user = $request->user();

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
        $user->fcm_token = $token;
        $user->save();

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Customer FCM token updated successfully'
        ]);
    }

    /**
     * Check if a customer account exists with the given mobile number.
     * POST /api/check-mobile or POST /api/customer/check-mobile
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

        $user = User::where('mobile_number', $request->mobile_number)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Customer account not found with this mobile number.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Customer mobile number verified successfully.'
        ]);
    }

    /**
     * Reset customer password using mobile number.
     * POST /api/forgot-password or POST /api/reset-password
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

        $user = User::where('mobile_number', $request->mobile_number)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Customer account not found with this mobile number.'
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Invalidate previous tokens
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully. Please login with your new password.'
        ]);
    }
}


<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = Address::where('user_id', $request->user()->id)->get();

        return response()->json([
            'success' => true,
            'data' => $addresses,
            'message' => 'Addresses retrieved successfully'
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required|string',
            'location' => 'required|string|max:255',
            'pincode' => 'required|string|max:20',
            'landmark' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $address = Address::create([
            'user_id' => $request->user()->id,
            'address' => $request->address,
            'location' => $request->location,
            'pincode' => $request->pincode,
            'landmark' => $request->landmark,
        ]);

        return response()->json([
            'success' => true,
            'data' => $address,
            'message' => 'Address saved successfully'
        ], 201);
    }
}

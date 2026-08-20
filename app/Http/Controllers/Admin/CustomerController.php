<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('is_admin', false)
            ->withCount('orders')
            ->latest()
            ->get();

        return view('admin.customers.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = User::where('is_admin', false)
            ->with(['addresses', 'orders.items.product'])
            ->findOrFail($id);

        return view('admin.customers.show', compact('customer'));
    }
}

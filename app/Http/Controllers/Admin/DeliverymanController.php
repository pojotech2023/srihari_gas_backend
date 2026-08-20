<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deliveryman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DeliverymanController extends Controller
{
    public function index()
    {
        $deliverymen = Deliveryman::latest()->get();
        return view('admin.deliverymen.index', compact('deliverymen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20|unique:deliverymen,mobile_number',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|string|same:password',
        ]);

        Deliveryman::create([
            'name' => $request->name,
            'mobile_number' => $request->mobile_number,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.deliverymen.index')->with('success', 'Deliveryman added successfully.');
    }

    public function edit($id)
    {
        $deliveryman = Deliveryman::findOrFail($id);
        return view('admin.deliverymen.edit', compact('deliveryman'));
    }

    public function update(Request $request, $id)
    {
        $deliveryman = Deliveryman::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20|unique:deliverymen,mobile_number,' . $id,
            'password' => 'nullable|string|min:6',
            'confirm_password' => 'nullable|string|same:password',
        ]);

        $deliveryman->name = $request->name;
        $deliveryman->mobile_number = $request->mobile_number;

        if ($request->filled('password')) {
            $deliveryman->password = Hash::make($request->password);
        }

        $deliveryman->save();

        return redirect()->route('admin.deliverymen.index')->with('success', 'Deliveryman updated successfully.');
    }

    public function destroy($id)
    {
        $deliveryman = Deliveryman::findOrFail($id);
        $deliveryman->delete();

        return redirect()->route('admin.deliverymen.index')->with('success', 'Deliveryman deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = \App\Models\Vehicle::with('driver.user')->get();
        return view('vehicles', compact('vehicles'));
    }

    public function show($id)
    {
        $vehicle = Vehicle::with(['driver'])->findOrFail($id);
        return response()->json($vehicle);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'make' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer',
            'plate_number' => 'required|string|max:20',
            'capacity' => 'required|integer|min:1',
        ]);
        $vehicle = Vehicle::create($validated);
        return redirect('/vehicles')->with('success', 'Vehicle added successfully!');
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $validated = $request->validate([
            'make' => 'sometimes|required|string|max:50',
            'model' => 'sometimes|required|string|max:50',
            'year' => 'sometimes|required|integer',
            'plate_number' => 'sometimes|required|string|max:20',
            'capacity' => 'sometimes|required|integer|min:1',
        ]);
        $vehicle->update($validated);
        return response()->json($vehicle);
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();
        return response()->json(['message' => 'Vehicle deleted successfully']);
    }

    public function create()
    {
        $drivers = \App\Models\Driver::with('user')->get();
        return view('add_vehicle', compact('drivers'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;

class TripController extends Controller
{
    public function index()
    {
        $trips = \App\Models\Trip::with(['tripRequest.passenger.user', 'driver.user'])->get();
        return view('trips', compact('trips'));
    }

    public function show($id)
    {
        $trip = Trip::with(['tripRequest', 'driver', 'payment', 'reviews'])->findOrFail($id);
        return response()->json($trip);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|exists:trip_requests,id',
            'driver_id' => 'required|exists:drivers,id',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date',
            'fare' => 'required|numeric',
            'tip' => 'nullable|numeric',
        ]);
        $trip = \App\Models\Trip::create($validated);
        return redirect('/')->with('success', 'Trip created successfully!');
    }

    public function update(Request $request, $id)
    {
        $trip = Trip::findOrFail($id);
        $validated = $request->validate([
            'start_time' => 'sometimes|required|date',
            'end_time' => 'sometimes|nullable|date',
            'fare' => 'sometimes|required|numeric',
            'tip' => 'sometimes|nullable|numeric',
        ]);
        $trip->update($validated);
        return response()->json($trip);
    }

    public function destroy($id)
    {
        $trip = Trip::findOrFail($id);
        $trip->delete();
        return response()->json(['message' => 'Trip deleted successfully']);
    }

    public function create(Request $request)
    {
        $request_id = $request->query('request_id');
        $drivers = \App\Models\Driver::with('user')->get();
        return view('create_trip', compact('request_id', 'drivers'));
    }
}

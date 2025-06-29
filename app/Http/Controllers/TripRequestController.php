<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TripRequest;
use App\Models\Passenger;

class TripRequestController extends Controller
{
    public function index()
    {
        $tripRequests = \App\Models\TripRequest::with('passenger.user')->get();
        return view('trip_requests', compact('tripRequests'));
    }

    public function show($id)
    {
        $tripRequest = TripRequest::with(['passenger', 'trip'])->findOrFail($id);
        return response()->json($tripRequest);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'passenger_id' => 'required|exists:passengers,id',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'request_time' => 'required|date',
            'status' => 'required|string|max:50',
            'seats' => 'required|integer|min:1',
            'estimated_fare' => 'required|numeric',
        ]);
        $tripRequest = \App\Models\TripRequest::create($validated);
        return redirect('/')->with('success', 'Trip request submitted successfully!');
    }

    public function update(Request $request, $id)
    {
        $tripRequest = TripRequest::findOrFail($id);
        $validated = $request->validate([
            'pickup_location' => 'sometimes|required|string|max:255',
            'dropoff_location' => 'sometimes|required|string|max:255',
            'request_time' => 'sometimes|required|date',
            'status' => 'sometimes|required|string|max:50',
            'seats' => 'sometimes|required|integer|min:1',
            'estimated_fare' => 'sometimes|required|numeric',
        ]);
        $tripRequest->update($validated);
        return response()->json($tripRequest);
    }

    public function destroy($id)
    {
        $tripRequest = TripRequest::findOrFail($id);
        $tripRequest->delete();
        return response()->json(['message' => 'Trip request deleted successfully']);
    }

    public function create()
    {
        $passengers = Passenger::with('user')->get();
        return view('trip_request', compact('passengers'));
    }
}

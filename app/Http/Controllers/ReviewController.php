<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Driver;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = \App\Models\Review::with(['trip.tripRequest.passenger.user', 'passenger.user', 'driver.user'])->get();
        return view('reviews', compact('reviews'));
    }

    public function show($id)
    {
        $review = Review::with(['trip', 'passenger', 'driver'])->findOrFail($id);
        return response()->json($review);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'passenger_id' => 'required|exists:passengers,id',
            'driver_id' => 'required|exists:drivers,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);
        $review = \App\Models\Review::create($validated);
        return redirect('/')->with('success', 'Review submitted successfully!');
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $validated = $request->validate([
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'comment' => 'sometimes|nullable|string',
        ]);
        $review->update($validated);
        return response()->json($review);
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        return response()->json(['message' => 'Review deleted successfully']);
    }

    public function create()
    {
        $trips = \App\Models\Trip::with(['tripRequest.passenger.user', 'driver.user'])->get();
        $passengers = \App\Models\Passenger::with('user')->get();
        $drivers = \App\Models\Driver::with('user')->get();
        return view('add_review', compact('trips', 'passengers', 'drivers'));
    }
}

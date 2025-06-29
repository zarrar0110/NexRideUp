<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Passenger;
use App\Models\TripRequest;
use App\Models\Trip;
use App\Models\Payment;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use App\Models\Driver;

class PassengerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $passenger = $user->passenger;
        
        if (!$passenger) {
            return redirect('/register-passenger')->with('warning', '⚠️ Please complete your passenger profile to access all features. This helps us provide better service.');
        }

        $data = [
            'totalTrips' => Trip::whereHas('tripRequest', function($query) use ($passenger) {
                $query->where('passenger_id', $passenger->id);
            })->count(),
            'activeRequests' => TripRequest::where('passenger_id', $passenger->id)
                ->whereIn('status', ['pending', 'accepted'])->count(),
            'totalSpent' => Payment::whereHas('trip.tripRequest', function($query) use ($passenger) {
                $query->where('passenger_id', $passenger->id);
            })->sum('amount'),
            'reviewsGiven' => Review::where('passenger_id', $passenger->id)->count(),
            'recentTripRequests' => TripRequest::where('passenger_id', $passenger->id)
                ->orderBy('created_at', 'desc')->limit(5)->get(),
            'recentTrips' => Trip::whereHas('tripRequest', function($query) use ($passenger) {
                $query->where('passenger_id', $passenger->id);
            })->with(['tripRequest', 'driver.user'])
                ->orderBy('created_at', 'desc')->limit(5)->get(),
            'recentReviews' => Review::where('passenger_id', $passenger->id)
                ->orderBy('created_at', 'desc')->limit(5)->get(),
        ];

        return view('passenger.dashboard', $data);
    }

    public function tripRequests()
    {
        $user = Auth::user();
        $passenger = $user->passenger;
        
        $tripRequests = TripRequest::where('passenger_id', $passenger->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('trip_requests', compact('tripRequests'));
    }

    public function createTripRequest()
    {
        return view('trip_request');
    }

    public function storeTripRequest(Request $request)
    {
        $user = Auth::user();
        $passenger = $user->passenger;

        $validated = $request->validate([
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'preferred_time' => 'required|date|after:now',
            'seats' => 'required|integer|min:1|max:6',
            'payment_method' => 'required|string|in:cash,card,mobile_money,digital_wallet',
            'notes' => 'nullable|string|max:500',
        ]);

        // Calculate estimated fare based on distance (simplified calculation)
        $estimatedFare = $this->calculateEstimatedFare($validated['pickup_location'], $validated['dropoff_location']);

        $validated['passenger_id'] = $passenger->id;
        $validated['status'] = 'pending';
        $validated['request_time'] = now();
        $validated['estimated_fare'] = $estimatedFare;

        TripRequest::create($validated);

        return redirect('/passenger/trip-requests')->with('success', 'Trip request created successfully!');
    }

    private function calculateEstimatedFare($pickup, $dropoff)
    {
        // This is a simplified fare calculation
        // In a real application, you would use a proper distance calculation API
        // For now, we'll return a random fare between 500 and 2000
        return rand(500, 2000);
    }

    public function trips()
    {
        $user = Auth::user();
        $passenger = $user->passenger;
        
        $trips = Trip::whereHas('tripRequest', function($query) use ($passenger) {
            $query->where('passenger_id', $passenger->id);
        })->with(['tripRequest', 'driver.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('trips', compact('trips'));
    }

    public function payments()
    {
        $user = Auth::user();
        $passenger = $user->passenger;
        
        $payments = Payment::whereHas('trip.tripRequest', function($query) use ($passenger) {
            $query->where('passenger_id', $passenger->id);
        })->orderBy('created_at', 'desc')->get();

        return view('payments', compact('payments'));
    }

    public function reviews()
    {
        $user = Auth::user();
        $passenger = $user->passenger;
        
        $reviews = Review::where('passenger_id', $passenger->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reviews', compact('reviews'));
    }

    public function createReview()
    {
        $user = Auth::user();
        $passenger = $user->passenger;
        
        $completedTrips = Trip::whereHas('tripRequest', function($query) use ($passenger) {
            $query->where('passenger_id', $passenger->id);
        })->where('status', 'completed')
            ->whereDoesntHave('review')
            ->get();

        return view('add_review', compact('completedTrips'));
    }

    public function storeReview(Request $request)
    {
        $user = Auth::user();
        $passenger = $user->passenger;

        $validated = $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'driver_id' => 'required|exists:drivers,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:500',
        ]);

        $validated['passenger_id'] = $passenger->id;

        Review::create($validated);

        return redirect('/passenger/reviews')->with('success', 'Review submitted successfully!');
    }

    public function profile()
    {
        $user = Auth::user();
        $passenger = $user->passenger;

        return redirect('/passenger/dashboard')->with('info', 'Profile management coming soon!');
    }

    // API methods for JSON responses
    public function index()
    {
        return response()->json(Passenger::with('user')->get());
    }

    public function show($id)
    {
        $passenger = Passenger::with('user')->findOrFail($id);
        return response()->json($passenger);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ], [
            'user_id.exists' => 'User not found.',
            'phone.required' => 'Phone number is required.',
            'address.required' => 'Address is required.',
        ]);

        // Check if passenger profile already exists
        $existingPassenger = Passenger::where('id', $validated['user_id'])->first();
        if ($existingPassenger) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Passenger profile already exists for this user.',
                    'passenger' => $existingPassenger
                ], 409); // Conflict status code
            } else {
                return redirect('/passenger/dashboard')->with('warning', '⚠️ Passenger profile already exists! You can now access your dashboard.');
            }
        }

        $passenger = Passenger::create($validated);
        
        if ($request->expectsJson()) {
            return response()->json([
                'message' => '✅ Passenger profile created successfully!',
                'passenger' => $passenger
            ], 201);
        } else {
            return redirect('/passenger/dashboard')->with('success', '🎉 Passenger profile completed successfully! Welcome to our ride-sharing platform. You can now request trips.');
        }
    }

    public function update(Request $request, $id)
    {
        $passenger = Passenger::findOrFail($id);
        $validated = $request->validate([
            'phone' => 'sometimes|required|string|max:20',
            'address' => 'sometimes|required|string|max:255',
        ]);
        $passenger->update($validated);
        return response()->json($passenger);
    }

    public function destroy($id)
    {
        $passenger = Passenger::findOrFail($id);
        $passenger->delete();
        return response()->json(['message' => 'Passenger deleted successfully']);
    }

    public function showRegistrationForm(Request $request)
    {
        $user_id = $request->query('user_id');
        return view('register_passenger', compact('user_id'));
    }

    public function getOnlineDrivers()
    {
        $drivers = Driver::with('user')
            ->whereHas('user', function($query) {
                $query->where('is_online', true);
            })
            ->get()
            ->map(function($driver) {
                return [
                    'id' => $driver->id,
                    'name' => $driver->user->name,
                    'lat' => $driver->latitude ?? 30.3753, // Default to Pakistan center
                    'lng' => $driver->longitude ?? 69.3451,
                    'vehicle' => $driver->vehicles->first()?->model ?? 'Vehicle'
                ];
            });

        return response()->json($drivers);
    }

    public function markTripFinished($tripId)
    {
        $user = Auth::user();
        $passenger = $user->passenger;
        
        $trip = Trip::with(['tripRequest', 'payment'])->findOrFail($tripId);
        
        // Check if this trip belongs to the passenger
        if ($trip->tripRequest->passenger_id !== $passenger->id) {
            return back()->with('error', 'You can only mark your own trips as finished.');
        }
        
        // Update trip status
        $trip->update([
            'status' => 'finished',
            'end_time' => now(),
        ]);
        
        // Update payment status if payment exists
        if ($trip->payment) {
            $trip->payment->update([
                'status' => 'completed',
                'paid_at' => now(),
            ]);
        }
        
        return redirect('/passenger/trips')->with('success', 'Trip marked as finished! Payment completed.');
    }

    public function viewTripRequest($id)
    {
        $user = Auth::user();
        $passenger = $user->passenger;
        
        // Add debugging
        if (!$passenger) {
            abort(404, 'Passenger profile not found');
        }
        
        try {
            $tripRequest = TripRequest::with(['passenger.user', 'trip'])
                ->where('id', $id)
                ->where('passenger_id', $passenger->id)
                ->first();
                
            if (!$tripRequest) {
                abort(404, 'Trip request not found or you do not have permission to view it');
            }
            
            return view('passenger.trip_request_view', compact('tripRequest'));
        } catch (\Exception $e) {
            abort(500, 'Error loading trip request: ' . $e->getMessage());
        }
    }
}

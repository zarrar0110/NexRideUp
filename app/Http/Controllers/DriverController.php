<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\TripRequest;
use App\Models\Trip;
use App\Models\Vehicle;
use App\Models\Payment;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $driver = $user->driver;
        
        if (!$driver) {
            return redirect('/register-driver')->with('warning', '⚠️ Please complete your driver profile to start accepting trips. This includes license verification and vehicle registration.');
        }

        // Fetch all relevant data for the driver
        $totalTrips = Trip::where('driver_id', $driver->id)->count();
        $activeTripsCount = Trip::where('driver_id', $driver->id)
            ->whereIn('status', ['in_progress', 'accepted'])->count();
        $totalEarnings = Payment::whereHas('trip', function($query) use ($driver) {
            $query->where('driver_id', $driver->id);
        })->sum('amount');
        $monthlyEarnings = Payment::whereHas('trip', function($query) use ($driver) {
            $query->where('driver_id', $driver->id);
        })->where('created_at', '>=', now()->startOfMonth())->sum('amount');
        $averageRating = Review::where('driver_id', $driver->id)->avg('rating');
        
        // Fetch available trip requests (all pending requests)
        $availableTripRequests = TripRequest::where('status', 'pending')
            ->with(['passenger.user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Fetch active trips for this driver
        $activeTrips = Trip::where('driver_id', $driver->id)
            ->whereIn('status', ['in_progress', 'accepted'])
            ->with(['tripRequest.passenger.user'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Fetch recent earnings for this driver
        $recentEarnings = Payment::whereHas('trip', function($query) use ($driver) {
            $query->where('driver_id', $driver->id);
        })->with(['trip'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Fetch vehicles for this driver
        $vehicles = Vehicle::where('driver_id', $driver->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $data = [
            'totalTrips' => $totalTrips,
            'activeTrips' => $activeTripsCount,
            'totalEarnings' => $totalEarnings,
            'monthlyEarnings' => $monthlyEarnings,
            'averageRating' => $averageRating,
            'availableTripRequests' => $availableTripRequests,
            'activeTrips' => $activeTrips,
            'recentEarnings' => $recentEarnings,
            'vehicles' => $vehicles,
        ];

        return view('driver.dashboard', $data);
    }

    public function tripRequests()
    {
        $user = Auth::user();
        $driver = $user->driver;
        if (!$driver) {
            return redirect('/register-driver')->with('warning', 'Please complete your driver profile to access this feature.');
        }
        $tripRequests = TripRequest::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('trip_requests', compact('tripRequests'));
    }

    public function acceptTripRequest($id)
    {
        $user = Auth::user();
        $driver = $user->driver;
        if (!$driver) {
            return redirect('/register-driver')->with('warning', 'Please complete your driver profile to access this feature.');
        }
        $tripRequest = TripRequest::findOrFail($id);

        if ($tripRequest->status !== 'pending') {
            return back()->with('error', 'This trip request is no longer available.');
        }

        // Create a new trip
        $trip = Trip::create([
            'request_id' => $tripRequest->id,
            'driver_id' => $driver->id,
            'start_time' => now(),
            'status' => 'accepted',
            'fare' => $this->calculateFare($tripRequest->pickup_location, $tripRequest->dropoff_location),
        ]);

        // Update trip request status
        $tripRequest->update(['status' => 'accepted']);

        return redirect('/driver/my-trips')->with('success', 'Trip request accepted successfully!');
    }

    public function myTrips()
    {
        $user = Auth::user();
        $driver = $user->driver;
        if (!$driver) {
            return redirect('/register-driver')->with('warning', 'Please complete your driver profile to access this feature.');
        }
        $activeTrips = Trip::where('driver_id', $driver->id)
            ->whereIn('status', ['accepted', 'in_progress'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('trips', ['trips' => $activeTrips]);
    }

    public function trips()
    {
        $user = Auth::user();
        $driver = $user->driver;
        if (!$driver) {
            return redirect('/register-driver')->with('warning', 'Please complete your driver profile to access this feature.');
        }
        $trips = Trip::where('driver_id', $driver->id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('trips', compact('trips'));
    }

    public function completeTrip($id)
    {
        $user = Auth::user();
        $driver = $user->driver;
        if (!$driver) {
            return redirect('/register-driver')->with('warning', 'Please complete your driver profile to access this feature.');
        }
        $trip = Trip::with('tripRequest')->findOrFail($id);

        if ($trip->driver_id !== $driver->id) {
            return back()->with('error', 'You can only complete your own trips.');
        }

        $trip->update([
            'status' => 'completed',
            'end_time' => now(),
        ]);

        // Create payment record
        Payment::create([
            'trip_id' => $trip->id,
            'amount' => $trip->fare,
            'method' => $trip->tripRequest->payment_method,
            'status' => 'pending',
            'paid_at' => now(),
        ]);

        return redirect('/driver/trips')->with('success', 'Trip completed successfully! Payment record created.');
    }

    public function vehicles()
    {
        $user = Auth::user();
        $driver = $user->driver;
        if (!$driver) {
            return redirect('/register-driver')->with('warning', 'Please complete your driver profile to access this feature.');
        }
        $vehicles = Vehicle::where('driver_id', $driver->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('vehicles', compact('vehicles'));
    }

    public function createVehicle()
    {
        $user = Auth::user();
        $driver = $user->driver;
        if (!$driver) {
            return redirect('/register-driver')->with('warning', 'Please complete your driver profile to access this feature.');
        }
        return view('add_vehicle', compact('driver'));
    }

    public function storeVehicle(Request $request)
    {
        $user = Auth::user();
        $driver = $user->driver;
        if (!$driver) {
            return redirect('/register-driver')->with('warning', 'Please complete your driver profile to access this feature.');
        }
        $validated = $request->validate([
            'make' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'required|string|max:50',
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number',
            'capacity' => 'required|integer|min:1|max:10',
        ]);

        $validated['driver_id'] = $driver->id;

        Vehicle::create($validated);

        return redirect('/driver/vehicles')->with('success', 'Vehicle added successfully!');
    }

    public function earnings()
    {
        $user = Auth::user();
        $driver = $user->driver;
        if (!$driver) {
            return redirect('/register-driver')->with('warning', 'Please complete your driver profile to access this feature.');
        }
        $earnings = Payment::whereHas('trip', function($query) use ($driver) {
            $query->where('driver_id', $driver->id);
        })->orderBy('created_at', 'desc')->get();

        $totalEarnings = $earnings->sum('amount');
        $monthlyEarnings = $earnings->where('created_at', '>=', now()->startOfMonth())->sum('amount');

        return view('payments', compact('earnings', 'totalEarnings', 'monthlyEarnings'));
    }

    public function reviews()
    {
        $user = Auth::user();
        $driver = $user->driver;
        if (!$driver) {
            return redirect('/register-driver')->with('warning', 'Please complete your driver profile to access this feature.');
        }
        $reviews = Review::where('driver_id', $driver->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reviews', compact('reviews'));
    }

    public function profile()
    {
        $user = Auth::user();
        $driver = $user->driver;
        if (!$driver) {
            return redirect('/register-driver')->with('warning', 'Please complete your driver profile to access this feature.');
        }
        return view('profile', compact('user', 'driver'));
    }

    private function calculateFare($pickup, $dropoff)
    {
        // Simple fare calculation (in a real app, you'd use distance APIs)
        return rand(10, 50); // Random fare between $10-$50
    }

    // API methods for JSON responses
    public function index()
    {
        return response()->json(Driver::with('user')->get());
    }

    public function show($id)
    {
        $driver = Driver::with('user')->findOrFail($id);
        return response()->json($driver);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'license_number' => 'required|string|max:50|unique:drivers,license_number',
            'experience_years' => 'required|integer|min:0|max:50',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ], [
            'user_id.exists' => 'User not found.',
            'license_number.required' => 'License number is required.',
            'license_number.unique' => 'This license number is already registered. Please use a different license or contact support.',
            'experience_years.required' => 'Years of driving experience is required.',
            'experience_years.integer' => 'Experience years must be a number.',
            'experience_years.min' => 'Experience years cannot be negative.',
            'experience_years.max' => 'Experience years cannot exceed 50.',
            'phone.required' => 'Phone number is required.',
            'address.required' => 'Address is required.',
        ]);

        // Check if driver profile already exists
        $existingDriver = Driver::where('id', $validated['user_id'])->first();
        if ($existingDriver) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Driver profile already exists for this user.',
                    'driver' => $existingDriver
                ], 409); // Conflict status code
            } else {
                return redirect('/driver/dashboard')->with('warning', '⚠️ Driver profile already exists! You can now access your dashboard.');
            }
        }

        $driver = Driver::create([
            'id' => $validated['user_id'],
            'license_number' => $validated['license_number'],
            'experience_years' => $validated['experience_years'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'message' => '✅ Driver profile created successfully! Your account is now ready to accept trips.',
                'driver' => $driver
            ], 201);
        } else {
            return redirect('/driver/dashboard')->with('success', '🎉 Driver profile completed successfully! Your account is now ready to accept trip requests. Don\'t forget to add your vehicle information.');
        }
    }

    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);
        $validated = $request->validate([
            'license_number' => 'sometimes|required|string|max:50|unique:drivers,license_number,' . $id,
            'experience_years' => 'sometimes|required|integer|min:0|max:50',
            'phone' => 'sometimes|required|string|max:20',
            'address' => 'sometimes|required|string|max:255',
        ]);
        $driver->update($validated);
        return response()->json($driver);
    }

    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->delete();
        return response()->json(['message' => 'Driver deleted successfully']);
    }

    public function showRegistrationForm(Request $request)
    {
        $user_id = $request->query('user_id');
        return view('register_driver', compact('user_id'));
    }
}

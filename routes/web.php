<?php

use Illuminate\Support\Facades\Route;
use App\Models\Vehicle;
use App\Models\TripRequest;
use Illuminate\Support\Facades\Auth;

// Public routes
Route::get('/', function () {
    $vehicles = Vehicle::with('driver.user')->latest()->take(5)->get();
    $tripRequests = TripRequest::with('passenger.user')->latest()->take(5)->get();
    return view('welcome', compact('vehicles', 'tripRequests'));
});

// Authentication routes
Route::get('/login', [App\Http\Controllers\UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\UserController::class, 'login']);
Route::get('/register', [App\Http\Controllers\UserController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [App\Http\Controllers\UserController::class, 'register'])->name('register');
Route::post('/logout', [App\Http\Controllers\UserController::class, 'logout'])->name('logout');

// Unified dashboard redirect route
Route::get('/dashboard', [App\Http\Controllers\UserController::class, 'redirectToDashboard'])->middleware('auth')->name('dashboard');

// Passenger routes
Route::middleware(['auth', 'role:passenger'])->prefix('passenger')->name('passenger.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\PassengerController::class, 'dashboard'])->name('dashboard');
    Route::get('/trip-requests', [App\Http\Controllers\PassengerController::class, 'tripRequests'])->name('trip-requests.index');
    Route::get('/trip-requests/create', [App\Http\Controllers\PassengerController::class, 'createTripRequest'])->name('trip-requests.create');
    Route::post('/trip-requests', [App\Http\Controllers\PassengerController::class, 'storeTripRequest'])->name('trip-requests.store');
    Route::get('/trips', [App\Http\Controllers\PassengerController::class, 'trips'])->name('trips.index');
    Route::post('/trips/{id}/finish', [App\Http\Controllers\PassengerController::class, 'markTripFinished'])->name('trips.finish');
    Route::get('/payments', [App\Http\Controllers\PassengerController::class, 'payments'])->name('payments.index');
    Route::get('/reviews', [App\Http\Controllers\PassengerController::class, 'reviews'])->name('reviews.index');
    Route::get('/reviews/create', [App\Http\Controllers\PassengerController::class, 'createReview'])->name('reviews.create');
    Route::post('/reviews', [App\Http\Controllers\PassengerController::class, 'storeReview'])->name('reviews.store');
    Route::get('/profile', [App\Http\Controllers\PassengerController::class, 'profile'])->name('profile');
    Route::get('/online-drivers', [App\Http\Controllers\PassengerController::class, 'getOnlineDrivers'])->name('online-drivers');
    Route::get('/trip-requests/{id}/view', [App\Http\Controllers\PassengerController::class, 'viewTripRequest'])->name('trip-requests.view');
});

// Driver routes
Route::middleware(['auth', 'role:driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DriverController::class, 'dashboard'])->name('dashboard');
    Route::get('/trip-requests', [App\Http\Controllers\DriverController::class, 'tripRequests'])->name('trip-requests.index');
    Route::post('/trip-requests/{id}/accept', [App\Http\Controllers\DriverController::class, 'acceptTripRequest'])->name('trip-requests.accept');
    Route::get('/my-trips', [App\Http\Controllers\DriverController::class, 'myTrips'])->name('my-trips.index');
    Route::get('/trips', [App\Http\Controllers\DriverController::class, 'trips'])->name('trips.index');
    Route::post('/trips/{id}/complete', [App\Http\Controllers\DriverController::class, 'completeTrip'])->name('trips.complete');
    Route::get('/vehicles', [App\Http\Controllers\DriverController::class, 'vehicles'])->name('vehicles.index');
    Route::get('/vehicles/create', [App\Http\Controllers\DriverController::class, 'createVehicle'])->name('vehicles.create');
    Route::post('/vehicles', [App\Http\Controllers\DriverController::class, 'storeVehicle'])->name('vehicles.store');
    Route::get('/earnings', [App\Http\Controllers\DriverController::class, 'earnings'])->name('earnings.index');
    Route::get('/reviews', [App\Http\Controllers\DriverController::class, 'reviews'])->name('reviews.index');
    Route::get('/profile', [App\Http\Controllers\DriverController::class, 'profile'])->name('profile');
});

// Admin routes - now using same pattern as driver/passenger
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users.index');
    Route::get('/drivers', [App\Http\Controllers\AdminController::class, 'drivers'])->name('drivers.index');
    Route::get('/passengers', [App\Http\Controllers\AdminController::class, 'passengers'])->name('passengers.index');
    Route::get('/trips', [App\Http\Controllers\AdminController::class, 'trips'])->name('trips.index');
    Route::get('/trip-requests', [App\Http\Controllers\AdminController::class, 'tripRequests'])->name('trip-requests.index');
    Route::get('/vehicles', [App\Http\Controllers\AdminController::class, 'vehicles'])->name('vehicles.index');
    Route::get('/payments', [App\Http\Controllers\AdminController::class, 'payments'])->name('payments.index');
    Route::get('/reviews', [App\Http\Controllers\AdminController::class, 'reviews'])->name('reviews.index');
    Route::get('/reports', [App\Http\Controllers\AdminController::class, 'reports'])->name('reports.index');
    Route::get('/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\AdminController::class, 'updateSettings'])->name('settings.update');
    Route::delete('/users/{id}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.destroy');
    Route::patch('/users/{id}/toggle-status', [App\Http\Controllers\AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
});

// Legacy routes (for backward compatibility)
Route::post('/register/driver', [App\Http\Controllers\DriverController::class, 'store'])->name('driver.profile.store');
Route::post('/register/passenger', [App\Http\Controllers\PassengerController::class, 'store'])->name('passenger.profile.store');
Route::get('/register-driver', [App\Http\Controllers\DriverController::class, 'showRegistrationForm'])->name('driver.profile.form');
Route::get('/register-passenger', [App\Http\Controllers\PassengerController::class, 'showRegistrationForm'])->name('passenger.profile.form');

// API routes (for JSON responses)
Route::prefix('api')->group(function () {
    Route::apiResource('users', App\Http\Controllers\UserController::class);
    Route::apiResource('drivers', App\Http\Controllers\DriverController::class);
    Route::apiResource('passengers', App\Http\Controllers\PassengerController::class);
    Route::apiResource('trip-requests', App\Http\Controllers\TripRequestController::class);
    Route::apiResource('trips', App\Http\Controllers\TripController::class);
    Route::apiResource('vehicles', App\Http\Controllers\VehicleController::class);
    Route::apiResource('payments', App\Http\Controllers\PaymentController::class);
    Route::apiResource('reviews', App\Http\Controllers\ReviewController::class);
});

// Debug route for vehicles
Route::get('/debug/vehicles', function () {
    $user = Auth::user();
    if (!$user) {
        return 'Not logged in';
    }
    
    $driver = $user->driver;
    if (!$driver) {
        return 'No driver profile';
    }
    
    $vehicles = \App\Models\Vehicle::where('driver_id', $driver->id)->get();
    
    return [
        'user_id' => $user->id,
        'driver_id' => $driver->id,
        'vehicles_count' => $vehicles->count(),
        'vehicles' => $vehicles->toArray()
    ];
});

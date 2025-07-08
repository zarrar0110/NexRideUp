<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Driver;
use App\Models\Trip;
use App\Models\Payment;
use App\Models\TripRequest;
use Illuminate\Support\Collection;

class AdminController extends Controller
{
    public function dashboard() {
        // Total users
        $totalUsers = User::count();
        // Active drivers (assuming 'is_online' or similar field on users table)
        $activeDrivers = User::where('role', 'driver')->where('is_online', true)->count();
        // Total trips
        $totalTrips = Trip::count();
        // Total revenue
        $totalRevenue = Payment::sum('amount');
        // Completed trips and revenue
        $completedTrips = Trip::where('status', 'completed')->count();
        $completedRevenueRaw = Payment::whereHas('trip', function($q) {
            $q->where('status', 'completed');
        })->sum('amount');
        $completedRevenue = $completedRevenueRaw * 0.2;
        // System overview counts
        $totalPassengers = \App\Models\Passenger::count();
        $totalDrivers = \App\Models\Driver::count();
        $totalVehicles = \App\Models\Vehicle::count();
        $totalReviews = \App\Models\Review::count();
        // Recent activity: latest 5 from users, trips, payments
        $recentUsers = User::orderByDesc('created_at')->limit(5)->get()->map(function($u) {
            return (object) [
                'type' => 'user_registered',
                'user_name' => $u->name,
                'description' => 'User registered',
                'created_at' => $u->created_at,
            ];
        });
        $recentTrips = Trip::orderByDesc('created_at')->limit(5)->get()->map(function($t) {
            return (object) [
                'type' => 'trip_completed',
                'user_name' => optional(optional($t->driver)->user)->name ?? 'Driver',
                'description' => 'Trip completed',
                'created_at' => $t->created_at,
            ];
        });
        $recentPayments = Payment::orderByDesc('created_at')->limit(5)->get()->map(function($p) {
            return (object) [
                'type' => 'payment_received',
                'user_name' => optional(optional($p->trip)->driver)->user->name ?? 'Driver',
                'description' => 'Payment received',
                'created_at' => $p->created_at,
            ];
        });
        $recentActivity = collect()->merge($recentUsers)->merge($recentTrips)->merge($recentPayments)
            ->sortByDesc('created_at')->take(5)->values();
        // Pending trip requests
        $pendingTripRequests = TripRequest::where('status', 'pending')->orderByDesc('created_at')->limit(5)->get();
        return view('admin.dashboard', compact(
            'totalUsers',
            'activeDrivers',
            'totalTrips',
            'totalRevenue',
            'completedTrips',
            'completedRevenue',
            'recentActivity',
            'pendingTripRequests',
            'totalPassengers',
            'totalDrivers',
            'totalVehicles',
            'totalReviews',
        ));
    }
    public function users() {
        $users = User::all();
        return view('admin.users', compact('users'));
    }
    public function drivers() {
        $drivers = Driver::with('user')->get();
        return view('admin.drivers', compact('drivers'));
    }
    public function passengers() {
        $passengers = \App\Models\Passenger::with('user')->get();
        return view('admin.passengers', compact('passengers'));
    }
    public function trips() {
        $trips = Trip::with(['driver.user', 'tripRequest.passenger.user'])->get();
        return view('admin.trips', compact('trips'));
    }
    public function payments() {
        $payments = Payment::with(['trip.driver.user'])->get();
        return view('admin.payments', compact('payments'));
    }
    public function reports() {
        // Daily, weekly, monthly rides
        $today = now()->startOfDay();
        $week = now()->startOfWeek();
        $month = now()->startOfMonth();
        $dailyRides = Trip::where('created_at', '>=', $today)->count();
        $weeklyRides = Trip::where('created_at', '>=', $week)->count();
        $monthlyRides = Trip::where('created_at', '>=', $month)->count();
        // Recent reviews
        $reviews = \App\Models\Review::with(['trip.driver.user', 'trip.tripRequest.passenger.user'])->orderByDesc('created_at')->limit(10)->get();
        return view('admin.reports', compact('dailyRides', 'weeklyRides', 'monthlyRides', 'reviews'));
    }
    public function settings() { return view('admin.settings'); }
    public function vehicles() {
        $vehicles = \App\Models\Vehicle::with('driver.user')->get();
        return view('vehicles', compact('vehicles'));
    }
    public function tripRequests() {
        $tripRequests = \App\Models\TripRequest::with('passenger.user')->get();
        return view('trip_requests', compact('tripRequests'));
    }
    public function reviews() {
        $reviews = \App\Models\Review::with(['trip.driver.user', 'trip.tripRequest.passenger.user'])->get();
        return view('reviews', compact('reviews'));
    }
} 

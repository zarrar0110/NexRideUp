@extends('layouts.driver')

@section('title', 'Dashboard')

@section('content')
<style>
    .stats-card {
        transition: transform 0.3s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }
    .card {
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: none;
    }
    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid #dee2e6;
        border-radius: 12px 12px 0 0 !important;
    }
    .list-group-item {
        border-radius: 8px;
        margin-bottom: 8px;
        border: 1px solid #e9ecef;
    }
    .badge {
        font-size: 0.8em;
        padding: 0.5em 0.8em;
    }
    .btn-sm {
        border-radius: 6px;
    }
    .text-muted {
        color: #6c757d !important;
    }
</style>

<div class="mb-4">
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="fas fa-dollar-sign me-2 text-success"></i>Earnings Summary</h5>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <div class="text-muted small">Total Earnings</div>
                            <div class="fs-4 fw-bold">${{ number_format($totalEarnings ?? 0, 2) }}</div>
                        </div>
                        <div>
                            <div class="text-muted small">This Month</div>
                            <div class="fs-5">${{ number_format($monthlyEarnings ?? 0, 2) }}</div>
                        </div>
                    </div>
                    @if(isset($recentEarnings) && count($recentEarnings) > 0)
                        <hr>
                        <div class="text-muted small mb-2">Recent Payments</div>
                        <ul class="list-group list-group-flush">
                            @foreach($recentEarnings->take(3) as $payment)
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span>${{ number_format($payment->amount, 2) }}</span>
                                    <span class="text-muted small">{{ $payment->created_at->format('M d, Y') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Live Driver Map</h5>
        </div>
        <div class="card-body">
            <div id="driver-map" style="height: 350px; width: 100%;"></div>
            <small class="text-muted">This map shows your current location (if you allow location access).</small>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('driver-map').setView([30.3753, 69.3451], 6); // Default: Pakistan center

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Try to get driver's current location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            map.setView([lat, lng], 14);
            L.marker([lat, lng]).addTo(map)
                .bindPopup('You are here!')
                .openPopup();
        }, function() {
            // Could not get location
        });
    }
});
</script>

<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-3 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Total Trips</h6>
                        <h3 class="mb-0">{{ $totalTrips ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-car fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Active Trips</h6>
                        <h3 class="mb-0">{{ is_countable($activeTrips) ? count($activeTrips) : ($activeTrips ?? 0) }}</h3>
                        @if(isset($activeTrips) && count($activeTrips) > 0)
                            <ul class="mt-2 mb-0 ps-3" style="font-size: 0.95em;">
                                @foreach($activeTrips as $trip)
                                    <li>
                                        {{ $trip->tripRequest->pickup_location ?? 'N/A' }} → {{ $trip->tripRequest->dropoff_location ?? 'N/A' }}<br>
                                        <span class="text-muted">Passenger: {{ $trip->tripRequest->passenger->user->name ?? 'N/A' }}, Started: {{ $trip->start_time ? $trip->start_time->format('M d, H:i') : 'N/A' }}, Fare: ${{ number_format($trip->fare, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-route fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Total Earnings</h6>
                        <h3 class="mb-0">${{ number_format($totalEarnings ?? 0, 2) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Rating</h6>
                        <h3 class="mb-0">{{ number_format($averageRating ?? 0, 1) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-star fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/driver/trip-requests" class="btn btn-primary">
                        <i class="fas fa-list me-2"></i>View Trip Requests
                    </a>
                    <a href="/driver/my-trips" class="btn btn-outline-success">
                        <i class="fas fa-car me-2"></i>My Active Trips
                    </a>
                    <a href="/driver/vehicles" class="btn btn-outline-info">
                        <i class="fas fa-car-side me-2"></i>Manage Vehicles
                    </a>
                    <a href="/driver/earnings" class="btn btn-outline-warning">
                        <i class="fas fa-dollar-sign me-2"></i>View Earnings
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Trip Requests -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Available Trip Requests</h5>
                <a href="/driver/trip-requests" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if(isset($availableTripRequests) && count($availableTripRequests) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Passenger</th>
                                    <th>Distance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availableTripRequests as $request)
                                <tr>
                                    <td>{{ $request->pickup_location }}</td>
                                    <td>{{ $request->dropoff_location }}</td>
                                    <td>{{ $request->passenger->user->name ?? 'N/A' }}</td>
                                    <td>{{ $request->distance ?? 'N/A' }} km</td>
                                    <td>
                                        <form method="POST" action="/driver/trip-requests/{{ $request->id }}/accept" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check me-1"></i>Accept
                                            </button>
                                        </form>
                                        <a href="/driver/trip-requests/{{ $request->id }}/view" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-list fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No available trip requests at the moment.</p>
                        <p class="text-muted">Check back later for new requests!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Active Trips -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-car me-2"></i>Active Trips</h5>
                <a href="/driver/my-trips" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if(isset($activeTrips) && count($activeTrips) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($activeTrips as $trip)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $trip->tripRequest->pickup_location ?? 'N/A' }} → {{ $trip->tripRequest->dropoff_location ?? 'N/A' }}</h6>
                                    <small class="text-muted">
                                        Passenger: {{ $trip->tripRequest->passenger->user->name ?? 'N/A' }} | 
                                        Started: {{ $trip->start_time ? $trip->start_time->format('M d, H:i') : 'N/A' }}
                                    </small>
                                </div>
                                <div>
                                    <span class="badge bg-success me-2">${{ number_format($trip->fare, 2) }}</span>
                                    <form method="POST" action="/driver/trips/{{ $trip->id }}/complete" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-check me-1"></i>Complete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-car fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No active trips at the moment.</p>
                        <a href="/driver/trip-requests" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>Find Trip Requests
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Earnings -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-dollar-sign me-2"></i>Recent Earnings</h5>
                <a href="/driver/earnings" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if(isset($recentEarnings) && count($recentEarnings) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentEarnings as $earning)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Trip #{{ $earning->trip_id ?? 'N/A' }}</h6>
                                    <small class="text-muted">
                                        {{ $earning->created_at ? $earning->created_at->format('M d, Y') : 'N/A' }}
                                    </small>
                                </div>
                                <span class="badge bg-success">${{ number_format($earning->amount ?? 0, 2) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-dollar-sign fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No earnings yet. Complete trips to start earning!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Vehicle Status -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-car-side me-2"></i>My Vehicles</h5>
                <a href="/driver/vehicles/create" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-plus me-1"></i>Add Vehicle
                </a>
            </div>
            <div class="card-body">
                @if(isset($vehicles) && count($vehicles) > 0)
                    <div class="row">
                        @foreach($vehicles as $vehicle)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $vehicle->make }} {{ $vehicle->model }}</h6>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="fas fa-hashtag me-1"></i>{{ $vehicle->plate_number }}<br>
                                            <i class="fas fa-palette me-1"></i>{{ $vehicle->color ?? 'N/A' }}<br>
                                            <i class="fas fa-calendar me-1"></i>{{ $vehicle->year }}<br>
                                            <i class="fas fa-users me-1"></i>Capacity: {{ $vehicle->capacity }}
                                        </small>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-success">Available</span>
                                        <a href="/driver/vehicles/{{ $vehicle->id }}/edit" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-car-side fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No vehicles registered yet.</p>
                        <p class="text-muted small">Driver ID: {{ auth()->user()->driver->id ?? 'N/A' }}</p>
                        <a href="/driver/vehicles/create" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Vehicle
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 
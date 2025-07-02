@extends('layouts.app')

@section('content')
<div class="hero-section">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-4">Welcome to NexRide</h1>
        <p class="lead mb-5">Connect with drivers and passengers for safe, convenient rides</p>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card role-card passenger-card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user fa-3x text-success"></i>
                    </div>
                    <h4 class="card-title">Passenger</h4>
                    <p class="card-text">Book rides, track your trips, and manage your travel history.</p>
                    <div class="d-grid gap-2">
                        <a href="/register?role=passenger" class="btn btn-success">Join as Passenger</a>
                        @auth
                            @if(auth()->user()->role === 'passenger')
                        <a href="/passenger/dashboard" class="btn btn-outline-success">Passenger Dashboard</a>
                            @else
                                <a href="/passenger/dashboard" class="btn btn-outline-success disabled" title="You are logged in as a {{ ucfirst(auth()->user()->role) }}">Passenger Dashboard</a>
                            @endif
                        @else
                            <a href="/login?intended_role=passenger" class="btn btn-outline-success">Login as Passenger</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card role-card driver-card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-car fa-3x text-primary"></i>
                    </div>
                    <h4 class="card-title">Driver</h4>
                    <p class="card-text">Accept trip requests, manage your vehicle, and earn money.</p>
                    <div class="d-grid gap-2">
                        <a href="/register?role=driver" class="btn btn-primary">Join as Driver</a>
                        @auth
                            @if(auth()->user()->role === 'driver')
                        <a href="/driver/dashboard" class="btn btn-outline-primary">Driver Dashboard</a>
                            @else
                                <a href="/driver/dashboard" class="btn btn-outline-primary disabled" title="You are logged in as a {{ ucfirst(auth()->user()->role) }}">Driver Dashboard</a>
                            @endif
                        @else
                            <a href="/login?intended_role=driver" class="btn btn-outline-primary">Login as Driver</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card role-card admin-card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-cog fa-3x text-danger"></i>
                    </div>
                    <h4 class="card-title">Admin</h4>
                    <p class="card-text">Manage the platform, monitor trips, and handle user support.</p>
                    <div class="d-grid gap-2">
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a href="/admin/dashboard" class="btn btn-outline-danger">Admin Dashboard</a>
                            @else
                                <a href="/admin/dashboard" class="btn btn-outline-danger disabled" title="You are logged in as a {{ ucfirst(auth()->user()->role) }}">Admin Dashboard</a>
                            @endif
                        @else
                            <a href="/login?intended_role=admin" class="btn btn-outline-danger">Login as Admin</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <h3 class="text-center mb-4">Recent Activity</h3>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Trip Requests</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($tripRequests ?? [] as $tripRequest)
                            <li class="list-group-item">
                                <strong>{{ $tripRequest->pickup_location }}</strong> → <strong>{{ $tripRequest->dropoff_location }}</strong>
                                <br><small class="text-muted">Status: {{ $tripRequest->status }}</small>
                            </li>
                        @empty
                            <li class="list-group-item">No recent trip requests</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Available Drivers</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($vehicles ?? [] as $vehicle)
                            <li class="list-group-item">
                                <strong>{{ $vehicle->make }} {{ $vehicle->model }}</strong>
                                <br><small class="text-muted">Plate: {{ $vehicle->plate_number }}</small>
                            </li>
                        @empty
                            <li class="list-group-item">No available drivers</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 100px 0;
        margin-top: -1.5rem;
    }
    .role-card {
        transition: transform 0.3s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .role-card:hover {
        transform: translateY(-5px);
    }
    .passenger-card {
        border-left: 4px solid #28a745;
    }
    .driver-card {
        border-left: 4px solid #007bff;
    }
    .admin-card {
        border-left: 4px solid #dc3545;
    }
    .btn.disabled {
        opacity: 0.6;
        cursor: not-allowed;
        pointer-events: none;
    }
    .btn.disabled:hover {
        transform: none;
    }
</style>
@endpush

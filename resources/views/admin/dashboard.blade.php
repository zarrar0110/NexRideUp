@extends('layouts.admin')

@section('title', 'Admin Dashboard - NexRide')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="text-muted">Total Rides</h6>
                <h3>{{ $completedTrips ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="text-muted">Active Users</h6>
                <h3>{{ $activeDrivers ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="text-muted">Total Earnings</h6>
                <h3>${{ number_format($completedRevenue ?? 0, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="text-muted">System Status</h6>
                <span class="badge bg-success">All systems operational</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-3 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Total Users</h6>
                        <h3 class="mb-0">{{ $totalUsers ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x text-danger"></i>
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
                        <h6 class="card-title text-muted">Active Drivers</h6>
                        <h3 class="mb-0">{{ $activeDrivers ?? 0 }}</h3>
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
                        <h6 class="card-title text-muted">Total Trips</h6>
                        <h3 class="mb-0">{{ $totalTrips ?? 0 }}</h3>
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
                        <h6 class="card-title text-muted">Revenue</h6>
                        <h3 class="mb-0">${{ number_format($totalRevenue ?? 0, 2) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x text-warning"></i>
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
                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/admin/users" class="btn btn-danger">
                        <i class="fas fa-users me-2"></i>Manage Users
                    </a>
                    <a href="/admin/drivers" class="btn btn-outline-primary">
                        <i class="fas fa-car me-2"></i>Manage Drivers
                    </a>
                    <a href="/admin/trips" class="btn btn-outline-success">
                        <i class="fas fa-route me-2"></i>View All Trips
                    </a>
                    <a href="/admin/reports" class="btn btn-outline-warning">
                        <i class="fas fa-chart-bar me-2"></i>Generate Reports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Activity</h5>
                <a href="/admin/reports" class="btn btn-sm btn-outline-primary">View Reports</a>
            </div>
            <div class="card-body">
                @if(isset($recentActivity) && count($recentActivity) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>User</th>
                                    <th>Details</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentActivity as $activity)
                                <tr>
                                    <td>
                                        @if($activity->type === 'trip_completed')
                                            <span class="badge bg-success">Trip Completed</span>
                                        @elseif($activity->type === 'user_registered')
                                            <span class="badge bg-info">User Registered</span>
                                        @elseif($activity->type === 'payment_received')
                                            <span class="badge bg-warning">Payment</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $activity->type }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $activity->user_name ?? 'System' }}</td>
                                    <td>{{ $activity->description ?? 'N/A' }}</td>
                                    <td>{{ $activity->created_at ? $activity->created_at->format('M d, H:i') : 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No recent activity to display.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Pending Trip Requests -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Pending Trip Requests</h5>
                <a href="/admin/trip-requests" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if(isset($pendingTripRequests) && count($pendingTripRequests) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($pendingTripRequests as $request)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $request->pickup_location }} → {{ $request->dropoff_location }}</h6>
                                    <small class="text-muted">
                                        Passenger: {{ $request->passenger->user->name ?? 'N/A' }} | 
                                        {{ $request->created_at ? $request->created_at->format('M d, H:i') : 'N/A' }}
                                    </small>
                                </div>
                                <span class="badge bg-warning">Pending</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-list fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No pending trip requests.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Recent Payments</h5>
                <a href="/admin/payments" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if(isset($recentPayments) && count($recentPayments) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentPayments as $payment)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Trip #{{ $payment->trip_id ?? 'N/A' }}</h6>
                                    <small class="text-muted">
                                        {{ $payment->payment_method ?? 'N/A' }} | 
                                        {{ $payment->created_at ? $payment->created_at->format('M d, H:i') : 'N/A' }}
                                    </small>
                                </div>
                                <span class="badge bg-success">${{ number_format($payment->amount ?? 0, 2) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No recent payments.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- System Overview -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>System Overview</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-user fa-2x text-success mb-2"></i>
                            <h4>{{ $passengerCount ?? 0 }}</h4>
                            <p class="text-muted mb-0">Passengers</p>
                        </div>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-car fa-2x text-primary mb-2"></i>
                            <h4>{{ $driverCount ?? 0 }}</h4>
                            <p class="text-muted mb-0">Drivers</p>
                        </div>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-car-side fa-2x text-info mb-2"></i>
                            <h4>{{ $vehicleCount ?? 0 }}</h4>
                            <p class="text-muted mb-0">Vehicles</p>
                        </div>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-star fa-2x text-warning mb-2"></i>
                            <h4>{{ $reviewCount ?? 0 }}</h4>
                            <p class="text-muted mb-0">Reviews</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
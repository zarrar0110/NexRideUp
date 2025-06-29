<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @php
        $role = auth()->user()->role ?? null;
        $dashboardUrl = $role === 'driver' ? '/driver/dashboard' : ($role === 'passenger' ? '/passenger/dashboard' : ($role === 'admin' ? '/admin/dashboard' : '/'));
    @endphp
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">All Trip Requests</h2>
            <a href="{{ $role === 'driver' ? '/driver/dashboard' : ($role === 'passenger' ? '/passenger/dashboard' : ($role === 'admin' ? '/admin/dashboard' : '/')) }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Back to Dashboard</a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Passenger</th>
                    <th>Pickup</th>
                    <th>Dropoff</th>
                    <th>Seats</th>
                    <th>Status</th>
                    <th>Estimated Fare</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tripRequests as $request)
                    <tr>
                        <td>{{ $request->id }}</td>
                        <td>{{ $request->passenger->user->name ?? 'N/A' }}</td>
                        <td>{{ $request->pickup_location }}</td>
                        <td>{{ $request->dropoff_location }}</td>
                        <td>{{ $request->seats }}</td>
                        <td>{{ $request->status }}</td>
                        <td>{{ $request->estimated_fare }}</td>
                        <td>
                            @if(strtolower($request->status) === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @else
                                <span class="badge bg-success">Accepted</span>
                            @endif
                            @php
                                $role = auth()->user()->role ?? null;
                                $viewUrl = $role === 'driver' ? url('/driver/trip-requests/' . $request->id . '/view') :
                                            ($role === 'passenger' ? url('/passenger/trip-requests/' . $request->id . '/view') :
                                            ($role === 'admin' ? url('/admin/trip-requests/' . $request->id . '/view') : '#'));
                            @endphp
                            <a href="{{ $viewUrl }}" class="btn btn-outline-primary btn-sm ms-1" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html> 
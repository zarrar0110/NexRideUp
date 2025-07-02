<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        @php
            $role = auth()->check() ? auth()->user()->role : null;
            $dashboardUrl = $role === 'admin' ? '/admin/dashboard' : ($role === 'driver' ? '/driver/dashboard' : ($role === 'passenger' ? '/passenger/dashboard' : '/'));
        @endphp
        <a href="{{ $dashboardUrl }}" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left me-1"></i> Back to Dashboard</a>
        <h2>All Vehicles</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Driver</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Plate Number</th>
                    <th>Capacity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehicles as $vehicle)
                    <tr>
                        <td>{{ $vehicle->id }}</td>
                        <td>{{ $vehicle->driver->user->name ?? 'N/A' }}</td>
                        <td>{{ $vehicle->make }}</td>
                        <td>{{ $vehicle->model }}</td>
                        <td>{{ $vehicle->year }}</td>
                        <td>{{ $vehicle->plate_number }}</td>
                        <td>{{ $vehicle->capacity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html> 
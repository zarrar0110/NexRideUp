<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trips</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>All Trips</h2>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Passenger</th>
                    <th>Driver</th>
                    <th>Pickup</th>
                    <th>Dropoff</th>
                    <th>Status</th>
                    <th>Fare</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trips as $trip)
                    <tr>
                        <td>{{ $trip->id }}</td>
                        <td>{{ $trip->tripRequest->passenger->user->name ?? 'N/A' }}</td>
                        <td>{{ $trip->driver->user->name ?? 'N/A' }}</td>
                        <td>{{ $trip->tripRequest->pickup_location ?? 'N/A' }}</td>
                        <td>{{ $trip->tripRequest->dropoff_location ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $trip->status === 'completed' ? 'success' : ($trip->status === 'finished' ? 'info' : 'warning') }}">
                                {{ ucfirst($trip->status) }}
                            </span>
                        </td>
                        <td>{{ $trip->fare }}</td>
                        <td>
                            @if($trip->status === 'completed' && auth()->user()->isPassenger())
                                <form method="POST" action="{{ url('/passenger/trips/' . $trip->id . '/finish') }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Mark as Finished</button>
                                </form>
                            @elseif($trip->status === 'accepted' && auth()->user()->isDriver())
                                <form method="POST" action="{{ url('/driver/trips/' . $trip->id . '/complete') }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Mark as Completed</button>
                                </form>
                            @else
                                <span class="text-muted">No actions</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html> 
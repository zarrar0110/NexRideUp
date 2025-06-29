<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="/passenger/dashboard" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left me-1"></i> Back to Dashboard</a>
        <h2>Add Review</h2>
        <form method="POST" action="{{ url('/reviews') }}">
            @csrf
            <div class="mb-3">
                <label for="trip_id" class="form-label">Trip</label>
                <select class="form-select" id="trip_id" name="trip_id" required>
                    <option value="">Select Trip</option>
                    @foreach($trips as $trip)
                        <option value="{{ $trip->id }}">
                            Trip #{{ $trip->id }} - Passenger: {{ $trip->tripRequest->passenger->user->name ?? 'N/A' }}, Driver: {{ $trip->driver->user->name ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="passenger_id" class="form-label">Passenger</label>
                <select class="form-select" id="passenger_id" name="passenger_id" required>
                    <option value="">Select Passenger</option>
                    @foreach($passengers as $passenger)
                        <option value="{{ $passenger->id }}">{{ $passenger->user->name }} (ID: {{ $passenger->id }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="driver_id" class="form-label">Driver</label>
                <select class="form-select" id="driver_id" name="driver_id" required>
                    <option value="">Select Driver</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}">{{ $driver->user->name }} (ID: {{ $driver->id }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="rating" class="form-label">Rating</label>
                <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
            </div>
            <div class="mb-3">
                <label for="comment" class="form-label">Comment</label>
                <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Review</button>
        </form>
    </div>
</body>
</html> 
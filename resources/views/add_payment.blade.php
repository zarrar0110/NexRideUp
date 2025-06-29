<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add Payment</h2>
        <form method="POST" action="{{ url('/payments') }}">
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
                <label for="amount" class="form-label">Amount</label>
                <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
            </div>
            <div class="mb-3">
                <label for="method" class="form-label">Payment Method</label>
                <input type="text" class="form-control" id="method" name="method" required>
            </div>
            <div class="mb-3">
                <label for="paid_at" class="form-label">Paid At</label>
                <input type="datetime-local" class="form-control" id="paid_at" name="paid_at" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Payment</button>
        </form>
    </div>
</body>
</html> 
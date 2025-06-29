<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="/passenger/dashboard" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left me-1"></i> Back to Dashboard</a>
        <h2>All Reviews</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Trip</th>
                    <th>Passenger</th>
                    <th>Driver</th>
                    <th>Rating</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                    <tr>
                        <td>{{ $review->id }}</td>
                        <td>
                            Trip #{{ $review->trip->id ?? 'N/A' }}<br>
                            Passenger: {{ $review->trip->tripRequest->passenger->user->name ?? 'N/A' }}<br>
                            Driver: {{ $review->trip->driver->user->name ?? 'N/A' }}
                        </td>
                        <td>{{ $review->passenger->user->name ?? 'N/A' }}</td>
                        <td>{{ $review->driver->user->name ?? 'N/A' }}</td>
                        <td>{{ $review->rating }}</td>
                        <td>{{ $review->comment }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html> 
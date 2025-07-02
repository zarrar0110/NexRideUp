<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        @php
            $role = auth()->check() ? auth()->user()->role : null;
            $dashboardUrl = $role === 'admin' ? '/admin/dashboard' : ($role === 'driver' ? '/driver/dashboard' : ($role === 'passenger' ? '/passenger/dashboard' : '/'));
        @endphp
        <a href="{{ $dashboardUrl }}" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left me-1"></i> Back to Dashboard</a>
        <h2>All Payments</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Trip</th>
                    <th>Passenger</th>
                    <th>Driver</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Paid At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>Trip #{{ $payment->trip->id ?? 'N/A' }}</td>
                        <td>{{ $payment->trip->tripRequest->passenger->user->name ?? 'N/A' }}</td>
                        <td>{{ $payment->trip->driver->user->name ?? 'N/A' }}</td>
                        <td>{{ $payment->amount }}</td>
                        <td>{{ $payment->method }}</td>
                        <td>{{ $payment->paid_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html> 
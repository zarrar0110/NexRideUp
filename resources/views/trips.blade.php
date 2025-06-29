@extends('layouts.passenger')

@section('content')
<div class="container mt-4">
    @php $role = auth()->user()->role ?? null; @endphp
    <a href="{{ $role === 'driver' ? '/driver/dashboard' : '/passenger/dashboard' }}" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left me-1"></i> Back to Dashboard</a>
    <h2 class="mb-4">My Trip History</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($trips->whereIn('status', ['completed', 'finished'])->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Pickup Location</th>
                        <th>Dropoff Location</th>
                        <th>Driver</th>
                        <th>Fare</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trips->whereIn('status', ['completed', 'finished']) as $index => $trip)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $trip->pickup_location ?? $trip->tripRequest->pickup_location ?? '-' }}</td>
                            <td>{{ $trip->dropoff_location ?? $trip->tripRequest->dropoff_location ?? '-' }}</td>
                            <td>{{ $trip->driver->user->name ?? 'N/A' }}</td>
                            <td>{{ $trip->fare ? '₨ ' . number_format($trip->fare, 0) : 'N/A' }}</td>
                            <td><span class="badge badge-success text-uppercase">{{ ucfirst($trip->status) }}</span></td>
                            <td>{{ $trip->created_at ? $trip->created_at->format('d M Y, h:i A') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">You have no completed trips yet.</div>
    @endif
</div>
@endsection 
@extends('layouts.passenger')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Trip Request Details</h2>
        <a href="/passenger/trip-requests" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Back to Trip Requests</a>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Request #{{ $tripRequest->id }}</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Passenger:</strong> {{ $tripRequest->passenger->user->name ?? 'N/A' }}</li>
                <li class="list-group-item"><strong>Pickup Location:</strong> <span id="pickup-location-display">{{ $tripRequest->pickup_location }}</span></li>
                <li class="list-group-item"><strong>Dropoff Location:</strong> <span id="dropoff-location-display">{{ $tripRequest->dropoff_location }}</span></li>
                <li class="list-group-item"><strong>Seats:</strong> {{ $tripRequest->seats }}</li>
                <li class="list-group-item"><strong>Status:</strong>
                    @if(isset($tripRequest->trip) && $tripRequest->trip->status === 'completed')
                        <span class="badge bg-info">Completed</span>
                    @elseif($tripRequest->status === 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($tripRequest->status === 'accepted')
                        <span class="badge bg-success">Accepted</span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst($tripRequest->status) }}</span>
                    @endif
                </li>
                <li class="list-group-item"><strong>Estimated Fare:</strong> {{ $tripRequest->estimated_fare }}</li>
                <li class="list-group-item"><strong>Requested At:</strong> {{ $tripRequest->created_at ? $tripRequest->created_at->format('M d, Y H:i') : 'N/A' }}</li>
                @if($tripRequest->trip)
                    <li class="list-group-item"><strong>Trip Status:</strong> {{ ucfirst($tripRequest->trip->status) }}</li>
                @endif
                @if($tripRequest->notes)
                    <li class="list-group-item"><strong>Notes:</strong> {{ $tripRequest->notes }}</li>
                @endif
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function isCoordinates(str) {
    // Simple check for 'lat,lng' format
    return /^-?\d{1,3}\.\d+,-?\d{1,3}\.\d+$/.test(str.trim());
}

function reverseGeocode(coords, elementId) {
    const [lat, lng] = coords.split(',');
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;
    const el = document.getElementById(elementId);
    el.innerText = 'Translating...';
    fetch(url, { headers: { 'Accept-Language': 'en' } })
        .then(res => res.json())
        .then(data => {
            if (data.display_name) {
                el.innerText = data.display_name;
            } else {
                el.innerText = coords;
            }
        })
        .catch(() => {
            el.innerText = coords;
        });
}

document.addEventListener('DOMContentLoaded', function() {
    const pickup = document.getElementById('pickup-location-display').innerText.trim();
    const dropoff = document.getElementById('dropoff-location-display').innerText.trim();
    if (isCoordinates(pickup)) {
        reverseGeocode(pickup, 'pickup-location-display');
    }
    if (isCoordinates(dropoff)) {
        reverseGeocode(dropoff, 'dropoff-location-display');
    }
});
</script>
@endpush 
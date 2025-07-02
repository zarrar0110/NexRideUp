@extends('layouts.admin')
@section('title', 'Ride Management')
@section('content')
<div class="container">
    <h2>Ride Management</h2>
    <table class="table">
        <thead>
            <tr><th>From</th><th>To</th><th>Driver</th><th>Passenger</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($trips as $trip)
            <tr>
                <td>{{ $trip->tripRequest->pickup_location ?? 'N/A' }}</td>
                <td>{{ $trip->tripRequest->dropoff_location ?? 'N/A' }}</td>
                <td>{{ $trip->driver->user->name ?? 'N/A' }}</td>
                <td>{{ $trip->tripRequest->passenger->user->name ?? 'N/A' }}</td>
                <td>{{ ucfirst($trip->status) }}</td>
                <td><button class="btn btn-sm btn-danger">Force Cancel</button></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 
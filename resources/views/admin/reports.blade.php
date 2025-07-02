@extends('layouts.admin')
@section('title', 'Reports & Analytics')
@section('content')
<div class="container">
    <h2>Reports & Analytics</h2>
    <div class="row mb-4">
        <div class="col-md-4"><div class="card"><div class="card-body"><h6>Daily Rides</h6><h3>{{ $dailyRides ?? 0 }}</h3></div></div></div>
        <div class="col-md-4"><div class="card"><div class="card-body"><h6>Weekly Rides</h6><h3>{{ $weeklyRides ?? 0 }}</h3></div></div></div>
        <div class="col-md-4"><div class="card"><div class="card-body"><h6>Monthly Rides</h6><h3>{{ $monthlyRides ?? 0 }}</h3></div></div></div>
    </div>
    <h4>Feedback Summaries</h4>
    <table class="table">
        <thead><tr><th>User</th><th>Feedback</th><th>Rating</th></tr></thead>
        <tbody>
            @foreach($reviews as $review)
            <tr>
                <td>{{ $review->trip->tripRequest->passenger->user->name ?? 'N/A' }}</td>
                <td>{{ $review->comment ?? 'N/A' }}</td>
                <td>{{ $review->rating ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 
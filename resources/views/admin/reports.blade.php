@extends('layouts.admin')
@section('title', 'Reports & Analytics')
@section('content')
<div class="container">
    <h2>Reports & Analytics</h2>
    <div class="row mb-4">
        <div class="col-md-4"><div class="card"><div class="card-body"><h6>Daily Rides</h6><h3>12</h3></div></div></div>
        <div class="col-md-4"><div class="card"><div class="card-body"><h6>Weekly Rides</h6><h3>80</h3></div></div></div>
        <div class="col-md-4"><div class="card"><div class="card-body"><h6>Monthly Rides</h6><h3>320</h3></div></div></div>
    </div>
    <h4>Feedback Summaries</h4>
    <table class="table">
        <thead><tr><th>User</th><th>Feedback</th><th>Rating</th></tr></thead>
        <tbody>
            <tr><td>John Smith</td><td>Great ride!</td><td>5</td></tr>
        </tbody>
    </table>
</div>
@endsection 
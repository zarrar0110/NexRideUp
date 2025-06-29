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
            <tr><td>Location A</td><td>Location B</td><td>Jane Doe</td><td>John Smith</td><td>Ongoing</td><td><button class="btn btn-sm btn-danger">Force Cancel</button></td></tr>
        </tbody>
    </table>
</div>
@endsection 
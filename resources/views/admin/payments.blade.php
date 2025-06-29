@extends('layouts.admin')
@section('title', 'Payment Management')
@section('content')
<div class="container">
    <h2>Payment Management</h2>
    <table class="table">
        <thead>
            <tr><th>Driver</th><th>Amount</th><th>Date</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <tr><td>Jane Doe</td><td>$100</td><td>2024-06-28</td><td>Pending</td><td><button class="btn btn-sm btn-success">Payout</button></td></tr>
        </tbody>
    </table>
    <div class="mt-4">
        <h5>Commission Settings</h5>
        <form class="row g-2">
            <div class="col-auto">
                <input type="number" class="form-control" value="10" min="0" max="100"> %
            </div>
            <div class="col-auto">
                <button class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection 
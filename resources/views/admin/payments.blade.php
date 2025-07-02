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
            @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->trip->driver->user->name ?? 'N/A' }}</td>
                <td>${{ number_format($payment->amount, 2) }}</td>
                <td>{{ $payment->created_at ? $payment->created_at->format('Y-m-d') : 'N/A' }}</td>
                <td>{{ $payment->paid_at ? 'Paid' : 'Pending' }}</td>
                <td><button class="btn btn-sm btn-success">Payout</button></td>
            </tr>
            @endforeach
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
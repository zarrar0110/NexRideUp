@extends('layouts.admin')
@section('title', 'Passenger Management')
@section('content')
<div class="container">
    <h2>Passenger Management</h2>
    <table class="table">
        <thead>
            <tr><th>Name</th><th>Email</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($passengers as $passenger)
            <tr>
                <td>{{ $passenger->user->name ?? 'N/A' }}</td>
                <td>{{ $passenger->user->email ?? 'N/A' }}</td>
                <td>Active</td>
                <td>
                    <button class="btn btn-sm btn-primary">Edit</button>
                    <button class="btn btn-sm btn-danger">Delete</button>
                    <button class="btn btn-sm btn-warning">Suspend</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 
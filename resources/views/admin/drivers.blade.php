@extends('layouts.admin')
@section('title', 'Driver Management')
@section('content')
<div class="container">
    <h2>Driver Management</h2>
    <table class="table">
        <thead>
            <tr><th>Name</th><th>License</th><th>Experience</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($drivers as $driver)
            <tr>
                <td>{{ $driver->user->name ?? 'N/A' }}</td>
                <td>{{ $driver->license_number ?? 'N/A' }}</td>
                <td>{{ $driver->experience_years ? $driver->experience_years . ' years' : 'N/A' }}</td>
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
@extends('layouts.admin')
@section('title', 'User Management')
@section('content')
<div class="container">
    <h2>User Management</h2>
    <table class="table">
        <thead>
            <tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <tr><td>Jane Doe</td><td>jane@example.com</td><td>Driver</td><td>Active</td><td><button class="btn btn-sm btn-primary">Edit</button> <button class="btn btn-sm btn-danger">Delete</button> <button class="btn btn-sm btn-warning">Suspend</button></td></tr>
        </tbody>
    </table>
</div>
@endsection 
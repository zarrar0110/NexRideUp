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
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>{{ $user->is_online ? 'Online' : 'Offline' }}</td>
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
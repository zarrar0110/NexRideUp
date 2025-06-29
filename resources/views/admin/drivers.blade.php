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
            <tr><td>Jane Doe</td><td>ABC123</td><td>5 years</td><td>Active</td><td><button class="btn btn-sm btn-primary">Edit</button> <button class="btn btn-sm btn-danger">Delete</button> <button class="btn btn-sm btn-warning">Suspend</button></td></tr>
        </tbody>
    </table>
</div>
@endsection 
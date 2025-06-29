@extends('layouts.admin')
@section('title', 'Settings')
@section('content')
<div class="container">
    <h2>App Settings</h2>
    <form class="mb-4">
        <div class="mb-3">
            <label class="form-label">App Name</label>
            <input type="text" class="form-control" value="Ride Sharing">
        </div>
        <div class="mb-3">
            <label class="form-label">Support Email</label>
            <input type="email" class="form-control" value="support@example.com">
        </div>
        <button class="btn btn-primary">Save Settings</button>
    </form>
    <h4>Push Notification Manager</h4>
    <p>(Stub for push notification management UI)</p>
    <h4>Service Area Geofencing</h4>
    <p>(Stub for geofencing map and controls)</p>
</div>
@endsection 
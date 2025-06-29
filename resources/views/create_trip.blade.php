<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Trip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Create Trip (Accept Request)</h2>
        
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <!-- Validation Errors -->
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ url('/trips') }}">
            @csrf
            <input type="hidden" name="request_id" value="{{ $request_id }}">
            <div class="mb-3">
                <label for="driver_id" class="form-label">Driver</label>
                <select class="form-select" id="driver_id" name="driver_id" required>
                    <option value="">Select Driver</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}">{{ $driver->user->name }} (ID: {{ $driver->id }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="start_time" class="form-label">Start Time</label>
                <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
            </div>
            <div class="mb-3">
                <label for="fare" class="form-label">Fare</label>
                <input type="number" step="0.01" class="form-control" id="fare" name="fare" required>
            </div>
            <div class="mb-3">
                <label for="tip" class="form-label">Tip</label>
                <input type="number" step="0.01" class="form-control" id="tip" name="tip">
            </div>
            <button type="submit" class="btn btn-primary">Create Trip</button>
        </form>
    </div>
</body>
</html> 
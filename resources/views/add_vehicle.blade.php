<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vehicle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add Vehicle</h2>
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
        <form method="POST" action="{{ route('driver.vehicles.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Driver</label>
                <input type="text" class="form-control" value="{{ $driver->user->name }} (ID: {{ $driver->id }})" disabled>
                <input type="hidden" name="driver_id" value="{{ $driver->id }}">
            </div>
            <div class="mb-3">
                <label for="make" class="form-label">Make</label>
                <input type="text" class="form-control" id="make" name="make" value="{{ old('make') }}" required>
            </div>
            <div class="mb-3">
                <label for="model" class="form-label">Model</label>
                <input type="text" class="form-control" id="model" name="model" value="{{ old('model') }}" required>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Year</label>
                <input type="number" class="form-control" id="year" name="year" min="1900" max="2100" value="{{ old('year') }}" required>
            </div>
            <div class="mb-3">
                <label for="color" class="form-label">Color</label>
                <input type="text" class="form-control" id="color" name="color" value="{{ old('color') }}" required>
            </div>
            <div class="mb-3">
                <label for="plate_number" class="form-label">Plate Number</label>
                <input type="text" class="form-control" id="plate_number" name="plate_number" value="{{ old('plate_number') }}" required>
            </div>
            <div class="mb-3">
                <label for="capacity" class="form-label">Capacity</label>
                <input type="number" class="form-control" id="capacity" name="capacity" min="1" max="10" value="{{ old('capacity') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Vehicle</button>
        </form>
    </div>
</body>
</html> 
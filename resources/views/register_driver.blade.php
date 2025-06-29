@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-car me-2"></i>Complete Your Driver Profile
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Welcome!</strong> Please complete your driver profile to start accepting trip requests.
                    </div>

                    <form method="POST" action="{{ url('/register/driver') }}">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user_id ?? auth()->id() }}">
                        
                        <div class="mb-3">
                            <label for="license_number" class="form-label">
                                <i class="fas fa-id-card me-1"></i>Driver's License Number
                            </label>
                            <input type="text" class="form-control @error('license_number') is-invalid @enderror" 
                                   id="license_number" name="license_number" value="{{ old('license_number') }}" 
                                   placeholder="Enter your driver's license number" required>
                            @error('license_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="experience_years" class="form-label">
                                <i class="fas fa-clock me-1"></i>Years of Driving Experience
                            </label>
                            <input type="number" class="form-control @error('experience_years') is-invalid @enderror" 
                                   id="experience_years" name="experience_years" value="{{ old('experience_years') }}" 
                                   placeholder="Enter your years of driving experience" min="0" max="50" required>
                            @error('experience_years')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone me-1"></i>Phone Number
                            </label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" 
                                   placeholder="Enter your phone number" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Address
                            </label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" 
                                      placeholder="Enter your full address" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Complete Driver Profile
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <p class="text-muted mb-0">
                            <i class="fas fa-shield-alt me-1"></i>
                            Your information is secure and will only be used for ride coordination.
                        </p>
                        <small class="text-muted">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            After profile completion, you'll need to add your vehicle information.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
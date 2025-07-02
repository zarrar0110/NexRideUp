@extends('layouts.passenger')

@section('title', 'Passenger Dashboard - NexRide')

@section('content')
<style>
.stats-card {
    transition: transform 0.3s ease;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
}
.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
}
.card {
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border: none;
}
.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    border-radius: 12px 12px 0 0 !important;
}
.list-group-item {
    border-radius: 8px;
    margin-bottom: 8px;
    border: 1px solid #e9ecef;
}
.badge {
    font-size: 0.8em;
    padding: 0.5em 0.8em;
}
.btn-sm {
    border-radius: 6px;
}
</style>

<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-3 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Total Trips</h6>
                        <h3 class="mb-0">{{ $totalTrips ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-car fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Active Requests</h6>
                        <h3 class="mb-0">{{ $activeRequests ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Total Spent</h6>
                        <h3 class="mb-0">${{ number_format($totalSpent ?? 0, 2) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Reviews Given</h6>
                        <h3 class="mb-0">{{ $reviewsGiven ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-star fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/passenger/trip-requests/create" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Request New Trip
                    </a>
                    <a href="/passenger/trip-requests" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>View My Requests
                    </a>
                    <a href="/passenger/trips" class="btn btn-outline-info">
                        <i class="fas fa-car me-2"></i>My Trip History
                    </a>
                    <a href="/passenger/payments" class="btn btn-outline-warning">
                        <i class="fas fa-credit-card me-2"></i>Payment History
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Trip Requests -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-route me-2"></i>Recent Trip Requests</h5>
                <a href="/passenger/trip-requests" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if(isset($recentTripRequests) && count($recentTripRequests) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTripRequests as $request)
                                <tr>
                                    <td>{{ $request->pickup_location }}</td>
                                    <td>{{ $request->dropoff_location }}</td>
                                    <td>
                                        @if(isset($request->trip) && $request->trip->status === 'completed')
                                            <span class="badge bg-info">Completed</span>
                                        @elseif($request->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($request->status === 'accepted')
                                            <span class="badge bg-success">Accepted</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($request->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $request->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="/passenger/trip-requests/{{ $request->id }}/view" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-route fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No trip requests yet. Start by requesting your first trip!</p>
                        <a href="/passenger/trip-requests/create" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Request Trip
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Trips -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-car me-2"></i>Recent Trips</h5>
                <a href="/passenger/trips" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if(isset($recentTrips) && count($recentTrips) > 0)
                    <div class="list-group list-group-flush">
                        @php
                            // Helper to check if a trip has been reviewed by the passenger
                            function hasReviewed($trip, $recentReviews) {
                                foreach ($recentReviews as $review) {
                                    if ($review->trip_id == $trip->id) return true;
                                }
                                return false;
                            }
                        @endphp
                        @foreach($recentTrips as $trip)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $trip->pickup_location }} → {{ $trip->dropoff_location }}</h6>
                                    <small class="text-muted">
                                        Driver: {{ $trip->driver->user->name ?? 'N/A' }} | 
                                        {{ $trip->created_at->format('M d, Y') }}
                                    </small>
                                </div>
                                <span class="badge bg-success">${{ number_format($trip->fare, 2) }}</span>
                                @if($trip->status === 'completed' && !hasReviewed($trip, $recentReviews))
                                    <button class="btn btn-warning btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $trip->id }}">
                                        Give Review
                                    </button>
                                @endif
                            </div>
                        </div>
                        @if($trip->status === 'completed' && !hasReviewed($trip, $recentReviews))
                        <!-- Review Modal -->
                        <div class="modal fade" id="reviewModal{{ $trip->id }}" tabindex="-1" aria-labelledby="reviewModalLabel{{ $trip->id }}" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <form method="POST" action="{{ url('/passenger/reviews') }}">
                                @csrf
                                <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                                <input type="hidden" name="driver_id" value="{{ $trip->driver->id }}">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="reviewModalLabel{{ $trip->id }}">Review Driver: {{ $trip->driver->user->name ?? 'N/A' }}</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <div class="mb-3">
                                    <label class="form-label">Rating</label>
                                    <div class="star-rating" data-trip="{{ $trip->id }}">
                                      @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star star" data-value="{{ $i }}"></i>
                                      @endfor
                                      <input type="hidden" name="rating" value="" required>
                                    </div>
                                  </div>
                                  <div class="mb-3">
                                    <label for="comment{{ $trip->id }}" class="form-label">Comment</label>
                                    <textarea class="form-control" id="comment{{ $trip->id }}" name="comment" rows="3" required></textarea>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                  <button type="submit" class="btn btn-primary">Submit Review</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-car fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No trips completed yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Reviews -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-star me-2"></i>Recent Reviews</h5>
                <a href="/passenger/reviews" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if(isset($recentReviews) && count($recentReviews) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentReviews as $review)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-muted"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="mb-1">{{ Str::limit($review->comment, 50) }}</p>
                                    <small class="text-muted">
                                        Driver: {{ $review->driver->user->name ?? 'N/A' }} | 
                                        {{ $review->created_at->format('M d, Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-star fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No reviews yet. Rate your drivers after trips!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.star-rating').forEach(function(ratingDiv) {
    const stars = ratingDiv.querySelectorAll('.star');
    const input = ratingDiv.querySelector('input[name="rating"]');
    stars.forEach(function(star, idx) {
        star.addEventListener('click', function() {
            input.value = idx + 1;
            stars.forEach((s, i) => {
                s.classList.toggle('text-warning', i <= idx);
                s.classList.toggle('text-muted', i > idx);
            });
        });
    });
});
</script>
@endpush 
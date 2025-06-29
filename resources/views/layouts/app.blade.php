<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ride Sharing Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    @stack('styles')
    <style>
        body, html {
            font-family: 'Poppins', Arial, sans-serif;
            background: #f4f7fa;
        }
        .navbar {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%) !important;
            color: #fff;
            box-shadow: 0 2px 8px rgba(102,126,234,0.08);
            border-radius: 0 0 16px 16px;
        }
        .navbar .navbar-brand, .navbar .nav-link, .navbar .btn {
            color: #fff !important;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        .carousel-caption {
            background: rgba(102,126,234,0.7);
            border-radius: 8px;
        }
        .carousel-indicators [data-bs-target] {
            background-color: #ffd700;
        }
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }
        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
        }
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }
        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Ride Sharing</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @auth
                    @if(auth()->user()->role === 'driver')
                        <li class="nav-item"><a class="nav-link" href="/driver/dashboard">Driver Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="/driver/trip-requests">Trip Requests</a></li>
                        <li class="nav-item"><a class="nav-link" href="/driver/my-trips">My Trips</a></li>
                        <li class="nav-item"><a class="nav-link" href="/driver/vehicles">My Vehicles</a></li>
                        <li class="nav-item"><a class="nav-link" href="/driver/vehicles/create">Add Vehicle</a></li>
                        <li class="nav-item"><a class="nav-link" href="/driver/earnings">Earnings</a></li>
                        <li class="nav-item"><a class="nav-link" href="/driver/reviews">Reviews</a></li>
                    @elseif(auth()->user()->role === 'passenger')
                        <li class="nav-item"><a class="nav-link" href="/passenger/dashboard">Passenger Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="/passenger/trip-requests">My Trip Requests</a></li>
                        <li class="nav-item"><a class="nav-link" href="/passenger/trip-requests/create">Request Trip</a></li>
                        <li class="nav-item"><a class="nav-link" href="/passenger/trips">My Trips</a></li>
                        <li class="nav-item"><a class="nav-link" href="/passenger/payments">Payments</a></li>
                        <li class="nav-item"><a class="nav-link" href="/passenger/reviews">Reviews</a></li>
                        <li class="nav-item"><a class="nav-link" href="/passenger/reviews/create">Add Review</a></li>
                    @elseif(auth()->user()->role === 'admin')
                        <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Admin Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/users">Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/drivers">Drivers</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/passengers">Passengers</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/trips">Trips</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/trip-requests">Trip Requests</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/vehicles">Vehicles</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/payments">Payments</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/reviews">Reviews</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/reports">Reports</a></li>
                    @endif
                @else
                    <li class="nav-item"><a class="nav-link" href="/register">Register</a></li>
                @endauth
            </ul>
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            @if(auth()->user()->role === 'driver')
                                <li><a class="dropdown-item" href="/driver/profile">Profile</a></li>
                            @elseif(auth()->user()->role === 'passenger')
                                <li><a class="dropdown-item" href="/passenger/profile">Profile</a></li>
                            @elseif(auth()->user()->role === 'admin')
                                <li><a class="dropdown-item" href="/admin/settings">Settings</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
<!-- Slider/Carousel -->
<div id="mainSlider" class="carousel slide mb-4" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#mainSlider" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#mainSlider" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#mainSlider" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="https://images.unsplash.com/photo-1511918984145-48de785d4c4e?auto=format&fit=crop&w=900&q=80" class="d-block w-100" style="max-height:220px; object-fit:cover;" alt="Welcome Slide">
            <div class="carousel-caption d-none d-md-block">
                <h5>Welcome to Ride Sharing!</h5>
                <p>Book safe, reliable rides anytime.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=900&q=80" class="d-block w-100" style="max-height:220px; object-fit:cover;" alt="Feature Slide">
            <div class="carousel-caption d-none d-md-block">
                <h5>All-in-One Platform</h5>
                <p>Drivers, passengers, and admins—manage everything in one place.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=900&q=80" class="d-block w-100" style="max-height:220px; object-fit:cover;" alt="Promo Slide">
            <div class="carousel-caption d-none d-md-block">
                <h5>Special Offers</h5>
                <p>Check out our latest promotions and discounts!</p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#mainSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#mainSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
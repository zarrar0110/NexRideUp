<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Driver Dashboard') - NexRide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    @stack('styles')
    <style>
        body, html {
            font-family: 'Poppins', Arial, sans-serif;
            background: #f4f7fa;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.85);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 0;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #ffd700;
            background: rgba(255,255,255,0.12);
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .top-navbar {
            background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);
            color: #fff;
            box-shadow: 0 2px 8px rgba(0,123,255,0.08);
            border-radius: 0 0 16px 16px;
        }
        .top-navbar h5, .top-navbar span, .top-navbar a.btn {
            color: #fff !important;
        }
        .carousel-caption {
            background: rgba(0,123,255,0.7);
            border-radius: 8px;
        }
        .carousel-indicators [data-bs-target] {
            background-color: #ffd700;
        }
        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <div class="p-3 text-center">
                        <h4 class="text-white mb-0" style="font-weight: bold; font-size: 2rem; background: linear-gradient(90deg, #ffb347 0%, #ffcc33 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-shadow: 1px 1px 8px rgba(255,179,71,0.2); letter-spacing: 2px;">NexRide</h4>
                        <small class="text-white-50" style="font-weight: 600; letter-spacing: 1px;">Driver Portal</small>
                    </div>
                    <hr class="text-white-50">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('driver/dashboard') ? 'active' : '' }}" href="/driver/dashboard">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('driver/trip-requests*') ? 'active' : '' }}" href="/driver/trip-requests">
                                <i class="fas fa-list me-2"></i> Available Trip Requests
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('driver/my-trips*') ? 'active' : '' }}" href="/driver/my-trips">
                                <i class="fas fa-car me-2"></i> My Active Trips
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('driver/trips*') ? 'active' : '' }}" href="/driver/trips">
                                <i class="fas fa-history me-2"></i> Trip History
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('driver/vehicles*') ? 'active' : '' }}" href="/driver/vehicles">
                                <i class="fas fa-car-side me-2"></i> My Vehicles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('driver/vehicles/create') ? 'active' : '' }}" href="/driver/vehicles/create">
                                <i class="fas fa-plus me-2"></i> Add Vehicle
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('driver/earnings*') ? 'active' : '' }}" href="/driver/earnings">
                                <i class="fas fa-dollar-sign me-2"></i> Earnings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('driver/reviews*') ? 'active' : '' }}" href="/driver/reviews">
                                <i class="fas fa-star me-2"></i> My Reviews
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('driver/profile*') ? 'active' : '' }}" href="/driver/profile">
                                <i class="fas fa-user me-2"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item mt-3">
                            <a href="#" class="nav-link text-warning" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-0">
                <div class="main-content">
                    <!-- Top Navbar -->
                    <nav class="top-navbar p-3 mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">@yield('title', 'Driver Dashboard')</h5>
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="badge bg-success me-2">
                                        <i class="fas fa-circle me-1"></i>Online
                                    </span>
                                    <span>Welcome, {{ auth()->user()->name ?? 'Driver' }}</span>
                                </div>
                                <a href="/" class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-home me-1"></i> Home
                                </a>
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
                                    <h5>Welcome to NexRide!</h5>
                                    <p>Accept trip requests and earn more with every ride.</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=900&q=80" class="d-block w-100" style="max-height:220px; object-fit:cover;" alt="Tip Slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Tip: Keep Your Profile Updated</h5>
                                    <p>Complete your profile and vehicle info for more trip offers.</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=900&q=80" class="d-block w-100" style="max-height:220px; object-fit:cover;" alt="Promo Slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Special Bonuses</h5>
                                    <p>Check out our latest driver promotions and bonuses!</p>
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

                    <!-- Content Area -->
                    <div class="p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
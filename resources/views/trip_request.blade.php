<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request a Trip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            height: 400px;
            width: 100%;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .location-input {
            position: relative;
        }
        .location-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1000;
            display: none;
        }
        .location-option {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }
        .location-option:hover {
            background-color: #f8f9fa;
        }
        .driver-marker {
            background-color: #007bff;
            border: 2px solid white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
        }
        .pickup-marker {
            background-color: #28a745;
            border: 2px solid white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
        }
        .dropoff-marker {
            background-color: #dc3545;
            border: 2px solid white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
        }
    </style>
</head>
<body>
    @php
        $role = auth()->user()->role ?? null;
        $dashboardUrl = $role === 'driver' ? '/driver/dashboard' : ($role === 'passenger' ? '/passenger/dashboard' : ($role === 'admin' ? '/admin/dashboard' : '/'));
    @endphp
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Request a Trip</h2>
            <a href="{{ $dashboardUrl }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Back to Dashboard</a>
        </div>
        
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

        <!-- Map Selection Message -->
        <div id="map-message" class="alert alert-info d-none" role="alert"></div>
        <!-- Live Map -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Select Pickup and Dropoff Locations</h5>
            </div>
            <div class="card-body">
                <div id="map"></div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <small class="text-muted">
                            <i class="fas fa-circle text-success me-1"></i>Green: Pickup Location
                        </small>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">
                            <i class="fas fa-circle text-danger me-1"></i>Red: Dropoff Location
                        </small>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <small class="text-muted">
                            <i class="fas fa-circle text-primary me-1"></i>Blue: Online Drivers
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ url('/passenger/trip-requests') }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3 location-input">
                        <label for="pickup_location" class="form-label">
                            <i class="fas fa-map-marker-alt text-success me-1"></i>Pickup Location
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="pickup_location" name="pickup_location" value="{{ old('pickup_location') }}" placeholder="Click on map or enter location" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="showLocationOptions('pickup')">
                                <i class="fas fa-map"></i>
                            </button>
                        </div>
                        <div class="location-dropdown" id="pickup-dropdown">
                            <div class="location-option" onclick="selectLocationType('pickup', 'map')">
                                <i class="fas fa-map me-2"></i>Select from Map
                            </div>
                            <div class="location-option" onclick="selectLocationType('pickup', 'current')">
                                <i class="fas fa-map-marker-alt me-2"></i>Use Current Location
                            </div>
                            <div class="location-option" onclick="selectLocationType('pickup', 'custom')">
                                <i class="fas fa-edit me-2"></i>Enter Custom Location
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3 location-input">
                        <label for="dropoff_location" class="form-label">
                            <i class="fas fa-map-marker-alt text-danger me-1"></i>Dropoff Location
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="dropoff_location" name="dropoff_location" value="{{ old('dropoff_location') }}" placeholder="Click on map or enter location" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="showLocationOptions('dropoff')">
                                <i class="fas fa-map"></i>
                            </button>
                        </div>
                        <div class="location-dropdown" id="dropoff-dropdown">
                            <div class="location-option" onclick="selectLocationType('dropoff', 'map')">
                                <i class="fas fa-map me-2"></i>Select from Map
                            </div>
                            <div class="location-option" onclick="selectLocationType('dropoff', 'current')">
                                <i class="fas fa-map-marker-alt me-2"></i>Use Current Location
                            </div>
                            <div class="location-option" onclick="selectLocationType('dropoff', 'custom')">
                                <i class="fas fa-edit me-2"></i>Enter Custom Location
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="preferred_time" class="form-label">Preferred Time</label>
                        <select class="form-control" id="preferred_time_select" required>
                            <option value="now">Now</option>
                            <option value="15">In 15 minutes</option>
                            <option value="30">In 30 minutes</option>
                            <option value="45">In 45 minutes</option>
                            <option value="60">In 1 hour</option>
                        </select>
                        <input type="hidden" id="preferred_time" name="preferred_time" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="seats" class="form-label">Number of Seats</label>
                        <select class="form-control" id="seats" name="seats" required>
                            <option value="">Select number of seats</option>
                            <option value="1" {{ old('seats') == '1' ? 'selected' : '' }}>1 Seat</option>
                            <option value="2" {{ old('seats') == '2' ? 'selected' : '' }}>2 Seats</option>
                            <option value="3" {{ old('seats') == '3' ? 'selected' : '' }}>3 Seats</option>
                            <option value="4" {{ old('seats') == '4' ? 'selected' : '' }}>4 Seats</option>
                            <option value="5" {{ old('seats') == '5' ? 'selected' : '' }}>5 Seats</option>
                            <option value="6" {{ old('seats') == '6' ? 'selected' : '' }}>6 Seats</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-control" id="payment_method" name="payment_method" required>
                            <option value="">Select payment method</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
                            <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="digital_wallet" {{ old('payment_method') == 'digital_wallet' ? 'selected' : '' }}>Digital Wallet</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any special instructions or notes">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-car me-2"></i>Request Trip
            </button>
        </form>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map;
        let pickupMarker = null;
        let dropoffMarker = null;
        let driverMarkers = [];
        let currentLocationType = null;
        let currentLocationField = null;

        // Initialize map
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map centered on Pakistan
            map = L.map('map').setView([30.3753, 69.3451], 6);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Try to get user's current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    map.setView([lat, lng], 13);
                    
                    // Add user's current location marker
                    L.marker([lat, lng], {
                        icon: L.divIcon({
                            className: 'user-marker',
                            html: '<i class="fas fa-user" style="color: #6c757d; font-size: 20px;"></i>',
                            iconSize: [20, 20]
                        })
                    }).addTo(map).bindPopup('Your Current Location');
                }, function() {
                    console.log('Could not get current location');
                });
            }

            // Load online drivers
            loadOnlineDrivers();

            // Handle map clicks
            map.on('click', function(e) {
                if (currentLocationType === 'map') {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;
                    
                    // Reverse geocode to get address
                    reverseGeocode(lat, lng, function(address) {
                        if (currentLocationField === 'pickup') {
                            setPickupLocation(lat, lng, address);
                        } else if (currentLocationField === 'dropoff') {
                            setDropoffLocation(lat, lng, address);
                        }
                    });
                }
            });

            const select = document.getElementById('preferred_time_select');
            const hiddenInput = document.getElementById('preferred_time');
            function updateHiddenInput() {
                let dt;
                if (select.value === 'now') {
                    dt = new Date();
                } else {
                    const mins = parseInt(select.value, 10);
                    dt = new Date(Date.now() + mins * 60000);
                }
                hiddenInput.value = formatDateToMySQL(dt);
            }
            select.addEventListener('change', updateHiddenInput);
            updateHiddenInput();
            // On form submit, ensure hidden input is set
            select.form.addEventListener('submit', function(e) {
                updateHiddenInput();
            });
        });

        // Load online drivers
        function loadOnlineDrivers() {
            // Fetch real online drivers from the backend
            fetch('/passenger/online-drivers')
                .then(response => response.json())
                .then(drivers => {
                    // Clear existing driver markers
                    driverMarkers.forEach(marker => map.removeLayer(marker));
                    driverMarkers = [];

                    drivers.forEach(driver => {
                        const marker = L.marker([driver.lat, driver.lng], {
                            icon: L.divIcon({
                                className: 'driver-marker',
                                html: '<i class="fas fa-car" style="color: white; font-size: 12px; line-height: 16px;"></i>',
                                iconSize: [20, 20]
                            })
                        }).addTo(map).bindPopup(`<b>${driver.name}</b><br>${driver.vehicle}<br>Online Driver`);
                        
                        driverMarkers.push(marker);
                    });
                })
                .catch(error => {
                    console.error('Error loading online drivers:', error);
                    // Fallback to sample drivers if API fails
                    const sampleDrivers = [
                        { lat: 30.3753, lng: 69.3451, name: 'Driver 1', vehicle: 'Sample Vehicle' },
                        { lat: 30.3853, lng: 69.3551, name: 'Driver 2', vehicle: 'Sample Vehicle' },
                        { lat: 30.3653, lng: 69.3351, name: 'Driver 3', vehicle: 'Sample Vehicle' }
                    ];

                    sampleDrivers.forEach(driver => {
                        const marker = L.marker([driver.lat, driver.lng], {
                            icon: L.divIcon({
                                className: 'driver-marker',
                                html: '<i class="fas fa-car" style="color: white; font-size: 12px; line-height: 16px;"></i>',
                                iconSize: [20, 20]
                            })
                        }).addTo(map).bindPopup(`<b>${driver.name}</b><br>${driver.vehicle}<br>Online Driver`);
                        
                        driverMarkers.push(marker);
                    });
                });
        }

        // Add these URLs at the top of the <script> section:
        const greenIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
        const redIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // Set pickup location
        function setPickupLocation(lat, lng, address) {
            // Remove existing pickup marker
            if (pickupMarker) {
                map.removeLayer(pickupMarker);
            }
            // Add new pickup marker (draggable)
            pickupMarker = L.marker([lat, lng], {
                icon: greenIcon,
                draggable: true
            }).addTo(map).bindPopup('Pickup Location');
            // Update input field
            document.getElementById('pickup_location').value = address;
            // Hide message
            document.getElementById('map-message').classList.add('d-none');
            // Reset location selection
            currentLocationType = null;
            currentLocationField = null;
            hideAllDropdowns();
            // Add dragend event
            pickupMarker.on('dragend', function(e) {
                const newLatLng = e.target.getLatLng();
                reverseGeocode(newLatLng.lat, newLatLng.lng, function(newAddress) {
                    document.getElementById('pickup_location').value = newAddress;
                });
            });
        }

        // Set dropoff location
        function setDropoffLocation(lat, lng, address) {
            // Remove existing dropoff marker
            if (dropoffMarker) {
                map.removeLayer(dropoffMarker);
            }
            // Add new dropoff marker (draggable)
            dropoffMarker = L.marker([lat, lng], {
                icon: redIcon,
                draggable: true
            }).addTo(map).bindPopup('Dropoff Location');
            // Update input field
            document.getElementById('dropoff_location').value = address;
            // Hide message
            document.getElementById('map-message').classList.add('d-none');
            // Reset location selection
            currentLocationType = null;
            currentLocationField = null;
            hideAllDropdowns();
            // Add dragend event
            dropoffMarker.on('dragend', function(e) {
                const newLatLng = e.target.getLatLng();
                reverseGeocode(newLatLng.lat, newLatLng.lng, function(newAddress) {
                    document.getElementById('dropoff_location').value = newAddress;
                });
            });
        }

        // Show location options dropdown
        function showLocationOptions(field) {
            hideAllDropdowns();
            currentLocationField = field;
            document.getElementById(field + '-dropdown').style.display = 'block';
        }

        // Select location type
        function selectLocationType(field, type) {
            currentLocationType = type;
            currentLocationField = field;
            const messageDiv = document.getElementById('map-message');
            if (type === 'map') {
                document.body.style.cursor = 'crosshair';
                messageDiv.textContent = 'Click on the map to select ' + field + ' location.';
                messageDiv.classList.remove('d-none');
            } else if (type === 'current') {
                // Use browser geolocation
                if (navigator.geolocation) {
                    messageDiv.textContent = 'Getting your current location...';
                    messageDiv.classList.remove('d-none');
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        reverseGeocode(lat, lng, function(address) {
                            if (field === 'pickup') {
                                setPickupLocation(lat, lng, address);
                            } else if (field === 'dropoff') {
                                setDropoffLocation(lat, lng, address);
                            }
                        });
                    }, function(error) {
                        messageDiv.textContent = 'Unable to get your current location.';
                        messageDiv.classList.remove('d-none');
                    });
                } else {
                    messageDiv.textContent = 'Geolocation is not supported by your browser.';
                    messageDiv.classList.remove('d-none');
                }
            } else if (type === 'custom') {
                document.getElementById(field + '_location').focus();
                messageDiv.classList.add('d-none');
            }
            hideAllDropdowns();
        }

        // Hide all dropdowns
        function hideAllDropdowns() {
            document.querySelectorAll('.location-dropdown').forEach(dropdown => {
                dropdown.style.display = 'none';
            });
            document.body.style.cursor = 'default';
        }

        // Reverse geocoding using OpenStreetMap Nominatim API
        function reverseGeocode(lat, lng, callback) {
            const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;
            fetch(url, { headers: { 'Accept-Language': 'en' } })
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        callback(data.display_name);
                    } else {
                        callback(`Address not found (${lat.toFixed(6)}, ${lng.toFixed(6)})`);
                    }
                })
                .catch(() => {
                    callback(`Address not found (${lat.toFixed(6)}, ${lng.toFixed(6)})`);
                });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.location-input')) {
                hideAllDropdowns();
            }
        });

        function formatDateToMySQL(dt) {
            const pad = n => n < 10 ? '0' + n : n;
            return dt.getFullYear() + '-' + pad(dt.getMonth() + 1) + '-' + pad(dt.getDate()) + ' '
                + pad(dt.getHours()) + ':' + pad(dt.getMinutes()) + ':' + pad(dt.getSeconds());
        }
    </script>
</body>
</html> 
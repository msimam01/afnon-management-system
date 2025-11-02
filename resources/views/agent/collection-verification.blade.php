@extends('layouts.layout')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Collection Verification</h1>
                        <p class="mt-2 text-lg text-gray-600">Farmer: {{ $application->farmer->full_name }}</p>
                        <p class="text-sm text-gray-500">Reference: {{ $application->reference_number }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('agent.verify.collection') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back to List
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <!-- Farmer Info Section (Left) -->
                <div class="xl:col-span-1 space-y-6">
                    <!-- Farmer Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-4">
                                <span class="text-white text-lg font-bold">{{ substr($application->farmer->full_name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">{{ $application->farmer->full_name }}</h3>
                                <p class="text-gray-600">{{ $application->farmer->registration_number }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="border-t pt-4">
                                <dl class="space-y-3">
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                        <dd class="text-sm text-gray-900">{{ $application->farmer->phone }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">BVN</dt>
                                        <dd class="text-sm font-mono text-gray-900">{{ $application->farmer->bvn ?? 'N/A' }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Farm Size</dt>
                                        <dd class="text-sm text-gray-900">{{ $application->farm->size }} hectares</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Season</dt>
                                        <dd class="text-sm text-gray-900">{{ $application->season->name }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Loan Type</dt>
                                        <dd class="text-sm text-blue-600 capitalize">{{ $application->season->loan_type ?? 'Complete Loan' }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Summary Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Financial Summary</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <dt class="text-sm font-medium text-blue-700">Total Loan</dt>
                                <dd class="text-lg font-bold text-blue-900">₦{{ number_format($application->total_loan, 0) }}</dd>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg">
                                <dt class="text-sm font-medium text-orange-700">Insurance</dt>
                                <dd class="text-sm font-semibold text-orange-900">{{ $application->insurance_rate }}% (₦{{ number_format($application->insurance_amount, 0) }})</dd>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                                <dt class="text-sm font-medium text-green-700">Organization Contribution</dt>
                                <dd class="text-lg font-bold text-green-900">₦{{ number_format($application->equity, 0) }}</dd>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-emerald-50 rounded-lg">
                                <dt class="text-sm font-medium text-emerald-700">Farmer Contribution</dt>
                                <dd class="text-lg font-bold text-emerald-900">₦{{ number_format($application->disbursed_amount, 0) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Commodity Details Section (Center) -->
                <div class="xl:col-span-2 space-y-6">
                    <!-- Commodity Allocations -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Commodity Allocations & Collection</h3>

                        <form id="collectionForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="application_id" value="{{ $application->id }}" />

                            <!-- Hidden location fields -->
                            <input type="hidden" name="location_lat" id="location_lat">
                            <input type="hidden" name="location_lng" id="location_lng">

                            <!-- Commodity List -->
                            <div class="space-y-4 mb-8">
                                <h4 class="text-lg font-medium text-gray-800 mb-4">Allocated Commodities</h4>
                                @foreach($application->commodity_allocations as $allocation)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-500 rounded-lg flex items-center justify-center mr-3">
                                                <span class="text-white text-sm font-bold">{{ substr($allocation->commodity_name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h5 class="font-semibold text-gray-900">{{ $allocation->commodity_name }}</h5>
                                                <p class="text-sm text-gray-600">{{ $allocation->qty_per_hectare }} per hectare</p>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <p class="text-2xl font-bold text-blue-600">{{ $allocation->allocated_quantity }}</p>
                                            <p class="text-sm text-gray-600">Allocated</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Collected Quantity *</label>
                                            <input type="number" name="collected_quantities[{{ $allocation->id }}]"
                                                   min="0" max="{{ $allocation->allocated_quantity }}"
                                                   step="0.01" required
                                                   placeholder="Enter collected"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <p class="text-xs text-gray-500 mt-1">Max: {{ $allocation->allocated_quantity }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Collection Photo -->
                            <div class="border-t pt-6">
                                <label class="block text-lg font-semibold text-gray-900 mb-4">Collection Photo *</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-500 transition-colors">
                                    <input type="file" name="photo" id="collectionPhoto" accept="image/*" required hidden>
                                    <div class="cursor-pointer" onclick="document.getElementById('collectionPhoto').click()">
                                        <div class="w-16 h-16 bg-gray-100 mx-auto rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.828-1.472A2 2 0 0110.153 4h3.694a2 2 0 011.664.89l.828 1.472A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-600 font-medium">Click to upload collection photo</p>
                                        <p class="text-sm text-gray-500">PNG, JPG up to 4MB</p>
                                    </div>
                                    <img id="collectionPhotoPreview" class="mt-4 w-full h-64 object-cover rounded-lg hidden border">
                                </div>
                                <button type="button" id="capturePhoto" class="mt-3 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.828-1.472A2 2 0 0110.153 4h3.694a2 2 0 011.664.89l.828 1.472A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Or Capture Photo
                                </button>
                            </div>

                            <!-- Collection Notes -->
                            <div class="mt-6">
                                <label class="block text-lg font-semibold text-gray-900 mb-4">Collection Notes</label>
                                <textarea name="collection_notes" rows="4"
                                          placeholder="Add any notes about the collection..."
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>

                            <!-- Signature Section -->
                            <div class="mt-8 border-t pt-8">
                                <h3 class="text-xl font-semibold text-gray-900 mb-6">Farmer Signature</h3>

                                <!-- Signature Canvas -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Draw Signature *</label>
                                    <div class="border-2 border-gray-300 rounded-lg p-4 bg-white">
                                        <canvas id="signatureCanvas" class="w-full h-64 border border-gray-200 rounded" style="touch-action: none;"></canvas>
                                        <div class="flex justify-between items-center mt-3">
                                            <p class="text-sm text-gray-600">Sign here using mouse, touch, or stylus</p>
                                            <button type="button" id="clearSignature" class="text-sm text-red-600 hover:text-red-800 underline">Clear</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Alternative Signature Upload -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Or Upload Signature Image</label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500">
                                        <input type="file" name="signature_image" id="signatureImage" accept="image/*" hidden>
                                        <div class="cursor-pointer" onclick="document.getElementById('signatureImage').click()">
                                            <div class="w-10 h-10 bg-gray-100 mx-auto rounded-full flex items-center justify-center mb-2">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                            </div>
                                            <p class="text-sm text-gray-600">Click to upload signature image</p>
                                        </div>
                                        <img id="signatureImagePreview" class="mt-4 max-w-full h-20 object-contain hidden border border-gray-300 rounded">
                                    </div>
                                </div>

                                <div id="signatureValidationMessage" class="hidden bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                                    <p class="text-sm text-red-700">Please provide a signature before submitting.</p>
                                </div>

                                <!-- Hidden signature data -->
                                <input type="hidden" name="signature_data" id="signatureData">
                                <input type="hidden" name="signature_type" id="signatureType">
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-8 pt-6 border-t flex justify-between">
                                <a href="{{ route('agent.verify.collection') }}"
                                   class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancel
                                </a>
                                <div class="space-x-3">
                                    <button type="button" id="printVerificationSlip"
                                            class="inline-flex items-center px-6 py-3 border border-blue-500 rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h8z"></path>
                                        </svg>
                                        Print Slip
                                    </button>
                                    <button type="submit" id="submitButton"
                                            class="inline-flex items-center px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Submit Verification
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Location Permission Modal -->
    <div id="locationModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Location Required</h2>
                </div>

                <div class="mb-6">
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Please allow location access to verify your collection activity. Your location is securely recorded with the verification for audit purposes and to prevent fraudulent collection attempts.
                    </p>
                </div>

                <div class="flex space-x-3">
                    <button id="allowLocationBtn" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <div class="flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2 animate-spin hidden" id="locationSpinner" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span id="allowLocationText">Allow Location</span>
                        </div>
                    </button>
                </div>

                <div class="mt-4 text-xs text-gray-500 text-center">
                    Your location data is securely transmitted and stored only for verification purposes.
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('collectionForm');
            const submitButton = document.getElementById('submitButton');
            const canvas = document.getElementById('signatureCanvas');
            const ctx = canvas.getContext('2d');
            const clearSignatureBtn = document.getElementById('clearSignature');
            const collectionPhotoInput = document.getElementById('collectionPhoto');
            const collectionPhotoPreview = document.getElementById('collectionPhotoPreview');
            const signatureImageInput = document.getElementById('signatureImage');
            const signatureImagePreview = document.getElementById('signatureImagePreview');
            const signatureValidationMessage = document.getElementById('signatureValidationMessage');
            const locationLatInput = document.getElementById('location_lat');
            const locationLngInput = document.getElementById('location_lng');

            let isDrawing = false;
            let locationAcquired = false;
            const locationModal = document.getElementById('locationModal');
            const allowLocationBtn = document.getElementById('allowLocationBtn');
            const locationSpinner = document.getElementById('locationSpinner');
            const allowLocationText = document.getElementById('allowLocationText');

            // Function to determine if we should use mock coordinates for development/testing
            function shouldUseMockCoordinates() {
                // Check if running on localhost or non-HTTPS
                if (window.location.hostname === 'localhost' ||
                    window.location.hostname === '127.0.0.1' ||
                    window.location.protocol !== 'https:') {
                    return true;
                }

                // Check if this is a development/staging tenant domain pattern
                if (window.location.hostname.includes('local') ||
                    window.location.hostname.includes('dev') ||
                    window.location.hostname.includes('staging') ||
                    window.location.hostname.includes('test')) {
                    return true;
                }

                // For multi-tenant setups where the tenant key suggests development
                const tenantDomain = window.location.hostname.split('.')[0] || '';
                if (tenantDomain.includes('test') ||
                    tenantDomain.includes('dev') ||
                    tenantDomain.includes('demo') ||
                    tenantDomain.length < 3) {  // Short tenant names often indicate test setups
                    return true;
                }

                // Could also check for query parameters or local storage flags
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('mock_location') ||
                    urlParams.has('dev_mode') ||
                    localStorage.getItem('afnon_dev_mode') === 'true') {
                    return true;
                }

                return false;
            }

            // Get user's location using geolocation API
            function getUserLocation() {
                // Show loading state
                locationSpinner.classList.remove('hidden');
                allowLocationText.textContent = 'Getting location...';
                allowLocationBtn.disabled = true;

                // Check if we should skip geolocation entirely and use mock coordinates
                if (shouldUseMockCoordinates()) {
                    console.log('Using mock coordinates for development/testing - skipping geolocation');

                    // Set mock coordinates immediately
                    locationLatInput.value = 10.286;
                    locationLngInput.value = 11.167;
                    locationAcquired = true;

                    // Show success feedback
                    const modalContent = locationModal.querySelector('.bg-white');
                    const messageDiv = modalContent.querySelector('p');
                    messageDiv.textContent = 'Using default location coordinates for development/testing. Verification can proceed.';
                    messageDiv.className = 'text-green-600 text-sm leading-relaxed';

                    // Update button to proceed
                    locationSpinner.classList.add('hidden');
                    allowLocationText.textContent = 'Continue';
                    allowLocationBtn.disabled = false;

                    return;
                }

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;

                        // Set the location values
                        locationLatInput.value = latitude;
                        locationLngInput.value = longitude;
                        locationAcquired = true;

                        console.log('Location acquired:', latitude, longitude);

                        // Hide modal
                        locationModal.classList.add('hidden');

                        // Reset button state
                        locationSpinner.classList.add('hidden');
                        allowLocationText.textContent = 'Allow Location';
                        allowLocationBtn.disabled = false;

                    }, function(error) {
                        locationAcquired = false;
                        console.log('Geolocation error:', error);

                        // Check if we should fall back to mock coordinates instead of showing error
                        if (shouldUseMockCoordinates()) {
                            console.log('Geolocation failed, using mock coordinates for development/testing');

                            // Set mock coordinates
                            locationLatInput.value = 10.286;
                            locationLngInput.value = 11.167;
                            locationAcquired = true;

                            // Show success feedback
                            const modalContent = locationModal.querySelector('.bg-white');
                            const messageDiv = modalContent.querySelector('p');
                            messageDiv.textContent = 'Using default location coordinates for development/testing. Verification can proceed.';
                            messageDiv.className = 'text-green-600 text-sm leading-relaxed';

                            // Update button to proceed
                            locationSpinner.classList.add('hidden');
                            allowLocationText.textContent = 'Continue';
                            allowLocationBtn.disabled = false;

                            return;
                        }

                        let modalMessage = '';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                modalMessage = 'Please allow location access in your browser settings to verify your collection activity.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                modalMessage = 'Location information is unavailable. Please enable GPS or location services and try again.';
                                break;
                            case error.TIMEOUT:
                                modalMessage = 'Location request timed out. Please check your internet connection and try again.';
                                break;
                            default:
                                modalMessage = 'An unknown error occurred while retrieving location. Please try again or contact support.';
                                break;
                        }

                        // Update modal with error message and retry option
                        const modalContent = locationModal.querySelector('.bg-white');
                        const messageDiv = modalContent.querySelector('p');
                        messageDiv.textContent = modalMessage;
                        messageDiv.className = 'text-gray-600 text-sm leading-relaxed'; // Ensure normal styling

                        // Reset button state
                        locationSpinner.classList.add('hidden');
                        allowLocationText.textContent = 'Try Again';
                        allowLocationBtn.disabled = false;

                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 300000 // 5 minutes
                    });
                } else {
                    const modalContent = locationModal.querySelector('.bg-white');
                    const messageDiv = modalContent.querySelector('p');
                    messageDiv.textContent = 'Geolocation is not supported by this browser. Please use a modern browser with location support.';

                    // Reset button state
                    locationSpinner.classList.add('hidden');
                    allowLocationText.textContent = 'Close';
                    allowLocationBtn.disabled = false;

                    // Change button action to close modal
                    allowLocationBtn.onclick = function() {
                        locationModal.classList.add('hidden');
                    };
                }
            }

            // Show location modal when page loads
            locationModal.classList.remove('hidden');

            // Handle location button click
            allowLocationBtn.addEventListener('click', function() {
                // If button says "Continue", just close the modal (mock location already set)
                if (allowLocationText.textContent === 'Continue') {
                    locationModal.classList.add('hidden');
                    return;
                }

                if (navigator.geolocation) {
                    getUserLocation();
                } else {
                    locationModal.classList.add('hidden');
                }
            });

            // Initialize canvas
            resizeCanvas();

            // Resize canvas for crisp rendering
            function resizeCanvas() {
                const rect = canvas.getBoundingClientRect();
                const dpr = window.devicePixelRatio || 1;
                canvas.width = rect.width * dpr;
                canvas.height = rect.height * dpr;
                ctx.scale(dpr, dpr);
                canvas.style.width = rect.width + 'px';
                canvas.style.height = rect.height + 'px';
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.strokeStyle = '#000';
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
            }

            // Drawing events
            function startDrawing(e) {
                isDrawing = true;
                ctx.beginPath();
                const rect = canvas.getBoundingClientRect();
                const scaleX = canvas.width / rect.width;
                const scaleY = canvas.height / rect.height;
                const x = (e.clientX - rect.left) * scaleX;
                const y = (e.clientY - rect.top) * scaleY;
                ctx.moveTo(x, y);
            }

            function draw(e) {
                if (!isDrawing) return;
                e.preventDefault();
                const rect = canvas.getBoundingClientRect();
                const scaleX = canvas.width / rect.width;
                const scaleY = canvas.height / rect.height;
                const x = (e.clientX - rect.left) * scaleX;
                const y = (e.clientY - rect.top) * scaleY;
                ctx.lineTo(x, y);
                ctx.stroke();
            }

            function stopDrawing() {
                isDrawing = false;
            }

            // Touch events for mobile
            function handleTouchStart(e) {
                e.preventDefault();
                const touch = e.touches[0];
                const mouseEvent = new MouseEvent('mousedown', {
                    clientX: touch.clientX,
                    clientY: touch.clientY
                });
                canvas.dispatchEvent(mouseEvent);
            }

            function handleTouchMove(e) {
                e.preventDefault();
                const touch = e.touches[0];
                const mouseEvent = new MouseEvent('mousemove', {
                    clientX: touch.clientX,
                    clientY: touch.clientY
                });
                canvas.dispatchEvent(mouseEvent);
            }

            function handleTouchEnd(e) {
                e.preventDefault();
                const mouseEvent = new MouseEvent('mouseup');
                canvas.dispatchEvent(mouseEvent);
            }

            // Event listeners
            canvas.addEventListener('mousedown', startDrawing);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stopDrawing);
            canvas.addEventListener('mouseout', stopDrawing);
            canvas.addEventListener('touchstart', handleTouchStart, { passive: false });
            canvas.addEventListener('touchmove', handleTouchMove, { passive: false });
            canvas.addEventListener('touchend', handleTouchEnd);

            // Clear signature
            clearSignatureBtn.addEventListener('click', function() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                document.getElementById('signatureData').value = '';
                document.getElementById('signatureType').value = '';
            });

            // Photo preview
            collectionPhotoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        collectionPhotoPreview.src = e.target.result;
                        collectionPhotoPreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Signature image preview
            signatureImageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        signatureImagePreview.src = e.target.result;
                        signatureImagePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Camera capture functionality
            document.getElementById('capturePhoto').addEventListener('click', function() {
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                    .then(function(stream) {
                        const modal = document.createElement('div');
                        modal.className = 'fixed inset-0 z-50 bg-black bg-opacity-75 flex items-center justify-center';
                        modal.innerHTML = `
                            <div class="bg-white rounded-lg p-6 max-w-lg w-full mx-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-bold">Capture Photo</h3>
                                    <button id="closeModal" class="text-2xl">&times;</button>
                                </div>
                                <video id="cameraVideo" autoplay playsinline class="w-full h-80 bg-gray-200 rounded-lg"></video>
                                <canvas id="tempCanvas" class="hidden"></canvas>
                                <div class="flex justify-center mt-4">
                                    <button id="captureBtn" class="px-6 py-3 bg-blue-600 text-white rounded-lg">Capture</button>
                                </div>
                            </div>
                        `;
                        document.body.appendChild(modal);

                        const video = document.getElementById('cameraVideo');
                        const tempCanvas = document.getElementById('tempCanvas');
                        video.srcObject = stream;

                        document.getElementById('captureBtn').addEventListener('click', function() {
                            tempCanvas.width = video.videoWidth;
                            tempCanvas.height = video.videoHeight;
                            const tempCtx = tempCanvas.getContext('2d');
                            tempCtx.drawImage(video, 0, 0);

                            tempCanvas.toBlob(function(blob) {
                                const file = new File([blob], 'captured-photo.jpg', { type: 'image/jpeg' });
                                const dt = new DataTransfer();
                                dt.items.add(file);
                                collectionPhotoInput.files = dt.files;

                                const reader = new FileReader();
                                reader.onload = e => {
                                    collectionPhotoPreview.src = e.target.result;
                                    collectionPhotoPreview.classList.remove('hidden');
                                };
                                reader.readAsDataURL(file);

                                stream.getTracks().forEach(track => track.stop());
                                document.body.removeChild(modal);
                            });
                        });

                        document.getElementById('closeModal').addEventListener('click', function() {
                            stream.getTracks().forEach(track => track.stop());
                            document.body.removeChild(modal);
                        });
                    })
                    .catch(function(error) {
                        alert('Camera not supported');
                    });
                }
            });

            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Validate location
                if (!locationAcquired) {
                    // Show location modal again if location failed
                    locationModal.classList.remove('hidden');
                    const modalContent = locationModal.querySelector('.bg-white');
                    const messageDiv = modalContent.querySelector('p');
                    messageDiv.textContent = 'Location permission is required to complete the collection verification. Please allow location access.';
                    allowLocationText.textContent = 'Allow Location';
                    return;
                }

                // Validate signature
                const hasCanvasSignature = !ctx.getImageData(0, 0, canvas.width, canvas.height).data.every(item => item === 0 || item === 255);
                const hasImageSignature = signatureImageInput.files.length > 0;

                if (!hasCanvasSignature && !hasImageSignature) {
                    signatureValidationMessage.classList.remove('hidden');
                    return;
                }

                signatureValidationMessage.classList.add('hidden');

                // Prepare signature data
                if (hasCanvasSignature) {
                    const signatureData = canvas.toDataURL('image/png');
                    document.getElementById('signatureData').value = signatureData;
                    document.getElementById('signatureType').value = 'canvas';
                } else {
                    document.getElementById('signatureType').value = 'upload';
                }

                // Submit form
                submitButton.disabled = true;
                submitButton.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div> Processing...';

                const formData = new FormData(form);

                fetch('{{ route('agent.verify.collection.submit') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        window.location.href = '{{ route('agent.verify.collection') }}';
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error'));
                        submitButton.disabled = false;
                        submitButton.innerHTML = 'Submit Verification';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Network error occurred');
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Submit Verification';
                });
            });

            // Print functionality
            document.getElementById('printVerificationSlip').addEventListener('click', function() {
                window.print();
            });

            // Window resize
            window.addEventListener('resize', resizeCanvas);
        });
    </script>

    <style>
        @media print {
            .no-print { display: none; }
            body { font-size: 12px; }
            .print-break { page-break-before: always; }
        }
    </style>
@endsection

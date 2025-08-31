<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Seasonal Loan - AFNON</title>
    @include('application.includes.app')
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                fontFamily: {
                    'sans': ['Inter', 'system-ui', 'sans-serif'],
                },
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'scale-in': 'scaleIn 0.2s ease-out',
                        'progress-fill': 'progressFill 0.4s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0.95)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' }
                        },
                        progressFill: {
                            '0%': { width: '0%' },
                            '100%': { width: '100%' }
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Enhanced Stepper Styles */
        .stepper-container {
            position: relative;
            margin: 3rem 0;
            padding: 2rem 0;
        }

        .stepper-progress-bg {
            position: absolute;
            top: 50%;
            left: 10%;
            right: 10%;
            height: 4px;
            background: linear-gradient(90deg, #e5e7eb 0%, #d1d5db 100%);
            border-radius: 2px;
            transform: translateY(-50%);
            z-index: 1;
        }

        .stepper-progress {
            height: 100%;
            background: linear-gradient(90deg, #10b981 0%, #059669 50%, #0ea5e9 100%);
            border-radius: 2px;
            width: 0%;
            transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        /* Removed shimmer animation for better performance */

        .stepper-steps {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 2;
            padding: 0 10%;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            background: white;
            padding: 0 1.5rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .step-circle {
            width: 4rem;
            height: 4rem;
            border-radius: 50%;
            background: #e5e7eb;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            border: 3px solid transparent;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .step-circle::before {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 50%;
            padding: 3px;
            background: linear-gradient(135deg, #10b981, #059669, #0ea5e9);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: exclude;
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .step-item.active .step-circle {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            transform: scale(1.02);
        }

        .step-item.active .step-circle::before {
            opacity: 1;
        }

        .step-item.completed .step-circle {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            transform: scale(1);
        }

        .step-item.completed .step-circle::before {
            opacity: 1;
        }

        .step-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #6b7280;
            transition: all 0.3s ease;
            text-align: center;
            max-width: 120px;
        }

        .step-item.active .step-title {
            color: #10b981;
            transform: translateY(-2px);
        }

        .step-item.completed .step-title {
            color: #059669;
        }

        .step-description {
            font-size: 0.75rem;
            color: #9ca3af;
            text-align: center;
            margin-top: 0.25rem;
            max-width: 120px;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .step-item.active .step-description {
            opacity: 1;
            transform: translateY(0);
        }

        /* Form sections */
        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
            animation: fadeIn 0.8s ease-in-out;
        }

        /* Enhanced form styling */
        .form-section {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(16, 185, 129, 0.1);
            border-radius: 1.5rem;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .form-section:hover {
            box-shadow: 0 15px 40px rgba(16, 185, 129, 0.1);
            transform: translateY(-2px);
        }

        /* Input enhancements */
        .form-input {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .form-input:focus {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.15);
        }

        /* Floating background elements */
        .floating-element {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(14, 165, 233, 0.1));
            animation: float 8s ease-in-out infinite;
            pointer-events: none;
        }

        .floating-element:nth-child(1) { width: 100px; height: 100px; top: 10%; left: 5%; animation-delay: 0s; }
        .floating-element:nth-child(2) { width: 150px; height: 150px; top: 60%; right: 10%; animation-delay: 2s; }
        .floating-element:nth-child(3) { width: 80px; height: 80px; bottom: 20%; left: 15%; animation-delay: 4s; }

        /* Button enhancements */
        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #0ea5e9 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
        }

        /* Dark mode enhancements */
        .dark .form-section {
            background: rgba(31, 41, 55, 0.9);
            border-color: rgba(16, 185, 129, 0.2);
        }

        .dark .step-item {
            background: transparent; /* prevent white boxes behind icons in dark mode */
        }

        .dark .step-circle {
            background: #374151;
            color: #9ca3af;
        }

        .dark .stepper-progress-bg {
            background: linear-gradient(90deg, #374151 0%, #4b5563 100%);
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .stepper-steps {
                padding: 0 5%;
            }

            .step-circle {
                width: 3rem;
                height: 3rem;
                font-size: 1rem;
            }

            .step-title {
                font-size: 0.8rem;
                max-width: 80px;
            }

            .step-description {
                display: none;
            }
        }

        /* Seed selection enhancements */
        .seed-option {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .seed-option::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(16, 185, 129, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .seed-option:hover::before {
            left: 100%;
        }

        .seed-option:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.15);
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-teal-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 transition-all duration-300">
    <!-- Floating Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-float" style="animation-delay: 2s;"></div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg shadow-lg border-b border-emerald-100 dark:border-gray-700 fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <button onclick="history.back()" aria-label="Go back"
                        class="mr-4 p-2 rounded-lg text-gray-400 dark:text-gray-500 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-gray-700 transition-all duration-200">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </button>
                    <div class="h-10 w-10 bg-gradient-to-br from-emerald-600 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-seedling text-white text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">AFNON</h1>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">Agricultural Finance Network</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="hidden sm:flex items-center bg-emerald-100 dark:bg-emerald-900/30 px-3 py-1 rounded-full">
                        <i class="fas fa-calendar-alt text-emerald-600 dark:text-emerald-400 mr-2"></i>
                        <span class="text-sm font-medium text-emerald-800 dark:text-emerald-300">{{ $season->name ?? 'Season' }}</span>
                    </div>
                    <button id="darkModeToggle" aria-label="Toggle dark mode"
                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-emerald-100 dark:hover:bg-gray-600 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all duration-200">
                        <i id="sunIcon" class="fas fa-sun hidden dark:block"></i>
                        <i id="moonIcon" class="fas fa-moon block dark:hidden"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12 animate-fade-in">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg mb-6">
                    <i class="fas fa-file-contract text-white text-2xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Apply for <span class="text-emerald-600 dark:text-emerald-400">{{ $season->name ?? 'Season' }}</span> Loan
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Get access to quality agricultural inputs based on your farm size. Complete the application in simple steps.
                </p>
            </div>

            <!-- Enhanced Form Container -->
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg shadow-2xl rounded-3xl overflow-hidden border border-emerald-100 dark:border-gray-700 animate-scale-in">
                <form id="application-form" method="POST" action="{{ route('applications.store') }}" class="p-8">
                    @csrf
                    @if ($errors->any())
                        <div id="error-summary" class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/20 dark:text-red-300 dark:border-red-700">
                            <p class="text-sm font-semibold mb-2">Please review the following issues:</p>
                            <ul class="list-disc pl-5 text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Hidden season inputs -->
                    <input type="hidden" id="season-select" name="season" value="{{ $season->id ?? '' }}">
                    <input type="hidden" name="season_id" value="{{ $season->id ?? '' }}">
                    <!-- Enhanced Multi-Step Stepper -->
                    <div class="stepper-container">
                        <div class="stepper-progress-bg">
                            <div class="stepper-progress" id="stepper-progress"></div>
                        </div>
                        <div class="stepper-steps">
                            <div class="step-item active" data-step="1">
                                <div class="step-circle">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="step-title">Personal Info</div>
                                <div class="step-description">Your details</div>
                            </div>
                            <div class="step-item" data-step="2">
                                <div class="step-circle">
                                    <i class="fas fa-tractor"></i>
                                </div>
                                <div class="step-title">Farm Details</div>
                                <div class="step-description">Farm information</div>
                            </div>
                            <div class="step-item" data-step="3">
                                <div class="step-circle">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="step-title">Complete</div>
                                <div class="step-description">Review & submit</div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 1: Personal Information -->
                    <div class="form-step active" id="step-1">
                        <div class="form-section">
                            <div class="mb-8 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg mb-4">
                                    <i class="fas fa-user-circle text-white text-xl"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Personal Information</h2>
                                <p class="text-gray-600 dark:text-gray-300">Please provide your personal details for verification</p>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">Full Name *</label>
                                    <input type="text" name="full_name" required
                                        placeholder="Enter your full name"
                                        class="form-input w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">Phone Number *</label>
                                    <input type="tel" name="phone" required
                                        placeholder="+234 xxx xxx xxxx"
                                        class="form-input w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">NIN *</label>
                                    <input type="text" name="nin" maxlength="11" required
                                        pattern="[0-9]{11}" placeholder="Enter your NIN"
                                        class="form-input w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">BVN *</label>
                                    <input type="text" id="bvn-input" name="bvn" maxlength="11" required
                                        pattern="[0-9]{11}" placeholder="Enter your BVN"
                                        class="form-input w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                                    <div id="bvn-status" class="mt-2 text-sm hidden" role="status" aria-live="polite"></div>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">State *</label>
                                    <select name="state" id="state" required onchange="selectLGA(this)"
                                        class="form-input w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">LGA *</label>
                                    <select name="lga" id="lga" required
                                        class="form-input w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        <option value="">Select LGA</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">Address *</label>
                                    <textarea name="address" required rows="3"
                                        class="form-input w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                        placeholder="Enter your full address"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Step 1 Navigation -->
                        <div class="flex justify-end mt-8">
                            <button type="button" id="next-step-1" class="btn-primary px-8 py-3 text-white font-semibold rounded-lg transition-all duration-300">
                                Next: Farm Information
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Farm Information -->
                    <div class="form-step" id="step-2">
                        <div class="form-section">
                            <div class="mb-8 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg mb-4">
                                    <i class="fas fa-tractor text-white text-xl"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Farm Information</h2>
                                <p class="text-gray-600 dark:text-gray-300">Tell us about your farm details</p>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">Farm Location *</label>
                                    <input type="text" name="farm_location" required
                                        placeholder="Village/Town"
                                        class="form-input w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">Farm Size (Hectares) *</label>
                                    <input type="number" name="farm_size" id="farm-size" step="0.1" min="0.1" required
                                        placeholder="e.g. 2.5"
                                        class="form-input w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">Cluster Farm Location</label>
                                    <input type="text" name="cluster_location"
                                        placeholder="e.g., Igabi West"
                                        class="form-input w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                                </div>
                            </div>
                        </div>

                        <!-- Step 2 Navigation -->
                        <div class="flex justify-between mt-8">
                            <button type="button" id="prev-step-2" class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-300">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Previous
                            </button>
                            <button type="button" id="next-step-2" class="btn-primary px-8 py-3 text-white font-semibold rounded-lg transition-all duration-300">
                                Next: Complete Application
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Completion -->
                    <div class="form-step" id="step-3">
                        <div class="form-section">
                            <div class="mb-8 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg mb-4">
                                    <i class="fas fa-check-circle text-white text-xl"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Complete Your Application</h2>
                                <p class="text-gray-600 dark:text-gray-300">Select your seed and review your loan details</p>
                            </div>

                            <!-- Seed Selection -->
                            <div class="mb-8">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fas fa-seedling text-emerald-600 mr-3"></i>
                                    Choose Your Seed
                                </h3>
                                <div class="grid md:grid-cols-2 gap-6" id="seed-options">
                                    @forelse(($seeds ?? []) as $seed)
                                        <label class="seed-option block border-2 border-gray-200 dark:border-gray-700 rounded-xl p-6 cursor-pointer hover:border-emerald-500 transition-all duration-300"
                                               tabindex="0" role="radio" aria-label="Select {{ $seed->name }}">
                                            <input type="radio" name="selected_seed" value="{{ $seed->id }}" class="hidden"
                                                   data-qty-per-hectare="{{ $seed->quantity_per_hectare }}"
                                                   data-price-per-unit="{{ $seed->price_per_unit }}"
                                                   data-unit="{{ $seed->unit ?? 'unit' }}">
                                            <div class="flex justify-between items-center">
                                                <div class="flex-1">
                                                    <div class="flex items-center mb-2">
                                                        <i class="fas fa-seedling text-emerald-600 mr-2"></i>
                                                        <h4 class="font-bold text-gray-900 dark:text-white">{{ $seed->name }}</h4>
                                                    </div>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ $seed->quantity_per_hectare }} {{ $seed->unit ?? 'unit' }}/hectare</p>
                                                    <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">₦{{ number_format($seed->price_per_unit) }} per {{ $seed->unit ?? 'unit' }}</p>
                                                </div>
                                                <div class="w-6 h-6 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                                    <div class="w-3 h-3 bg-emerald-500 rounded-full hidden"></div>
                                                </div>
                                            </div>
                                        </label>
                                    @empty
                                        <p class="text-sm text-gray-600 dark:text-gray-400">No seed options available for this season.</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Loan Summary -->
                            <div class="mb-8" id="loan-summary">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fas fa-calculator text-emerald-600 mr-3"></i>
                                    Loan Summary
                                </h3>
                                <div class="grid md:grid-cols-3 gap-4">
                                    <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border-2 border-emerald-200 dark:border-emerald-700 rounded-xl">
                                        <div class="flex items-center mb-2">
                                            <i class="fas fa-money-bill-wave text-emerald-600 mr-2"></i>
                                            <span class="text-sm font-medium text-emerald-800 dark:text-emerald-200">Total Loan</span>
                                        </div>
                                        <p id="total-loan" class="text-2xl font-bold text-emerald-900 dark:text-emerald-100">₦0</p>
                                    </div>
                                    <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border-2 border-yellow-200 dark:border-yellow-700 rounded-xl">
                                        <div class="flex items-center mb-2">
                                            <i class="fas fa-piggy-bank text-yellow-600 mr-2"></i>
                                            <span class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Equity Held</span>
                                        </div>
                                        <p id="equity-held" class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">₦0</p>
                                    </div>
                                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-200 dark:border-blue-700 rounded-xl">
                                        <div class="flex items-center mb-2">
                                            <i class="fas fa-hand-holding-usd text-blue-600 mr-2"></i>
                                            <span class="text-sm font-medium text-blue-800 dark:text-blue-200">You Receive</span>
                                        </div>
                                        <p id="you-receive" class="text-2xl font-bold text-blue-900 dark:text-blue-100">₦0</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Important Note -->
                            <div class="mb-6 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border-l-4 border-yellow-400 rounded-lg">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-yellow-600 mr-3 mt-1"></i>
                                    <div>
                                        <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-1">Important Information</p>
                                        <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                            You will receive 50% of the total loan value. The remaining 50% is held as equity by AFNON to ensure program sustainability.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Agreement Checkboxes -->
                            <div class="space-y-4 mb-8">
                                <div class="flex items-start gap-3">
                                    <input type="checkbox" required id="terms-agreement"
                                        class="mt-1 h-5 w-5 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded transition-all duration-200">
                                    <label for="terms-agreement" class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                        I agree to the <a href="#" class="text-emerald-600 hover:text-emerald-800 underline font-medium">Terms and Conditions</a>
                                        and confirm that all provided information is accurate and complete.
                                    </label>
                                </div>
                                <div class="flex items-start gap-3">
                                    <input type="checkbox" required id="equity-agreement"
                                        class="mt-1 h-5 w-5 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded transition-all duration-200">
                                    <label for="equity-agreement" class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                        I understand and accept that 50% of the loan value will be held as equity by AFNON as part of the loan structure.
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3 Navigation -->
                        <div class="flex justify-between items-center mt-8">
                            <button type="button" id="prev-step-3" class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all duration-300">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Previous
                            </button>
                            <button type="submit" id="submit-btn" aria-label="Submit application"
                                class="btn-primary px-8 py-4 text-white font-bold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                <span id="submit-btn-content">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Submit Application
                                </span>
                                <span id="submit-btn-loading" class="hidden">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                    Submitting...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 max-w-md mx-4 animate-scale-in">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Application Submitted!</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-6">Your loan application has been successfully submitted for review.</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Reference: <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">AF-2024-001</span></p>
                <button onclick="closeModal()" class="btn-primary px-6 py-2 text-white rounded-lg">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        // Enhanced JavaScript functionality
        let currentStep = 1;
        const totalSteps = 3;

        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') html.classList.add('dark');

        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', () => {
                html.classList.toggle('dark');
                localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
            });
        }

        // State/LGA Dynamic Fetch
        // Fetch all States on load
        function fetchStates() {
            const stateSelect = document.getElementById('state');
            if (!stateSelect) return;
            fetch('https://nga-states-lga.onrender.com/fetch')
                .then((res) => res.json())
                .then((data) => {
                    // Clear existing except placeholder
                    const length = stateSelect.options.length;
                    for (let i = length - 1; i >= 1; i--) {
                        stateSelect.options[i] = null;
                    }
                    for (let index = 0; index < Object.keys(data).length; index++) {
                        const option = document.createElement('option');
                        option.text = data[index];
                        option.value = data[index];
                        stateSelect.add(option);
                    }
                })
                .catch(() => {
                    // Non-blocking UI notice
                    showNotification('Failed to load states, please check connection.', 'error');
                });
        }

        // Fetch Local Governments based on selected state
        function selectLGA(target) {
            const state = target.value;
            const lgaSelect = document.getElementById('lga');
            if (!lgaSelect || !state) return;
            fetch('https://nga-states-lga.onrender.com/?state=' + encodeURIComponent(state))
                .then((res) => res.json())
                .then((data) => {
                    // Clear existing options
                    const length = lgaSelect.options.length;
                    for (let i = length - 1; i >= 0; i--) {
                        lgaSelect.options[i] = null;
                    }
                    // Add placeholder
                    const ph = document.createElement('option');
                    ph.text = 'Select LGA';
                    ph.value = '';
                    lgaSelect.add(ph);
                    for (let index = 0; index < Object.keys(data).length; index++) {
                        const option = document.createElement('option');
                        option.text = data[index];
                        option.value = data[index];
                        lgaSelect.add(option);
                    }
                })
                .catch(() => {
                    showNotification('Failed to load LGAs for ' + state + '.', 'error');
                });
        }

        // Loan Summary Calculation
        const otherCommodities = @json(collect($others ?? [])->map(function($c){
            return [
                'quantity_per_hectare' => $c->quantity_per_hectare,
                'price_per_unit' => $c->price_per_unit,
            ];
        })->values());
        const insuranceRate = {{ $season->insurance_rate ?? 0 }};

        function parseNumber(n) {
            const x = parseFloat(n);
            return isNaN(x) ? 0 : x;
        }

        function formatNaira(n) {
            try {
                return new Intl.NumberFormat('en-NG', { style: 'currency', currency: 'NGN', maximumFractionDigits: 0 }).format(n);
            } catch (_) {
                return '₦' + Math.round(n).toLocaleString();
            }
        }

        function calculateLoanSummary() {
            const totalLoanEl = document.getElementById('total-loan');
            const equityEl = document.getElementById('equity-held');
            const receiveEl = document.getElementById('you-receive');
            const farmSize = parseNumber(document.getElementById('farm-size')?.value);
            const selectedSeed = document.querySelector('input[name="selected_seed"]:checked');

            // Keep card visible, but show placeholders until ready
            if (!selectedSeed || farmSize <= 0) {
                if (totalLoanEl) totalLoanEl.textContent = '—';
                if (equityEl) equityEl.textContent = '—';
                if (receiveEl) receiveEl.textContent = '—';
                return;
            }

            const qtyPerHectare = parseNumber(selectedSeed.dataset.qtyPerHectare);
            const pricePerUnit = parseNumber(selectedSeed.dataset.pricePerUnit);
            const seedQty = qtyPerHectare * farmSize;
            const seedVal = seedQty * pricePerUnit;

            let othersTotal = 0;
            if (Array.isArray(otherCommodities)) {
                for (const item of otherCommodities) {
                    const qty = parseNumber(item.quantity_per_hectare) * farmSize;
                    const val = qty * parseNumber(item.price_per_unit);
                    othersTotal += val;
                }
            }

            const baseTotal = seedVal + othersTotal;
            const insuranceAmount = baseTotal * (parseNumber(insuranceRate) / 100);
            const finalTotal = baseTotal + insuranceAmount;
            const equity = finalTotal / 2;
            const youReceive = finalTotal - equity;

            if (totalLoanEl) totalLoanEl.textContent = formatNaira(finalTotal);
            if (equityEl) equityEl.textContent = formatNaira(equity);
            if (receiveEl) receiveEl.textContent = formatNaira(youReceive);
        }

        // Enhanced Stepper Functions
        function updateStepperProgress() {
            const progressBar = document.getElementById('stepper-progress');
            const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
            progressBar.style.width = progressPercentage + '%';
        }

        function showStep(stepNumber) {
            // Hide all steps
            document.querySelectorAll('.form-step').forEach(step => {
                step.classList.remove('active');
            });

            // Show current step
            document.getElementById(`step-${stepNumber}`).classList.add('active');

            // Update stepper visual state
            document.querySelectorAll('.step-item').forEach((step, index) => {
                const stepIndex = index + 1;
                step.classList.remove('active', 'completed');

                if (stepIndex < stepNumber) {
                    step.classList.add('completed');
                    // Add checkmark for completed steps
                    const circle = step.querySelector('.step-circle');
                    circle.innerHTML = '<i class="fas fa-check"></i>';
                } else if (stepIndex === stepNumber) {
                    step.classList.add('active');
                    // Restore original icon for active step
                    const circle = step.querySelector('.step-circle');
                    if (stepNumber === 1) circle.innerHTML = '<i class="fas fa-user-circle"></i>';
                    else if (stepNumber === 2) circle.innerHTML = '<i class="fas fa-tractor"></i>';
                    else if (stepNumber === 3) circle.innerHTML = '<i class="fas fa-check-circle"></i>';
                } else {
                    // Restore original icon for future steps
                    const circle = step.querySelector('.step-circle');
                    if (stepIndex === 1) circle.innerHTML = '<i class="fas fa-user-circle"></i>';
                    else if (stepIndex === 2) circle.innerHTML = '<i class="fas fa-tractor"></i>';
                    else if (stepIndex === 3) circle.innerHTML = '<i class="fas fa-check-circle"></i>';
                }
            });

            currentStep = stepNumber;
            updateStepperProgress();
        }

        function validateStep(stepNumber) {
            const step = document.getElementById(`step-${stepNumber}`);
            const requiredFields = step.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500', 'bg-red-50');
                    isValid = false;

                    // Add shake animation
                    field.style.animation = 'shake 0.5s ease-in-out';
                    setTimeout(() => {
                        field.style.animation = '';
                    }, 500);
                } else {
                    field.classList.remove('border-red-500', 'bg-red-50');
                }
            });

            return isValid;
        }

        // BVN Verification
        const bvnInput = document.getElementById('bvn-input');
        const bvnStatus = document.getElementById('bvn-status');

        if (bvnInput) {
            bvnInput.addEventListener('input', function() {
                const bvn = this.value;
                if (bvn.length === 11) {
                    bvnStatus.classList.remove('hidden');
                    bvnStatus.innerHTML = '<span class="text-yellow-600"><i class="fas fa-spinner fa-spin mr-2"></i>Verifying BVN...</span>';

                    // Simulate BVN verification
                    setTimeout(() => {
                        const isValid = Math.random() > 0.3; // 70% success rate for demo
                        if (isValid) {
                            bvnStatus.innerHTML = '<span class="text-green-600"><i class="fas fa-check-circle mr-2"></i>BVN verified successfully</span>';
                        } else {
                            bvnStatus.innerHTML = '<span class="text-red-600"><i class="fas fa-exclamation-circle mr-2"></i>Invalid BVN. Please check and try again.</span>';
                        }
                    }, 2000);
                } else {
                    bvnStatus.classList.add('hidden');
                }
            });
        }

        // Seed Selection Enhancement
        function setupSeedSelection() {
            document.querySelectorAll('.seed-option').forEach(option => {
                option.addEventListener('click', function() {
                    // Clear all selections
                    document.querySelectorAll('.seed-option').forEach(opt => {
                        opt.classList.remove('border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/20');
                        opt.querySelector('.w-3').classList.add('hidden');
                        opt.querySelector('.w-6').classList.remove('border-emerald-500');
                    });

                    // Select current option
                    this.classList.add('border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/20');
                    this.querySelector('.w-3').classList.remove('hidden');
                    this.querySelector('.w-6').classList.add('border-emerald-500');
                    this.querySelector('input[type="radio"]').checked = true;
                    // Recalculate loan summary when seed changes
                    calculateLoanSummary();

                    // Add selection animation
                    this.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 200);
                });

                // Keyboard support
                option.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        option.click();
                    }
                });
            });
        }

        // Step Navigation
        document.getElementById('next-step-1')?.addEventListener('click', () => {
            if (validateStep(1)) {
                showStep(2);
            } else {
                showNotification('Please fill in all required fields before proceeding.', 'error');
            }
        });

        document.getElementById('prev-step-2')?.addEventListener('click', () => {
            showStep(1);
        });

        document.getElementById('next-step-2')?.addEventListener('click', () => {
            if (validateStep(2)) {
                showStep(3);
            } else {
                showNotification('Please fill in all required fields before proceeding.', 'error');
            }
        });

        document.getElementById('prev-step-3')?.addEventListener('click', () => {
            showStep(2);
        });

        // Form Submission - allow real submission, block only if invalid
        (function() {
            const form = document.getElementById('application-form');
            const submitBtn = document.getElementById('submit-btn');
            const submitBtnContent = document.getElementById('submit-btn-content');
            const submitBtnLoading = document.getElementById('submit-btn-loading');

            if (!form) return;

            form.addEventListener('submit', (e) => {
                if (!validateStep(3)) {
                    e.preventDefault();
                    showNotification('Please complete all required fields and accept the terms.', 'error');
                    return;
                }

                // Show loading state
                if (submitBtn && submitBtnContent && submitBtnLoading) {
                    submitBtn.disabled = true;
                    submitBtnContent.classList.add('hidden');
                    submitBtnLoading.classList.remove('hidden');

                    // Prevent double submission
                    submitBtn.style.pointerEvents = 'none';
                }
            });
        })();

        // Utility Functions
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-24 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
                type === 'error' ? 'bg-red-500 text-white' :
                type === 'success' ? 'bg-green-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle'} mr-2"></i>
                    ${message}
                </div>
            `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateX(-100%)';
            }, 100);

            // Remove after 5 seconds
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 5000);
        }

        function closeModal() {
            document.getElementById('success-modal').classList.add('hidden');
        }

        // Add shake animation keyframes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
        `;
        document.head.appendChild(style);

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            setupSeedSelection();
            updateStepperProgress();
            fetchStates();
            // Live update on farm size change
            const fs = document.getElementById('farm-size');
            if (fs) {
                fs.addEventListener('input', calculateLoanSummary);
            }
            // Initialize loan summary placeholders
            calculateLoanSummary();
        });
    </script>
</body>
</html>

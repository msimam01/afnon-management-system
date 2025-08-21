<!DOCTYPE html>
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Apply for {{ $season->name ?? 'Seasonal' }} Loan - AFNON</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {!! ToastMagic::styles() !!}
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                fontFamily: {
                    'sans': ['Inter', 'system-ui', 'sans-serif'],
                },
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'scale-in': 'scaleIn 0.3s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                        'bounce-slow': 'bounce 2s infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0.95)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .step-indicator { transition: all 0.3s ease; }
        .step-indicator.active { background: linear-gradient(135deg, #10b981 0%, #059669 100%); transform: scale(1.1); }
        .step-indicator.completed { background: linear-gradient(135deg, #059669 0%, #047857 100%); }
        .form-section { display: none; }
        .form-section.active { display: block; animation: fadeIn 0.5s ease-in-out; }
        .calculation-card { background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border: 2px solid #10b981; box-shadow: 0 10px 25px rgba(16, 185, 129, 0.1); }
        .verification-success { background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border: 2px solid #10b981; }
        .verification-error { background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border: 2px solid #ef4444; }
        .floating-label { transition: all 0.2s ease; }
        .floating-label.active { transform: translateY(-1.5rem) scale(0.875); color: #10b981; }

        /* Enhanced focus styles for inputs/selects/textareas */
        #application-form input[type="text"],
        #application-form input[type="tel"],
        #application-form input[type="number"],
        #application-form textarea,
        #application-form select {
            transition: box-shadow .2s ease, transform .05s ease;
        }
        #application-form input:focus-visible,
        #application-form textarea:focus-visible,
        #application-form select:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(16,185,129,0.35);
        }

        /* Seed option card interactions (no JS changes) */
        #seed-options label {
            transition: transform .15s ease, box-shadow .2s ease, border-color .2s ease, background-color .2s ease;
            border-color: rgba(16,185,129,0.25);
        }
        #seed-options label:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(16,185,129,0.15);
        }
        /* Highlight selected card using :has() support in modern browsers */
        #seed-options label:has(input:checked) {
            border-color: #10b981;
            background: linear-gradient(180deg, rgba(240,253,244,0.7), rgba(236,253,245,0.7));
        }

        /* Commodities table row hover */
        #other-commodities-section tbody tr {
            transition: background-color .15s ease;
        }
        #other-commodities-section tbody tr:hover {
            background-color: rgba(16,185,129,0.06);
        }

        /* Subtle error hint styles for x-input-error blocks */
        .input-error-hint {
            background: linear-gradient(180deg, rgba(254,242,242,0.6), rgba(254,226,226,0.4));
            border-left: 3px solid #ef4444;
            padding: 6px 10px;
            border-radius: 8px;
        }
        .input-error-hint li::before {
            content: "⚠ ";
            margin-right: 4px;
        }

        /* Seed card visuals (align to reference) */
        #seed-options label { position: relative; }
        /* remove any previous corner badge */
        #seed-options label::after { content: none !important; }

        /* Error summary styling */
        #error-summary { border-left: 4px solid #ef4444; }
        #error-summary a { text-decoration: underline; }

        /* Stat cards */
        .stat-card {
            background: linear-gradient(180deg, rgba(240,253,244,0.55), rgba(236,253,245,0.55));
            border: 1px solid rgba(16,185,129,0.4);
            border-radius: 14px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .stat-card .stat-icon {
            width: 36px; height: 36px;
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 9999px;
            background: linear-gradient(135deg, #10b981, #0ea5e9);
            color: white;
            box-shadow: 0 8px 20px rgba(16,185,129,0.25);
        }

        /* Multi-step form stepper (professional look) */
        .stepper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .step {
            flex: 1 1 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            text-align: center;
        }
        /* connector to the next step */
        .step::after {
            content: '';
            position: absolute;
            top: 24px; /* vertically centers with 48px circle */
            left: calc(50% + 28px);
            right: -50%;
            height: 2px;
            background: #e5e7eb; /* neutral */
        }
        .step:last-child::after { display: none; }
        .step.completed::after { background: #10b981; }

        .step-circle {
            width: 48px;
            height: 48px;
            border-radius: 9999px;
            background: #ffffff;
            border: 2px solid #e5e7eb;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-bottom: 0.5rem;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .step.active .step-circle {
            border-color: #10b981;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            box-shadow: 0 6px 16px rgba(16,185,129,0.25);
            transform: translateY(-1px);
        }
        .step.completed .step-circle {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            border-color: transparent;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(5,150,105,0.25);
        }
        .step-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #6b7280;
        }
        .step.active .step-title { color: #059669; }
        .step.completed .step-title { color: #047857; }
        
        /* Form sections */
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
            animation: fadeIn 0.5s ease-in-out;
        }

        /* Seed option radio indicator */
        #seed-options .radio-indicator { width: 20px; height: 20px; border: 2px solid #d1d5db; border-radius: 9999px; display: inline-flex; align-items: center; justify-content: center; }
        #seed-options .radio-dot { width: 12px; height: 12px; border-radius: 9999px; background: #10b981; display: none; }
        #seed-options label:has(input:checked) .radio-indicator { border-color: #10b981; }
        #seed-options label:has(input:checked) .radio-dot { display: block; }
        #seed-options label:has(input:checked) .select-text { display: none; }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-teal-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 transition-all duration-300">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-300 rounded-full mix-blend-multiply filter blur-xl opacity-20"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-20" style="animation-delay: 2s;"></div>
        <div class="absolute top-40 left-1/2 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-20" style="animation-delay: 4s;"></div>
    </div>
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
                        <span class="text-sm font-medium text-emerald-800 dark:text-emerald-300">{{ $season->name }}</span>
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

    <div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12 animate-fade-in">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg mb-6">
                    <i class="fas fa-file-contract text-white text-2xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Apply for <span class="text-emerald-600 dark:text-emerald-400">{{ $season->name }}</span> Loan
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Get access to quality agricultural inputs based on your farm size. Complete the application in simple steps.
                </p>
            </div>

        <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg shadow-2xl rounded-3xl overflow-hidden border border-emerald-100 dark:border-gray-700 animate-scale-in">
            <form id="application-form" method="POST" action="{{ route('applications.store') }}" class="p-8 space-y-10">
                @csrf
                @if ($errors->any())
                    <div id="error-summary" class="input-error-hint mb-4">
                        <p class="text-sm font-semibold text-red-700 dark:text-red-300 mb-1">Please review the following issues:</p>
                        <ul class="list-disc pl-5 text-sm text-red-700 dark:text-red-300 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Multi-Step Progress Stepper -->
                <div class="stepper">
                    <div class="step active" data-step="1">
                        <div class="step-circle">1</div>
                        <div class="step-title">Personal Information</div>
                    </div>
                    <div class="step" data-step="2">
                        <div class="step-circle">2</div>
                        <div class="step-title">Farm Information</div>
                    </div>
                    <div class="step" data-step="3">
                        <div class="step-circle">3</div>
                        <div class="step-title">Completion</div>
                    </div>
                </div>
                <!-- Hidden season inputs -->
                <input type="hidden" id="season-select" name="season" value="{{ $season->id }}">
                <input type="hidden" name="season_id" value="{{ $season->id }}">

                <!-- Step 1: Personal Information -->
                <div class="form-step active" id="step-1">
                    <div class="mb-8 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg mb-4">
                            <i class="fas fa-user-circle text-white text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Personal Information</h2>
                        <p class="text-gray-600 dark:text-gray-300">Please provide your personal details for verification</p>
                    </div>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Full Name *</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" required
                                placeholder="Enter full name"
                                class="w-full px-4 py-3 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all duration-200" />
                            <x-input-error :messages="$errors->get('full_name')" class="mt-1 input-error-hint" />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number *</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required
                                placeholder="+234 xxx xxx xxxx"
                                class="w-full px-4 py-3 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all duration-200" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-1 input-error-hint" />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">NIN *</label>
                            <input type="text" name="nin" maxlength="11" value="{{ old('nin') }}" required
                                pattern="[0-9]{11}"
                                class="w-full px-4 py-3 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all duration-200" />
                            <x-input-error :messages="$errors->get('nin')" class="mt-1 input-error-hint" />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">BVN *</label>
                            <input type="text" id="bvn-input" name="bvn" maxlength="11" required
                                pattern="[0-9]{11}" value="{{ old('bvn') }}"
                                class="w-full px-4 py-3 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all duration-200" />

                            <x-input-error :messages="$errors->get('bvn')" class="mt-1 input-error-hint" />
                            <div id="bvn-status" class="mt-1 text-sm hidden" role="status" aria-live="polite"></div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">State *</label>
                            <select name="state" id="state" onchange="selectLGA(this)" required
                                class="w-full px-4 py-3 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all duration-200">
                                <option value="{{ old('state') }}">Select State</option>
                            </select>
                            <x-input-error :messages="$errors->get('state')" class="mt-1 input-error-hint" />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">LGA *</label>
                            <select name="lga" id="lga" required
                                class="w-full px-4 py-3 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all duration-200">
                                <option value="{{ old('lga') }}">Select LGA</option>
                            </select>
                            <x-input-error :messages="$errors->get('lga')" class="mt-1 input-error-hint" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Address *</label>
                            <textarea name="address" value="{{ old('address') }}" required rows="3"
                                class="w-full px-4 py-3 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all duration-200"
                                placeholder="Enter your address"></textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-1 input-error-hint" />
                        </div>
                    </div>
                    
                    <!-- Step 1 Navigation -->
                    <div class="flex justify-end mt-8">
                        <button type="button" id="next-step-1" class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-300">
                            Next: Farm Information
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Farm Information -->
                <div class="form-step" id="step-2">
                    <div class="mb-8 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg mb-4">
                            <i class="fas fa-tractor text-white text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Farm Information</h2>
                        <p class="text-gray-600 dark:text-gray-300">Tell us about your farm details</p>
                    </div>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Farm Location *</label>
                            <input type="text" name="farm_location" value="{{ old('farm_location') }}" required
                                class="w-full px-4 py-3 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all duration-200"
                                placeholder="Village/Town" />
                            <x-input-error :messages="$errors->get('farm_location')" class="mt-1 input-error-hint" />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Farm Size (Hectares)
                                *</label>
                            <input type="number" name="farm_size" id="farm-size" step="0.1" min="0.1"
                                required value="{{ old('farm_size') }}"
                                class="w-full px-4 py-3 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all duration-200"
                                placeholder="e.g. 2.5" />
                            <x-input-error :messages="$errors->get('farm_size')" class="mt-1 input-error-hint" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Cluster Farm
                                Location</label>
                            <input type="text" name="cluster_location" value="{{ old('cluster_location') }}"
                                class="w-full px-4 py-3 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all duration-200"
                                placeholder="e.g., Igabi West" />
                            <x-input-error :messages="$errors->get('cluster_location')" class="mt-1 input-error-hint" />
                        </div>
                    </div>
                    
                    <!-- Step 2 Navigation -->
                    <div class="flex justify-between mt-8">
                        <button type="button" id="prev-step-2" class="px-6 py-3 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition-all duration-300">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Previous
                        </button>
                        <button type="button" id="next-step-2" class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-300">
                            Next: Completion
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Completion -->
                <div class="form-step" id="step-3">
                    <div class="mb-8 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg mb-4">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Complete Your Application</h2>
                        <p class="text-gray-600 dark:text-gray-300">Select your seed and review your loan details</p>
                    </div>

                    <!-- Seed Selection -->
                    <section id="seed-selection">
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 flex items-center">
                                <i class="fas fa-seedling text-emerald-600 mr-3"></i>
                                Choose a Seed
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Select your preferred seed commodity</p>
                        </div>
                        <div id="seed-options" class="grid md:grid-cols-2 gap-6 mb-8"></div>
                        <x-input-error :messages="$errors->get('seed_selected')" class="mt-1 input-error-hint" />
                    </section>

                    <!-- Commodities Breakdown -->
                    <section id="other-commodities-section" class="hidden mb-8">
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 flex items-center">
                                <i class="fas fa-list-alt text-emerald-600 mr-3"></i>
                                Commodities Breakdown
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Your commodity allocation based on farm size</p>
                        </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border rounded-2xl overflow-hidden dark:border-gray-700">
                            <thead class="bg-emerald-50 dark:bg-emerald-900/20">
                                <tr>
                                    <th class="px-4 py-3 text-left text-emerald-800 dark:text-emerald-200 font-semibold">Commodity</th>
                                    <th class="px-4 py-3 text-left text-emerald-800 dark:text-emerald-200 font-semibold">Quantity</th>
                                    <th class="px-4 py-3 text-left text-emerald-800 dark:text-emerald-200 font-semibold">Unit Price</th>
                                    <th class="px-4 py-3 text-left text-emerald-800 dark:text-emerald-200 font-semibold">Total</th>
                                </tr>
                            </thead>
                            <tbody id="other-commodities-list"
                                class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Loan Summary -->
                <section id="loan-summary" class="hidden mb-8">
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="p-4 bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-600 rounded-lg">
                            <p id="total-loan" class="font-semibold text-gray-900 dark:text-white"></p>
                        </div>
                        <div class="p-4 bg-yellow-50 dark:bg-yellow-900/10 border border-yellow-600 rounded-lg">
                            <p id="equity-held" class="font-semibold text-gray-900 dark:text-white"></p>
                        </div>
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/10 border border-blue-600 rounded-lg">
                            <p id="disbursed-amount" class="font-semibold text-gray-900 dark:text-white"></p>
                        </div>
                    </section>

                    <!-- Note -->
                    <div id="equity-note" class="hidden mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-yellow-600 mr-2 mt-1"></i>
                            <div>
                                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Important Note</p>
                                <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                                    You will receive 50% of the total loan value. The remaining 50% is held as equity by the organization.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Agreement -->
                    <div class="space-y-4 mb-8">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" required id="terms-agreement"
                                class="mt-1 h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                            <label for="terms-agreement" class="text-sm text-gray-700 dark:text-gray-300">
                                I agree to the <a href="#" class="text-emerald-600 hover:underline">Terms and Conditions</a>
                                and confirm that all provided information is accurate.
                            </label>
                        </div>
                        <div class="flex items-start gap-3">
                            <input type="checkbox" required id="equity-agreement"
                                class="mt-1 h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                            <label for="equity-agreement" class="text-sm text-gray-700 dark:text-gray-300">
                                I understand that 50% of the loan value will be held as equity by the organization.
                            </label>
                        </div>
                    </div>

                    <!-- Step 3 Navigation -->
                    <div class="flex justify-between items-center">
                        <button type="button" id="prev-step-3" class="px-6 py-3 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition-all duration-300">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Previous
                        </button>
                        <button type="submit" id="submit-btn" aria-label="Submit application"
                            class="px-8 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Submit Application
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('layouts.footer')

    <script>
        // Dark Mode
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
        const bvnInput = document.getElementById('bvn-input');
        const bvnStatus = document.getElementById('bvn-status');
        //Fetch all States
        fetch('https://nga-states-lga.onrender.com/fetch')
            .then((res) => res.json())
            .then((data) => {
                var x = document.getElementById("state");
                for (let index = 0; index < Object.keys(data).length; index++) {
                    var option = document.createElement("option");
                    option.text = data[index];
                    option.value = data[index];
                    x.add(option);
                }
            });

        //Fetch Local Goverments based on selected state
        function selectLGA(target) {
            var state = target.value;
            fetch('https://nga-states-lga.onrender.com/?state=' + state)
                .then((res) => res.json())
                .then((data) => {
                    var x = document.getElementById("lga");

                    var select = document.getElementById("lga");
                    var length = select.options.length;
                    for (i = length - 1; i >= 0; i--) {
                        select.options[i] = null;
                    }
                    for (let index = 0; index < Object.keys(data).length; index++) {
                        var option = document.createElement("option");
                        option.text = data[index];
                        option.value = data[index];
                        x.add(option);
                    }
                });
        }

        bvnInput.addEventListener('input', function() {
            let bvn = this.value;
            if (bvn.length === 11) {
                bvnStatus.classList.remove('hidden');
                bvnStatus.innerHTML = `<span class="text-yellow-600">⏳ Verifying BVN...</span>`;

                fetch("{{ route('bvn.verify') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            bvn: bvn
                        })
                    })
                    .then(async res => {
                        let data;
                        try {
                            data = await res.json();
                        } catch (e) {
                            throw new Error("Invalid JSON response");
                        }

                        if (!res.ok) {
                            throw new Error(data.message || "Verification request failed");
                        }

                        return data;
                    })
                    .then(data => {
                        if (data.status) {
                            bvnStatus.innerHTML = `<span class="text-green-600">${data.message}</span>`;
                        } else {
                            bvnStatus.innerHTML = `<span class="text-red-600">❌ ${data.message}</span>`;
                        }
                    })
                    .catch(err => {
                        bvnStatus.innerHTML =
                            `<span class="text-red-600">❌ ${err.message || 'Could not verify BVN. Please try again.'}</span>`;
                    });

            } else {
                bvnStatus.classList.add('hidden');
            }
        });

        const commodityData = {
            "{{ $season->id }}": {
                seeds: @json($seeds),
                others: @json($others)
            }
        };
        const seedCommodities = @json($seeds);
        const otherCommodities = @json($others);
        const insuranceRate = {{ $season->insurance_rate ?? 1 }};

        function handleFarmSizeChange() {
            const farmSize = parseFloat(document.getElementById('farm-size').value || 0);
            const seedSection = document.getElementById('seed-selection');
            const seedOptions = document.getElementById('seed-options');
            const breakdownSection = document.getElementById('other-commodities-section');

            if (farmSize > 0) {
                renderCommoditiesForSeason(); // re-render
            } else {
                seedOptions.innerHTML = ''; // clear seeds
                seedSection.classList.add('hidden');
                breakdownSection.classList.add('hidden');
                document.getElementById('loan-summary').classList.add('hidden');
                document.getElementById('equity-note').classList.add('hidden');
            }
        }

        document.getElementById('farm-size').addEventListener('input', handleFarmSizeChange);

        // Multi-step form navigation
        let currentStep = 1;
        const totalSteps = 3;

        function showStep(stepNumber) {
            // Hide all steps
            document.querySelectorAll('.form-step').forEach(step => {
                step.classList.remove('active');
            });
            
            // Show current step
            document.getElementById(`step-${stepNumber}`).classList.add('active');
            
            // Update stepper
            document.querySelectorAll('.step').forEach((step, index) => {
                step.classList.remove('active', 'completed');
                if (index + 1 < stepNumber) {
                    step.classList.add('completed');
                } else if (index + 1 === stepNumber) {
                    step.classList.add('active');
                }
            });
            
            currentStep = stepNumber;
        }

        function validateStep(stepNumber) {
            const step = document.getElementById(`step-${stepNumber}`);
            const requiredFields = step.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            return isValid;
        }

        // Step navigation event listeners
        document.getElementById('next-step-1').addEventListener('click', () => {
            if (validateStep(1)) {
                showStep(2);
            } else {
                alert('Please fill in all required fields before proceeding.');
            }
        });

        document.getElementById('prev-step-2').addEventListener('click', () => {
            showStep(1);
        });

        document.getElementById('next-step-2').addEventListener('click', () => {
            if (validateStep(2)) {
                const farmSize = parseFloat(document.getElementById('farm-size').value || 0);
                if (farmSize > 0) {
                    renderCommoditiesForSeason();
                }
                showStep(3);
            } else {
                alert('Please fill in all required fields before proceeding.');
            }
        });

        document.getElementById('prev-step-3').addEventListener('click', () => {
            showStep(2);
        });

        function renderCommoditiesForSeason() {
            const seasonId = document.getElementById('season-select').value;
            const farmSize = parseFloat(document.getElementById('farm-size').value || 0);
            const data = commodityData[seasonId];

            if (!data || farmSize <= 0) { return; }

            // Show skeletons while (re)rendering
            showSeedSkeleton();

            // Render seed options
            const seedHTML = data.seeds.map(seed => `
            <label class="seed-option block border-2 border-gray-200 dark:border-gray-700 rounded-lg p-4 cursor-pointer hover:border-emerald-500 transition-all duration-200"
                   tabindex="0" role="radio" aria-label="Select ${seed.name}">
                <input type="radio" name="selected_seed" value="${seed.id}" data-price="${seed.price_per_unit}" data-qty="${seed.quantity_per_hectare}" data-name="${seed.name}" data-unit="${seed.unit}" class="hidden">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">${seed.name}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">${seed.quantity_per_hectare} ${seed.unit}/hectare</p>
                        <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400">₦${parseFloat(seed.price_per_unit).toLocaleString()} per ${seed.unit}</p>
                    </div>
                    <div class="w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center radio-indicator">
                        <div class="w-3 h-3 bg-emerald-500 rounded-full radio-dot hidden"></div>
                    </div>
                </div>
            </label>
        `).join('');

            document.getElementById('seed-options').innerHTML = seedHTML;
            document.getElementById('seed-selection').classList.remove('hidden');

            // Add click handlers for seed selection
            document.querySelectorAll('.seed-option').forEach(option => {
                option.addEventListener('click', function() {
                    const radio = this.querySelector('input[type="radio"]');
                    
                    // Clear all selections
                    document.querySelectorAll('.seed-option').forEach(opt => {
                        opt.classList.remove('border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/20');
                        opt.querySelector('.radio-dot').classList.add('hidden');
                    });
                    
                    // Select current option
                    this.classList.add('border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/20');
                    this.querySelector('.radio-dot').classList.remove('hidden');
                    radio.checked = true;
                    
                    // Trigger breakdown
                    renderCommodityBreakdown(data, farmSize, radio);
                    updateSeedAria();
                });
            });

            // Keyboard support: Enter/Space selects a focused seed card
            const seedContainer = document.getElementById('seed-options');
            seedContainer.setAttribute('role', 'radiogroup');
            seedContainer.addEventListener('keydown', (e) => {
                const label = e.target.closest('label');
                if (!label) return;
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    label.click(); // Use the click handler
                }
            });

            updateSeedAria();

        }

        function renderCommodityBreakdown(data, farmSize, selectedInput) {
            let total = 0;

            // Show table skeleton while building rows
            showBreakdownSkeleton();

            const seedQty = parseFloat(selectedInput.dataset.qty) * farmSize;
            const seedPrice = parseFloat(selectedInput.dataset.price);
            const seedUnit = selectedInput.dataset.unit;
            const seedName = selectedInput.dataset.name;
            const seedTotal = seedQty * seedPrice;
            total += seedTotal;

            let rows = `
            <tr>
                <td class="px-4 py-2 dark:text-white">${seedName}</td>
                <td class="px-4 py-2 dark:text-white">${seedQty.toFixed(1)} ${seedUnit}</td>
                <td class="px-4 py-2 dark:text-white">₦${seedPrice.toLocaleString()}</td>
                <td class="px-4 py-2 font-semibold dark:text-white">₦${seedTotal.toLocaleString()}</td>
            </tr>
        `;

            data.others.forEach(item => {
                const q = item.quantity_per_hectare * farmSize;
                const val = q * item.price_per_unit;
                total += val;
                rows += `
                <tr>
                    <td class="px-4 py-2 dark:text-white">${item.name}</td>
                    <td class="px-4 py-2 dark:text-white">${q.toFixed(1)} ${item.unit}</td>
                    <td class="px-4 py-2 dark:text-white">₦${item.price_per_unit.toLocaleString()}</td>
                    <td class="px-4 py-2 font-semibold dark:text-white">₦${val.toLocaleString()}</td>
                </tr>`;
            });

            const insurance = total * (insuranceRate / 100);
            const finalLoan = total + insurance;
            const equity = finalLoan / 2;

            // Add insurance row
            rows += `
            <tr class="bg-gray-50 dark:bg-gray-700">
                <td class="px-4 py-2 font-semibold text-gray-800 dark:text-white">Insurance (${insuranceRate}%)</td>
                <td class="px-4 py-2 dark:text-white">—</td>
                <td class="px-4 py-2 dark:text-white">—</td>
                <td class="px-4 py-2 font-semibold dark:text-white">₦${insurance.toLocaleString()}</td>
            </tr>
        `;

            // Update table and summary
            document.getElementById('other-commodities-list').innerHTML = rows;
            document.getElementById('total-loan').innerHTML =
                `Total Loan Value: <strong>₦${finalLoan.toLocaleString()}</strong>`;
            document.getElementById('equity-held').innerHTML = `Equity Held: <strong>₦${equity.toLocaleString()}</strong>`;
            document.getElementById('disbursed-amount').innerHTML =
                `Disbursed Amount: <strong>₦${equity.toLocaleString()}</strong>`;

            // Show sections
            document.getElementById('other-commodities-section').classList.remove('hidden');
            document.getElementById('loan-summary').classList.remove('hidden');
            document.getElementById('equity-note').classList.remove('hidden');
        }

        // Accessibility helpers
        function updateSeedAria() {
            const labels = document.querySelectorAll('#seed-options label');
            labels.forEach(l => {
                const r = l.querySelector('input[type="radio"]');
                if (r) l.setAttribute('aria-checked', r.checked ? 'true' : 'false');
            });
        }


        // Skeletons
        function showSeedSkeleton(count = 2) {
            const container = document.getElementById('seed-options');
            const items = Array.from({ length: count }).map(() => `
                <div class="border rounded-lg p-4 bg-white dark:bg-gray-800">
                    <div class="skeleton h-4 w-1/3 mb-3"></div>
                    <div class="skeleton h-3 w-2/3"></div>
                </div>
            `).join('');
            container.innerHTML = items;
        }

        function showBreakdownSkeleton(rows = 3) {
            const tbody = document.getElementById('other-commodities-list');
            let rowsHtml = '';
            for (let i = 0; i < rows; i++) {
                rowsHtml += `
                    <tr>
                        <td class="px-4 py-2"><div class="skeleton h-4 w-32"></div></td>
                        <td class="px-4 py-2"><div class="skeleton h-4 w-24"></div></td>
                        <td class="px-4 py-2"><div class="skeleton h-4 w-20"></div></td>
                        <td class="px-4 py-2"><div class="skeleton h-4 w-24"></div></td>
                    </tr>`;
            }
            tbody.innerHTML = rowsHtml;
            document.getElementById('other-commodities-section').classList.remove('hidden');
        }


        // Trigger calculation when farm size changes
        document.getElementById('farm-size').addEventListener('input', () => {
            const selected = document.querySelector('input[name="selected_seed"]:checked');
            if (selected) {
                const farmSize = parseFloat(document.getElementById('farm-size').value || 0);
                const seasonId = document.getElementById('season-select').value;
                renderCommodityBreakdown(commodityData[seasonId], farmSize, selected);
            }
        });

        // Attach to the form that wraps your application fields
        document.querySelector('form').addEventListener('submit', function(e) {
            const farmSize = parseFloat(document.getElementById('farm-size').value || 0);
            const selectedSeed = document.querySelector('input[name="selected_seed"]:checked');

            // If farm size is > 0 but no seed selected, block submission
            if (farmSize > 0 && !selectedSeed) {
                e.preventDefault();
                showSeedError("Please select a seed before submitting your application.");
                document.getElementById('seed-selection').scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });

        function showSeedError(message) {
            let errorDiv = document.getElementById('seed-error');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.id = 'seed-error';
                errorDiv.className = 'text-red-600 text-sm mt-2';
                document.getElementById('seed-selection').appendChild(errorDiv);
            }
            errorDiv.textContent = message;
        }

        function hideSeedError() {
            const errorDiv = document.getElementById('seed-error');
            if (errorDiv) errorDiv.remove();
        }

        // When a seed is selected, remove the error message
        document.addEventListener('change', function(e) {
            if (e.target && e.target.name === 'selected_seed') {
                hideSeedError();
            }
        });

        function downloadAcknowledgment() {
            alert("🔧 Acknowledgment slip generation coming soon...");
        }

        function closeSuccessModal() {
            document.getElementById('success-modal').classList.add('hidden');
        }
        // const form = document.getElementById('application-form');
        // form.addEventListener('submit', async function(e) {
        //     e.preventDefault();

        //     const formData = new FormData(form);

        //     try {
        //         const res = await fetch('/applications', {
        //             method: 'POST',
        //             headers: {
        //                 'Accept': 'application/json' // 🔥 Tells Laravel to return JSON even on error
        //             },
        //             body: formData
        //         });

        //         const contentType = res.headers.get('content-type');
        //         const isJSON = contentType && contentType.includes('application/json');

        //         if (!res.ok) {
        //             const errorResponse = isJSON ? await res.json() : await res.text();
        //             console.error('Validation Error:', errorResponse);

        //             if (isJSON && errorResponse.errors) {
        //                 alert(Object.values(errorResponse.errors).flat().join('\n'));
        //             } else {
        //                 alert('Submission failed. Please try again.');
        //             }
        //             return;
        //         }

        //         const data = await res.json();
        //         console.log('Success:', data);

        //         // ✅ Show success modal with application data
        //         document.getElementById('ref-number').textContent = data.reference;
        //         document.getElementById('success-modal').classList.remove('hidden');

        //     } catch (error) {
        //         console.error("Network or unexpected error:", error);
        //         alert("An unexpected error occurred. Please check your connection or try again.");
        //     }
        // });

        document.getElementById('season-select').addEventListener('change', () => { renderCommoditiesForSeason(); updateStepper(); });
        document.getElementById('farm-size').addEventListener('input', () => { renderCommoditiesForSeason(); updateStepper(); });
        // Initial render on page load
        document.addEventListener('DOMContentLoaded', () => {
            renderCommoditiesForSeason();
            updateStepper();
        });
    </script>
    {!! ToastMagic::scripts() !!}
</body>

</html>

@extends('layouts.layout')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-emerald-50/30 to-green-50/20 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Enhanced Header Section -->
        <div class="relative mb-8">
            <div class="absolute inset-0 rounded-2xl blur-xl bg-gradient-to-r from-emerald-500/20 to-green-600/20"></div>
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl border border-white/20 dark:border-gray-700/50 p-8 shadow-xl">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 via-emerald-900 to-emerald-900 bg-clip-text text-transparent dark:from-white dark:via-emerald-100 dark:to-emerald-100">
                            Create New Tenant
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 font-medium">Set up a new tenant organization with dedicated resources</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Form Section -->
        <div class="relative">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-500/10 via-gray-500/10 to-zinc-500/10 rounded-2xl blur-xl"></div>
            <div class="relative bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl rounded-2xl border border-white/20 dark:border-gray-700/50 shadow-xl overflow-hidden">

                <!-- Form Header -->
                <div class="px-8 py-6 border-b border-gray-200/50 dark:border-gray-700/50 bg-gradient-to-r from-white/50 to-gray-50/50 dark:from-gray-800/50 dark:to-gray-700/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Tenant Configuration</h3>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="p-8">
                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/20 dark:text-red-300 dark:border-red-700">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-semibold">Please fix the following errors:</span>
                            </div>
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="tenantForm" method="POST" action="{{ route('superadmin.tenants.store') }}" class="space-y-8">
                        @csrf

                        <!-- Basic Information Section -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h4>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            Tenant Name *
                                        </span>
                                    </label>
                                    <input type="text" name="name" id="name" required
                                        class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-300/50 dark:border-gray-600/50 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 text-gray-900 dark:text-white placeholder-gray-500 transition-all duration-300 hover:shadow-md"
                                        placeholder="e.g., Kano State Agricultural Development Program"
                                        value="{{ old('name') }}">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">The display name for this tenant organization</p>
                                </div>

                                <div class="space-y-2">
                                    <label for="id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                            </svg>
                                            Tenant ID (Slug) *
                                        </span>
                                    </label>
                                    <input type="text" name="id" id="id" required
                                        class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-300/50 dark:border-gray-600/50 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 text-gray-900 dark:text-white placeholder-gray-500 transition-all duration-300 hover:shadow-md font-mono"
                                        placeholder="e.g., kano"
                                        value="{{ old('id') }}"
                                        pattern="[a-z0-9-]+"
                                        title="Only lowercase letters, numbers, and hyphens allowed">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Unique identifier (lowercase, no spaces)</p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="domain" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                                        </svg>
                                        Tenant Domain *
                                    </span>
                                </label>
                                <div class="relative">
                                    <input type="url" name="domain" id="domain" required
                                        class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-300/50 dark:border-gray-600/50 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 text-gray-900 dark:text-white placeholder-gray-500 transition-all duration-300 hover:shadow-md"
                                        placeholder="https://kano.afnon.com"
                                        value="{{ old('domain') }}">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Full URL where this tenant will be accessible</p>
                            </div>

                            <div class="space-y-2">
                                <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                        </svg>
                                        Description (Optional)
                                    </span>
                                </label>
                                <textarea name="description" id="description" rows="3"
                                    class="w-full px-4 py-3 bg-white/80 dark:bg-gray-700/80 backdrop-blur-sm border border-gray-300/50 dark:border-gray-600/50 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 text-gray-900 dark:text-white placeholder-gray-500 transition-all duration-300 hover:shadow-md resize-none"
                                    placeholder="Brief description of this tenant organization and its purpose...">{{ old('description') }}</textarea>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Optional description for administrative purposes</p>
                            </div>
                        </div>

                        <!-- Information Panel -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-l-4 border-blue-400 rounded-lg p-6">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-blue-600 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-2">What happens when you create a tenant?</h4>
                                    <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                                        <li>• A dedicated database will be created for this tenant</li>
                                        <li>• Default admin and agent users will be set up automatically</li>
                                        <li>• All necessary tables and permissions will be initialized</li>
                                        <li>• The tenant will be ready for immediate use</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200/50 dark:border-gray-700/50">
                            <button type="button" onclick="window.history.back()"
                                class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-xl hover:from-gray-700 hover:to-gray-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Cancel
                            </button>
                            <button type="submit" id="submitBtn"
                                class="flex-1 inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-emerald-600 to-green-600 text-white rounded-xl hover:from-emerald-700 hover:to-green-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                <span id="submitBtnContent">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    Create Tenant
                                </span>
                                <span id="submitBtnLoading" class="hidden">
                                    <svg class="animate-spin w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Creating Tenant...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('tenantForm');
        const submitBtn = document.getElementById('submitBtn');
        const submitBtnContent = document.getElementById('submitBtnContent');
        const submitBtnLoading = document.getElementById('submitBtnLoading');
        const nameInput = document.getElementById('name');
        const idInput = document.getElementById('id');

        // Auto-generate slug from name
        nameInput.addEventListener('input', function() {
            const name = this.value;
            const slug = name.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                .replace(/\s+/g, '-') // Replace spaces with hyphens
                .replace(/-+/g, '-') // Replace multiple hyphens with single
                .replace(/^-|-$/g, ''); // Remove leading/trailing hyphens

            if (!idInput.dataset.userModified) {
                idInput.value = slug;
            }
        });

        // Track if user manually modified the ID field
        idInput.addEventListener('input', function() {
            this.dataset.userModified = 'true';
        });

        // Form submission with loading state
        form.addEventListener('submit', function(e) {
            // Show loading state
            submitBtn.disabled = true;
            submitBtnContent.classList.add('hidden');
            submitBtnLoading.classList.remove('hidden');

            // Prevent double submission
            submitBtn.style.pointerEvents = 'none';
        });

        // Input validation and styling
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });

            // Real-time validation feedback
            input.addEventListener('input', function() {
                if (this.checkValidity()) {
                    this.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                    this.classList.add('border-green-500', 'focus:ring-green-500', 'focus:border-green-500');
                } else {
                    this.classList.remove('border-green-500', 'focus:ring-green-500', 'focus:border-green-500');
                    this.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                }
            });
        });
    });
</script>

<style>
    .focused {
        transform: scale(1.01);
    }
</style>
@endsection

@extends('layouts.layout')
@section('content')
<div id="settings-section" class="w-full min-h-screen px-4 py-8 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">System Settings</h2>
            </div>
            <p class="text-gray-600 dark:text-gray-400">Manage your organization information and system configuration</p>
        </div>

        <!-- Main Settings Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-white">Organization Information</h3>
                        <p class="text-emerald-100 text-sm mt-1">Configure your organization's basic details and branding</p>
                    </div>
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8">
                <form action="{{ route('superadmin.settings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <!-- Basic Information Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Organization Name -->
                        <div class="space-y-2">
                            <label class="flex items-center space-x-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span>Organization Name</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="org_name" 
                                value="{{ $setting->org_name ?? '' }}" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200"
                                placeholder="Enter organization name"
                                required
                            >
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label class="flex items-center space-x-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span>Email Address</span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                name="email" 
                                value="{{ $setting->email ?? '' }}" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200"
                                placeholder="Enter email address"
                                required
                            >
                        </div>

                        <!-- Phone -->
                        <div class="space-y-2">
                            <label class="flex items-center space-x-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span>Phone Number</span>
                            </label>
                            <input 
                                type="text" 
                                name="phone" 
                                value="{{ $setting->phone ?? '' }}" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200"
                                placeholder="Enter phone number"
                            >
                        </div>

                        <!-- Address -->
                        <div class="space-y-2">
                            <label class="flex items-center space-x-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>Address</span>
                            </label>
                            <input 
                                type="text" 
                                name="address" 
                                value="{{ $setting->address ?? '' }}" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200"
                                placeholder="Enter organization address"
                            >
                        </div>
                    </div>

                    <!-- Logo Upload Section -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                        <div class="space-y-4">
                            <label class="flex items-center space-x-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Organization Logo</span>
                            </label>
                            
                            <div class="flex items-start space-x-6">
                                <!-- Current Logo Display -->
                                @if(!empty($setting->logo))
                                <div class="flex-shrink-0">
                                    <div class="w-24 h-24 bg-gray-100 dark:bg-gray-600 rounded-lg overflow-hidden border-2 border-gray-200 dark:border-gray-500">
                                        <img src="{{ asset('storage/' . $setting->logo) }}" alt="Current Logo" class="w-full h-full object-cover">
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">Current Logo</p>
                                </div>
                                @endif
                                
                                <!-- File Upload Area -->
                                <div class="flex-1">
                                    <div class="relative">
                                        <input 
                                            type="file" 
                                            name="logo" 
                                            id="logo-upload"
                                            accept="image/*"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                        >
                                        <div id="upload-area" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-emerald-400 dark:hover:border-emerald-400 transition-colors duration-200">
                                            <svg id="upload-icon" class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            <div id="upload-text">
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                                    <span class="font-medium text-emerald-600 dark:text-emerald-400">Click to upload</span> or drag and drop
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                            </div>
                                            <!-- Preview will be inserted here -->
                                            <div id="image-preview" class="hidden">
                                                <img id="preview-img" class="w-24 h-24 object-cover rounded-lg mx-auto mb-2" alt="Preview">
                                                <p class="text-xs text-gray-600 dark:text-gray-400">New logo preview</p>
                                                <button type="button" id="remove-preview" class="text-xs text-red-500 hover:text-red-700 mt-1">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            All changes are saved automatically
                        </div>
                        
                        <div class="flex space-x-3">
                            <button 
                                type="button" 
                                class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 font-medium"
                            >
                                Reset Form
                            </button>
                            <button 
                                type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-lg hover:from-emerald-700 hover:to-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-200 font-medium shadow-lg hover:shadow-xl"
                            >
                                <span class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Save Settings</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Additional Info Card -->
        <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-1">Important Notes</h4>
                    <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                        <li>• Logo should be in PNG or JPG format with transparent background for best results</li>
                        <li>• Recommended logo dimensions: 200x200px or higher for crisp display</li>
                        <li>• Changes will be reflected across all system interfaces immediately</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Logo upload preview functionality
document.getElementById('logo-upload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const uploadIcon = document.getElementById('upload-icon');
    const uploadText = document.getElementById('upload-text');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    if (file) {
        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('Please select a valid image file (JPG, PNG, or GIF)');
            this.value = '';
            return;
        }
        
        // Validate file size (10MB = 10 * 1024 * 1024 bytes)
        const maxSize = 10 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('File size must be less than 10MB');
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            // Hide upload UI and show preview
            uploadIcon.classList.add('hidden');
            uploadText.classList.add('hidden');
            imagePreview.classList.remove('hidden');
            
            // Set preview image
            previewImg.src = e.target.result;
            
            console.log('File selected:', file.name, 'Size:', (file.size / 1024).toFixed(2) + 'KB');
        };
        reader.readAsDataURL(file);
    }
});

// Remove preview functionality
document.getElementById('remove-preview').addEventListener('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const uploadIcon = document.getElementById('upload-icon');
    const uploadText = document.getElementById('upload-text');
    const imagePreview = document.getElementById('image-preview');
    const fileInput = document.getElementById('logo-upload');
    
    // Reset file input
    fileInput.value = '';
    
    // Show upload UI and hide preview
    uploadIcon.classList.remove('hidden');
    uploadText.classList.remove('hidden');
    imagePreview.classList.add('hidden');
});

// Drag and drop functionality
const uploadArea = document.getElementById('upload-area');
const fileInput = document.getElementById('logo-upload');

// Prevent default drag behaviors
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, preventDefaults, false);
    document.body.addEventListener(eventName, preventDefaults, false);
});

// Highlight drop area when item is dragged over it
['dragenter', 'dragover'].forEach(eventName => {
    uploadArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, unhighlight, false);
});

// Handle dropped files
uploadArea.addEventListener('drop', handleDrop, false);

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

function highlight(e) {
    uploadArea.classList.add('border-emerald-400', 'bg-emerald-50', 'dark:bg-emerald-900/20');
}

function unhighlight(e) {
    uploadArea.classList.remove('border-emerald-400', 'bg-emerald-50', 'dark:bg-emerald-900/20');
}

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length > 0) {
        fileInput.files = files;
        // Trigger the change event
        const event = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(event);
    }
}

// Reset form functionality
document.querySelector('button[type="button"]').addEventListener('click', function() {
    if (confirm('Are you sure you want to reset all form fields?')) {
        const form = document.querySelector('form');
        const uploadIcon = document.getElementById('upload-icon');
        const uploadText = document.getElementById('upload-text');
        const imagePreview = document.getElementById('image-preview');
        
        // Reset form
        form.reset();
        
        // Reset upload area
        uploadIcon.classList.remove('hidden');
        uploadText.classList.remove('hidden');
        imagePreview.classList.add('hidden');
    }
});
</script>
@endsection
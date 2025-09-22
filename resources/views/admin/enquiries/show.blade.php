@extends('layouts.layout')

@section('title', 'View Enquiry')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Enquiry Details</h3>
            <div class="flex items-center space-x-2">
                @if($enquiry->is_spam)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                        Spam
                    </span>
                @elseif($enquiry->read_at)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Read
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                        Unread
                    </span>
                @endif
            </div>
        </div>

        <div class="px-6 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <!-- Enquiry Details -->
                    <div class="mb-6">
                        <h5 class="text-lg font-semibold text-blue-600 dark:text-blue-400 mb-4">Contact Information</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <strong class="text-gray-700 dark:text-gray-300">Name:</strong><br>
                                <span class="text-gray-900 dark:text-white">{{ $enquiry->name }}</span>
                            </div>
                            <div>
                                <strong class="text-gray-700 dark:text-gray-300">Email:</strong><br>
                                <a href="mailto:{{ $enquiry->email }}"
                                   class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                    {{ $enquiry->email }}
                                </a>
                            </div>
                        </div>
                        @if($enquiry->phone)
                            <div class="mt-4">
                                <strong class="text-gray-700 dark:text-gray-300">Phone:</strong><br>
                                <a href="tel:{{ $enquiry->phone }}"
                                   class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                    {{ $enquiry->phone }}
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="mb-6">
                        <h5 class="text-lg font-semibold text-blue-600 dark:text-blue-400 mb-4">Enquiry Details</h5>
                        <div class="mb-4">
                            <strong class="text-gray-700 dark:text-gray-300">Subject:</strong><br>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 mt-1">
                                {{ $enquiry->subject }}
                            </span>
                        </div>
                        <div>
                            <strong class="text-gray-700 dark:text-gray-300">Message:</strong><br>
                            <div class="mt-2 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $enquiry->message }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h5 class="text-lg font-semibold text-blue-600 dark:text-blue-400 mb-4">Technical Information</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <strong class="text-gray-700 dark:text-gray-300">IP Address:</strong><br>
                                <code class="text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $enquiry->ip_address }}</code>
                            </div>
                            <div>
                                <strong class="text-gray-700 dark:text-gray-300">Submitted:</strong><br>
                                <span class="text-gray-900 dark:text-white">{{ $enquiry->formatted_created_at }}</span>
                            </div>
                        </div>
                        @if($enquiry->user_agent)
                            <div class="mt-4">
                                <strong class="text-gray-700 dark:text-gray-300">User Agent:</strong><br>
                                <small class="text-gray-500 dark:text-gray-400 break-all">{{ $enquiry->user_agent }}</small>
                            </div>
                        @endif
                        @if($enquiry->read_at)
                            <div class="mt-4">
                                <strong class="text-gray-700 dark:text-gray-300">Read At:</strong><br>
                                <span class="text-gray-900 dark:text-white">{{ $enquiry->read_at->format('M d, Y \a\t g:i A') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <!-- Actions -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Actions</h5>
                        </div>
                        <div class="p-4 space-y-3">
                            <a href="mailto:{{ $enquiry->email }}?subject=Re: {{ $enquiry->subject }}"
                               class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-reply mr-2"></i> Reply via Email
                            </a>

                            @if($enquiry->is_spam)
                                <form method="POST" action="{{ route('admin.enquiries.mark-not-spam', $enquiry) }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                                        <i class="fas fa-check mr-2"></i> Mark as Not Spam
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.enquiries.mark-spam', $enquiry) }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center justify-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-colors"
                                            onclick="return confirm('Mark this enquiry as spam?')">
                                        <i class="fas fa-ban mr-2"></i> Mark as Spam
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
                                        onclick="return confirm('Are you sure you want to delete this enquiry?')">
                                    <i class="fas fa-trash mr-2"></i> Delete Enquiry
                                </button>
                            </form>

                            <a href="{{ route('admin.enquiries.index') }}"
                               class="w-full flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i> Back to List
                            </a>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 mt-4">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Stats</h5>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-2 gap-4 text-center">
                                <div class="border-r border-gray-200 dark:border-gray-700">
                                    <h4 class="text-2xl font-bold text-blue-600 dark:text-blue-400 mb-1">{{ \App\Models\Enquiry::count() }}</h4>
                                    <small class="text-gray-500 dark:text-gray-400">Total</small>
                                </div>
                                <div>
                                    <h4 class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mb-1">{{ \App\Models\Enquiry::unread()->count() }}</h4>
                                    <small class="text-gray-500 dark:text-gray-400">Unread</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


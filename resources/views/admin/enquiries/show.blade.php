@extends('layouts.app')

@section('title', 'View Enquiry')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Enquiry Details</h3>
                    <div class="card-tools">
                        @if($enquiry->is_spam)
                            <span class="badge badge-danger">Spam</span>
                        @elseif($enquiry->read_at)
                            <span class="badge badge-success">Read</span>
                        @else
                            <span class="badge badge-warning">Unread</span>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Enquiry Details -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">Contact Information</h5>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <strong>Name:</strong><br>
                                        {{ $enquiry->name }}
                                    </div>
                                    <div class="col-sm-6">
                                        <strong>Email:</strong><br>
                                        <a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a>
                                    </div>
                                </div>
                                @if($enquiry->phone)
                                    <div class="row mt-2">
                                        <div class="col-sm-6">
                                            <strong>Phone:</strong><br>
                                            <a href="tel:{{ $enquiry->phone }}">{{ $enquiry->phone }}</a>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-4">
                                <h5 class="text-primary mb-3">Enquiry Details</h5>
                                <div class="row">
                                    <div class="col-12">
                                        <strong>Subject:</strong><br>
                                        <span class="badge badge-info">{{ $enquiry->subject }}</span>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <strong>Message:</strong><br>
                                        <div class="border p-3 mt-2 bg-light rounded">
                                            {{ $enquiry->message }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5 class="text-primary mb-3">Technical Information</h5>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <strong>IP Address:</strong><br>
                                        <code>{{ $enquiry->ip_address }}</code>
                                    </div>
                                    <div class="col-sm-6">
                                        <strong>Submitted:</strong><br>
                                        {{ $enquiry->formatted_created_at }}
                                    </div>
                                </div>
                                @if($enquiry->user_agent)
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <strong>User Agent:</strong><br>
                                            <small class="text-muted">{{ $enquiry->user_agent }}</small>
                                        </div>
                                    </div>
                                @endif
                                @if($enquiry->read_at)
                                    <div class="row mt-2">
                                        <div class="col-sm-6">
                                            <strong>Read At:</strong><br>
                                            {{ $enquiry->read_at->format('M d, Y \a\t g:i A') }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Actions -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="mailto:{{ $enquiry->email }}?subject=Re: {{ $enquiry->subject }}"
                                           class="btn btn-primary">
                                            <i class="fas fa-reply"></i> Reply via Email
                                        </a>

                                        @if($enquiry->is_spam)
                                            <form method="POST" action="{{ route('admin.enquiries.mark-not-spam', $enquiry) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-success w-100">
                                                    <i class="fas fa-check"></i> Mark as Not Spam
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.enquiries.mark-spam', $enquiry) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-warning w-100"
                                                        onclick="return confirm('Mark this enquiry as spam?')">
                                                    <i class="fas fa-ban"></i> Mark as Spam
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-100"
                                                    onclick="return confirm('Are you sure you want to delete this enquiry?')">
                                                <i class="fas fa-trash"></i> Delete Enquiry
                                            </button>
                                        </form>

                                        <a href="{{ route('admin.enquiries.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Back to List
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Stats -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Quick Stats</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="border-end">
                                                <h4 class="text-primary mb-0">{{ \App\Models\Enquiry::count() }}</h4>
                                                <small class="text-muted">Total</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-warning mb-0">{{ \App\Models\Enquiry::unread()->count() }}</h4>
                                            <small class="text-muted">Unread</small>
                                        </div>
                                    </div>
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


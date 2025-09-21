@extends('layouts.app')

@section('title', 'Enquiries Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Enquiries Management</h3>
                    <div class="card-tools">
                        <span class="badge badge-info">{{ $enquiries->total() }} Total</span>
                        <span class="badge badge-warning">{{ $enquiries->where('read_at', null)->count() }} Unread</span>
                        <span class="badge badge-danger">{{ $enquiries->where('is_spam', true)->count() }} Spam</span>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('admin.enquiries.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Search by name, email, or subject"
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                                <option value="spam" {{ request('status') == 'spam' ? 'selected' : '' }}>Spam</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_from" class="form-control"
                                   value="{{ request('date_from') }}" placeholder="From Date">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_to" class="form-control"
                                   value="{{ request('date_to') }}" placeholder="To Date">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('admin.enquiries.index') }}" class="btn btn-secondary">Clear</a>
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    @if($enquiries->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enquiries as $enquiry)
                                        <tr class="{{ $enquiry->read_at ? '' : 'table-warning' }}">
                                            <td>
                                                <strong>{{ $enquiry->name }}</strong>
                                                @if($enquiry->phone)
                                                    <br><small class="text-muted">{{ $enquiry->phone }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a>
                                            </td>
                                            <td>
                                                <span class="text-truncate d-inline-block" style="max-width: 200px;"
                                                      title="{{ $enquiry->subject }}">
                                                    {{ $enquiry->subject }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($enquiry->is_spam)
                                                    <span class="badge badge-danger">Spam</span>
                                                @elseif($enquiry->read_at)
                                                    <span class="badge badge-success">Read</span>
                                                @else
                                                    <span class="badge badge-warning">Unread</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $enquiry->formatted_created_at }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.enquiries.show', $enquiry) }}"
                                                       class="btn btn-sm btn-primary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    @if($enquiry->is_spam)
                                                        <form method="POST" action="{{ route('admin.enquiries.mark-not-spam', $enquiry) }}"
                                                              class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success"
                                                                    title="Mark as Not Spam"
                                                                    onclick="return confirm('Mark this enquiry as not spam?')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form method="POST" action="{{ route('admin.enquiries.mark-spam', $enquiry) }}"
                                                              class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-warning"
                                                                    title="Mark as Spam"
                                                                    onclick="return confirm('Mark this enquiry as spam?')">
                                                                <i class="fas fa-ban"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}"
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                                title="Delete"
                                                                onclick="return confirm('Are you sure you want to delete this enquiry?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $enquiries->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No enquiries found</h5>
                            <p class="text-muted">There are no enquiries matching your criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-refresh every 30 seconds to show new enquiries
    setInterval(function() {
        if (document.visibilityState === 'visible') {
            location.reload();
        }
    }, 30000);
</script>
@endpush


@extends('admin.layouts.superadmin')

@section('content')
<div class="container-fluid px-4 py-4">
<!-- Header -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h2 fw-bold mb-1">Pending Users</h1>
        <p class="text-muted mb-0 small">
            Review user information and documents before approval.
        </p>
    </div>

    <div class="d-flex align-items-center gap-3">

        <span class="badge bg-warning bg-opacity-15 text-dark fs-6 px-3 py-2 rounded-pill">
            <i class="fas fa-clock me-1"></i> {{ $users->total() }} Pending
        </span>

        <a href="{{ route('superadmin.users.index') }}"
           class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>

    </div>
</div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle fs-5"></i>
            <span class="flex-grow-1">{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-circle fs-5"></i>
            <span class="flex-grow-1">{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search & Filter Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-light p-4">
            <form method="GET" class="row g-3">
                <div class="col-md-5">
                    <label for="search" class="form-label fw-medium text-secondary">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text"
                               class="form-control border-start-0 ps-0"
                               id="search"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Name, email, company, phone...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="sort" class="form-label fw-medium text-secondary">Sort by</label>
                    <select name="sort" id="sort" class="form-select">
                        <option value="" {{ request('sort')=='' ? 'selected' : '' }}>Newest first</option>
                        <option value="oldest" {{ request('sort')=='oldest' ? 'selected' : '' }}>Oldest first</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                    <a href="{{ route('superadmin.users.pending') }}" class="btn btn-outline-secondary flex-fill">
                        <i class="fas fa-undo-alt me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">User</th>
                        <th class="py-3">Company</th>
                        <th class="py-3">Registered</th>
                        <th class="py-3">Status</th>
                        <th class="text-end pe-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        @php
                            $photo = $user->profile_photo
                                ? asset('storage/' . $user->profile_photo)
                                : asset('assets/img/default-profile.png');
                            $docs = is_array($user->company_documents) ? $user->company_documents : [];
                        @endphp

                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $photo }}"
                                         alt="{{ $user->name }}'s profile"
                                         class="rounded-circle border object-fit-cover"
                                         width="48" height="48">
                                    <div>
                                        <div class="fw-semibold">{{ $user->name }} {{ $user->last_name }}</div>
                                        <div class="small text-muted">
                                            <span class="d-inline-block me-3"><i class="fas fa-envelope me-1"></i>{{ $user->email }}</span>
                                            @if($user->phone)
                                                <span class="d-inline-block me-3"><i class="fas fa-phone me-1"></i>{{ $user->phone }}</span>
                                            @endif
                                            <span class="d-inline-block"><i class="fas fa-language me-1"></i>{{ $user->language ?? 'English' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $user->company_name ?? '-' }}</div>
                                <div class="small text-muted">
                                    <i class="fas fa-briefcase me-1"></i> Exp: {{ $user->years_experience ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $user->created_at->format('Y-m-d') }}</div>
                                <div class="small text-muted">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-2 rounded-pill">
                                    <i class="fas fa-hourglass-half me-1"></i> Pending
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="button"
                                            class="btn btn-outline-primary btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#view-{{ $user->id }}">
                                        <i class="fas fa-eye me-1"></i> View
                                    </button>
                                    <form action="{{ route('superadmin.users.approve', $user) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Approve this user?');">
                                        @csrf
                                        <button class="btn btn-success btn-sm">
                                            <i class="fas fa-check me-1"></i> Approve
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Detail Modal -->
                        <div class="modal fade" id="view-{{ $user->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $user->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold" id="modalLabel-{{ $user->id }}">Review User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body pt-2">
                                        <!-- Profile Summary -->
                                        <div class="d-flex gap-4 mb-4 p-3 bg-light rounded-3">
                                            <img src="{{ $photo }}"
                                                 alt=""
                                                 class="rounded-circle border object-fit-cover"
                                                 width="80" height="80">
                                            <div class="flex-grow-1">
                                                <h4 class="mb-1">{{ $user->name }} {{ $user->last_name }}</h4>
                                                <div class="text-muted small">
                                                    <div><i class="fas fa-envelope me-2"></i>{{ $user->email }}</div>
                                                    @if($user->phone)
                                                        <div><i class="fas fa-phone me-2"></i>{{ $user->phone }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-4">
                                            <!-- Company Info -->
                                            <div class="col-md-6">
                                                <div class="bg-light p-3 rounded-3 h-100">
                                                    <h6 class="fw-bold mb-3"><i class="fas fa-building me-2"></i>Company</h6>
                                                    <div class="small">
                                                        <div class="mb-2"><span class="text-muted">Name:</span> <span class="fw-medium">{{ $user->company_name ?? '-' }}</span></div>
                                                        <div class="mb-2"><span class="text-muted">Experience:</span> <span class="fw-medium">{{ $user->years_experience ?? 'N/A' }}</span></div>
                                                        <div class="mb-2"><span class="text-muted">Language:</span> <span class="fw-medium">{{ $user->language ?? 'English' }}</span></div>
                                                        <div class="mb-2"><span class="text-muted">Registered:</span> <span class="fw-medium">{{ $user->created_at->format('Y-m-d H:i') }}</span></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Documents -->
                                            <div class="col-md-6">
                                                <div class="bg-light p-3 rounded-3 h-100">
                                                    <h6 class="fw-bold mb-3"><i class="fas fa-file-alt me-2"></i>Documents</h6>
                                                    @if(!empty($docs))
                                                        <div class="d-flex flex-column gap-2">
                                                            @foreach($docs as $doc)
                                                                @php
                                                                    $file = is_array($doc) ? $doc : ['file_name' => $doc, 'original_name' => basename($doc)];
                                                                @endphp
                                                                <div class="d-flex justify-content-between align-items-center p-2 bg-white rounded-2 border">
                                                                    <div class="text-truncate small" style="max-width: 70%;">
                                                                        <i class="fas fa-file-pdf text-danger me-2"></i>
                                                                        {{ $file['original_name'] ?? basename($file['file_name']) }}
                                                                    </div>
                                                                    <a href="{{ asset('storage/' . $file['file_name']) }}"
                                                                       target="_blank"
                                                                       class="btn btn-sm btn-outline-primary">
                                                                        <i class="fas fa-external-link-alt"></i> View
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="text-muted small p-2">No documents uploaded.</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Rejection Section -->
                                        <div class="mt-4 p-3 bg-danger-soft rounded-3 border border-danger border-opacity-25">
                                            <h6 class="fw-bold text-danger mb-3"><i class="fas fa-times-circle me-2"></i>Reject User</h6>
                                            <form action="{{ route('superadmin.users.reject', $user) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to reject this user?');">
                                                @csrf
                                                <textarea name="rejection_reason"
                                                          class="form-control form-control-sm mb-3"
                                                          rows="2"
                                                          placeholder="Reason for rejection (optional)"></textarea>
                                                <div class="d-flex justify-content-end gap-2">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                    <button class="btn btn-danger btn-sm">
                                                        <i class="fas fa-ban me-1"></i> Confirm Rejection
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <form action="{{ route('superadmin.users.approve', $user) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Approve this user?');">
                                            @csrf
                                            <button class="btn btn-success">
                                                <i class="fas fa-check me-1"></i> Approve
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-check-circle fa-2x mb-3 d-block"></i>
                                No pending users found. 🎉
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $users->appends(request()->query())->links() }}
    </div>
</div>
@endsection
@extends('admin.layouts.superadmin')

@section('title', 'Crew')

@section('content')
<div class="container-fluid px-4">
    
    <!-- Header Block -->
    <div class="header-block bg-white p-4 rounded-3 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1 text-primary">
                    <i class="fas fa-users me-2"></i>Management of crews
                </h1>
                <p class="mb-0 text-muted">Manage and assign your work teams</p>
            </div>
            <a href="{{ route('superadmin.crew.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>New Crew
            </a>
        </div>
    </div>

    <!-- Stats Block -->
    <div class="stats-block row g-4 mb-4">
        <div class="col-md-6">
            <div class="card stat-card bg-primary bg-opacity-10 border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-25 p-3 rounded-4 me-3">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted">Total Crews</h6>
                            <h3 class="mb-0">{{ $crews->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card stat-card bg-success bg-opacity-10 border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-25 p-3 rounded-4 me-3">
                            <i class="fas fa-user-check fa-2x text-success"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted">Assigned</h6>
                            <h3 class="mb-0">{{ $crews->filter(fn($crew) => $crew->subcontractors->isNotEmpty())->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        


    </div>

    <!-- Main Content Block -->
    <div class="main-content-block">
        <div class="card border-0 shadow-sm">
            
            <!-- Filters Block -->
            <div class="filters-block bg-light p-3 border-bottom">
                <form method="GET" action="{{ route('superadmin.crew.index') }}">
                    <div class="row g-3 align-items-end">

                        <!-- Search Input -->
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <div class="position-relative">
                                <i class="fas fa-search position-absolute ms-3 mt-2 text-muted"></i>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control ps-5" placeholder="Search for crews...">
                            </div>
                        </div>

                        <!-- States Filter -->
                        <div class="col-md-4">
                            <label class="form-label">Filter by States</label>
                            @php
                                $availableStates = [
                                    'AL'=>'Alabama', 'AK'=>'Alaska', 'AZ'=>'Arizona', 'AR'=>'Arkansas', 'CA'=>'California',
                                    'CO'=>'Colorado', 'CT'=>'Connecticut', 'DE'=>'Delaware', 'FL'=>'Florida', 'GA'=>'Georgia',
                                    'HI'=>'Hawaii', 'ID'=>'Idaho', 'IL'=>'Illinois', 'IN'=>'Indiana', 'IA'=>'Iowa',
                                    'KS'=>'Kansas', 'KY'=>'Kentucky', 'LA'=>'Louisiana', 'ME'=>'Maine', 'MD'=>'Maryland',
                                    'MA'=>'Massachusetts', 'MI'=>'Michigan', 'MN'=>'Minnesota', 'MS'=>'Mississippi', 'MO'=>'Missouri',
                                    'MT'=>'Montana', 'NE'=>'Nebraska', 'NV'=>'Nevada', 'NH'=>'New Hampshire', 'NJ'=>'New Jersey',
                                    'NM'=>'New Mexico', 'NY'=>'New York', 'NC'=>'North Carolina', 'ND'=>'North Dakota',
                                    'OH'=>'Ohio', 'OK'=>'Oklahoma', 'OR'=>'Oregon', 'PA'=>'Pennsylvania', 'RI'=>'Rhode Island',
                                    'SC'=>'South Carolina', 'SD'=>'South Dakota', 'TN'=>'Tennessee', 'TX'=>'Texas', 'UT'=>'Utah',
                                    'VT'=>'Vermont', 'VA'=>'Virginia', 'WA'=>'Washington', 'WV'=>'West Virginia',
                                    'WI'=>'Wisconsin', 'WY'=>'Wyoming'
                                ];
                                $selectedStates = request()->get('states', []);
                            @endphp

                            <select name="states[]" class="form-select" multiple>
                                @foreach($availableStates as $code => $name)
                                    <option value="{{ $code }}" {{ in_array($code, $selectedStates) ? 'selected' : '' }}>
                                        {{ $name }} ({{ $code }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl (Cmd on Mac) to select multiple</small>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <!-- Actions -->
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('superadmin.crew.index') }}" class="btn btn-outline-secondary w-100">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>



           <!-- Crew List Block -->
            <div class="crew-list-block">
                @if ($crews->isEmpty())
                    <div class="empty-state text-center py-5">
                        <i class="fas fa-users-slash fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted mb-3">No crews registered</h4>
                        <p class="text-muted mb-4">Start by adding your first work team</p>
                        <a href="{{ route('superadmin.crew.create') }}" class="btn btn-primary px-4">
                            <i class="fas fa-plus me-2"></i>Create First Crew
                        </a>
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach ($crews as $crew)
                            <!-- Crew Item Block -->
                            <div class="list-group-item p-0">
                                <div class="crew-item p-3">
                                    <div class="row align-items-center">
                                        <!-- Crew Info -->
                                        <div class="col-lg-4 mb-3 mb-lg-0">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-3">
                                                    <span class="avatar-initials bg-primary text-white">
                                                        {{ strtoupper(substr($crew->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h5 class="mb-1">{{ $crew->name }}</h5>
                                                    
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                            <i class="fas fa-building me-1"></i>{{ $crew->company }}
                                                        </span>
                                                        @if($crew->subcontractors->isNotEmpty())
                                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                                <i class="fas fa-user-check me-1"></i>Assigned
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <h5 class="mb-1 d-flex align-items-center">
                                                    @if($crew->is_active)
                                                        <span class="badge bg-success bg-opacity-10 text-success ms-2">
                                                            <i class="fas fa-check-circle me-1"></i> Active
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger bg-opacity-10 text-danger ms-2">
                                                            <i class="fas fa-times-circle me-1"></i> Inactive
                                                        </span>
                                                    @endif
                                                </h5>
                                                
                                            </div>
                                        </div>
                                        
                                        <!-- Contact Info -->
                                        <div class="col-lg-3 mb-3 mb-lg-0">
                                            <div class="contact-info">
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="fas fa-envelope text-muted me-2"></i>
                                                    <small>{{ $crew->email }}</small>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-phone text-muted me-2"></i>
                                                    <small>{{ $crew->phone ?: 'Not specified' }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                      <!-- States Info -->
                                    <div class="col-lg-2 mb-3 mb-lg-0">
                                        @if($crew->states && is_array($crew->states) && count($crew->states))
                                            <div class="states-info">
                                                <h6 class="text-muted mb-1">Operating States:</h6>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach(array_slice($crew->states, 0, 3) as $state)
                                                        <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-2 py-1">
                                                            {{ $state }}
                                                        </span>
                                                    @endforeach

                                                    @if(count($crew->states) > 3)
                                                        @php
                                                            $remaining = array_slice($crew->states, 3);
                                                        @endphp
                                                        <span class="badge rounded-pill bg-light text-dark px-2 py-1" 
                                                            data-bs-toggle="tooltip" 
                                                            data-bs-placement="top" 
                                                            title="{{ implode(', ', $remaining) }}">
                                                            +{{ count($remaining) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Not specified</span>
                                        @endif
                                    </div>

                                        
                                        <!-- Actions -->
                                        <div class="col-lg-3 text-lg-end">
                                            <div class="d-flex justify-content-lg-end gap-2">
                                                <a href="{{ route('superadmin.crew.assign', $crew->id) }}" 
                                                class="btn btn-sm btn-outline-primary rounded-pill"
                                                data-bs-toggle="tooltip" title="Assign subcontractors">
                                                    <i class="fas fa-user-plus me-1"></i>Assign
                                                </a>
                                                
                                                <div class="btn-group">
                                                    <a href="{{ route('superadmin.crew.edit', $crew->id) }}" 
                                                    class="btn btn-sm btn-outline-secondary"
                                                    data-bs-toggle="tooltip" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('superadmin.crew.destroy', $crew->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this crew?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Subcontractors Block -->
                                @if($crew->subcontractors->isNotEmpty())
                                    <div class="subcontractors-block bg-light p-3 border-top">
                                        <h6 class="text-muted mb-2">
                                            <i class="fas fa-user-tie me-2"></i>Assigned Crew Manager:
                                        </h6>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($crew->subcontractors as $sub)
                                                <div class="subcontractor-badge">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-xs me-2">
                                                            <span class="avatar-initials bg-warning text-dark">
                                                                {{ strtoupper(substr($sub->name, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <strong>{{ $sub->name }} {{ $sub->last_name }}</strong>
                                                            <small class="d-block text-muted">{{ $sub->company_name }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination Block -->
                    <div class="pagination-block px-4 py-3 border-top">
                        {{ $crews->links() }}
                    </div>


                @endif
            </div>
            
        
        </div>
    </div>

</div>

<style>

    .pagination-block {
        background-color: #f8f9fa;
    }

    .pagination-block .pagination {
        justify-content: center;
        margin-bottom: 0;
    }

    .pagination-block .page-item.active .page-link {
        background-color: #3b7ddd;
        border-color: #3b7ddd;
    }

    .pagination-block .page-link {
        color: #3b7ddd;
        border-radius: 0.375rem;
        margin: 0 3px;
    }

    .pagination-block .page-link:hover {
        color: #2a5ea5;
    }
    /* Estilos para los bloques */
    .header-block {
        background-color: #f8fafc;
        border-left: 4px solid #3b7ddd;
    }
    
    .stat-card {
        transition: transform 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .avatar {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    
    .avatar-initials {
        font-weight: 600;
        font-size: 1.25rem;
    }
    
    .avatar-xs {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }
    
    .crew-item {
        transition: background-color 0.2s;
    }
    
    .crew-item:hover {
        background-color: #f8f9fa;
    }
    
    .subcontractor-badge {
        padding: 0.5rem;
        background-color: white;
        border-radius: 0.5rem;
        border: 1px solid #e9ecef;
        transition: transform 0.2s;
    }
    
    .subcontractor-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .empty-state {
        opacity: 0.7;
    }
    
    .search-box {
        position: relative;
    }
    
    .search-box i {
        z-index: 10;
    }
</style>

<script>
    // Activar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

@endsection
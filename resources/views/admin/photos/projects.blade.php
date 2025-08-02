@extends('admin.layouts.superadmin')

@section('title', 'Photo Projects Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="mb-8 space-y-4">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Photo Projects</h1>
        <div class="relative max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" placeholder="Search projects..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <!-- Job Requests Section -->
    <section class="mb-10">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h2 class="text-lg leading-6 font-medium text-gray-900">Job Requests</h2>
                <p class="mt-1 text-sm text-gray-500">Photo projects for scheduled jobs</p>
            </div>
            
            @if($jobs->isEmpty())
                <div class="px-4 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No job requests found</h3>
                    <p class="mt-1 text-sm text-gray-500">There are no photo projects for scheduled jobs.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                    @foreach($jobs as $job)
                    <article class="bg-white border border-gray-200 rounded-lg overflow-hidden transition-all duration-150 hover:shadow-md">
                        <div class="p-5">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 bg-blue-100 p-3 rounded-lg">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 truncate">
                                        {{ $job->company_name ?: 'Job #'.$job->id }}
                                    </h3>
                                    @if($job->job_number_name)
                                    <p class="text-sm text-gray-500">{{ $job->job_number_name }}</p>
                                    @endif
                                    
                                    <div class="mt-3 space-y-2 text-sm">
                                        @if($job->customer_first_name || $job->customer_last_name)
                                        <p class="text-gray-700">
                                            {{ $job->customer_first_name }} {{ $job->customer_last_name }}
                                        </p>
                                        @endif
                                        
                                        @if($job->job_address_city || $job->job_address_state)
                                        <p class="text-gray-600">
                                            {{ $job->job_address_city }}, {{ $job->job_address_state }}
                                        </p>
                                        @endif
                                        
                                        @if($job->install_date_requested)
                                        <p class="text-gray-600">
                                            {{ \Carbon\Carbon::parse($job->install_date_requested)->format('M d, Y') }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <a href="{{ route('superadmin.photos.view', ['tipo' => 'job_request', 'id' => $job->id]) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    View Photos
                                </a>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Emergencies Section -->
    <section>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h2 class="text-lg leading-6 font-medium text-gray-900">Emergencies</h2>
                <p class="mt-1 text-sm text-gray-500">Photo projects for emergency situations</p>
            </div>
            
            @if($emergencies->isEmpty())
                <div class="px-4 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No emergencies found</h3>
                    <p class="mt-1 text-sm text-gray-500">There are no photo projects for emergencies.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                    @foreach($emergencies as $emergency)
                    <article class="bg-white border border-gray-200 rounded-lg overflow-hidden transition-all duration-150 hover:shadow-md">
                        <div class="p-5">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 bg-red-100 p-3 rounded-lg">
                                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 truncate">
                                        {{ $emergency->type_of_supplement ?? 'Emergency' }}
                                    </h3>
                                    @if($emergency->job_number_name)
                                    <p class="text-sm text-gray-500">{{ $emergency->job_number_name }}</p>
                                    @endif
                                    
                                    <div class="mt-3 space-y-2 text-sm">
                                        @if($emergency->date_submitted)
                                        <p class="text-gray-700">
                                            {{ \Carbon\Carbon::parse($emergency->date_submitted)->format('M d, Y') }}
                                        </p>
                                        @endif
                                        
                                        @if($emergency->job_city || $emergency->job_state)
                                        <p class="text-gray-600">
                                            {{ $emergency->job_city }}, {{ $emergency->job_state }}
                                        </p>
                                        @endif
                                        
                                        @if($emergency->company_name)
                                        <p class="text-gray-600">
                                            {{ $emergency->company_name }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <a href="{{ route('superadmin.photos.view', ['tipo' => 'emergency', 'id' => $emergency->id]) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    View Photos
                                </a>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
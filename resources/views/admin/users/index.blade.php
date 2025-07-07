@extends('admin.layouts.superadmin')


@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Correctable Card -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="bg-primary px-6 py-4">
            <h3 class="text-lg font-medium text-white">Contractor</h3>
        </div>
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-4xl font-bold text-gray-800">{{ $contractors }}</span>
                <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-users mr-1"></i> Group
                </span>
            </div>
            <div class="border-t border-gray-200 pt-4">
                <a href="{{ route('superadmin.users.contractors') }}" class="inline-flex items-center text-secondary hover:text-accent">
                    <i class="fas fa-eye mr-2"></i> View 
                </a>
                <span class="ml-4 text-gray-500">
                    <i class="fas fa-user-tie mr-1"></i> 
                </span>
            </div>
            
        </div>
    </div>

    <!-- Subconnotable Card -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="bg-primary px-6 py-4">
            <h3 class="text-lg font-medium text-white">Crew Manager</h3>
        </div>
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-4xl font-bold text-gray-800">{{ $subcontractors }}</span>
                <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-users mr-1"></i> Group
                </span>
            </div>
            <div class="border-t border-gray-200 pt-4">
                <a href="{{ route('superadmin.subcontractors.index') }}" class="inline-flex items-center text-secondary hover:text-accent">
                    <i class="fas fa-eye mr-2"></i> View
                </a>
                <span class="ml-4 text-gray-500">
                    <i class="fas fa-user-tie mr-1"></i> 
                </span>
            </div>
        </div>
    </div>

    <!-- Dietas Card -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="bg-primary px-6 py-4">
            <h3 class="text-lg font-medium text-white">Offers</h3>
        </div>
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-4xl font-bold text-gray-800">7</span>
                <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i> Active
                </span>
            </div>
            <div class="border-t border-gray-200 pt-4">
                <a href="{{ route('superadmin.calendar.index') }}" class="inline-flex items-center text-secondary hover:text-accent">
                    <i class="fas fa-eye mr-2"></i> View
                </a>
                <span class="ml-4 text-gray-500">
                    <i class="fas fa-user-shield mr-1"></i> O
                </span>
            </div>
        </div>
    </div>

    
</div>


@endsection
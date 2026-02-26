@extends('admin.layouts.superadmin')

@section('title', 'Dashboard')

@section('content')

@php
    $offersTotal = $offersAssigned + $offersUnassigned;
    $pctAssigned = $offersTotal ? round(($offersAssigned / $offersTotal) * 100) : 0;
    $pctUnassigned = 100 - $pctAssigned;
@endphp

<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Overview</h2>
    <p class="text-gray-600">Platform overview and recent growth</p>
</div>

{{-- Main Cards with Integrated Growth Stats --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    {{-- Contractor Card --}}
    <div class="bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-lg border border-blue-100 overflow-hidden transition-all duration-300 hover:shadow-xl">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5">
            <h3 class="text-xl font-bold text-white flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                    <i class="fas fa-hard-hat text-white text-lg"></i>
                </div>
                <span>Total Contractors</span>
            </h3>
        </div>
        <div class="p-6">
            <div class="flex items-end justify-between mb-4">
                <span class="text-5xl font-bold text-gray-900">{{ $contractors }}</span>
                <div class="text-right">
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                        <i class="fas fa-users mr-1.5"></i> Total
                    </span>
                </div>
            </div>

            {{-- Growth Stats Inside Main Card --}}
            <div class="bg-white rounded-xl p-4 border border-blue-100 mb-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">New this month</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $contractorsLastMonth }}</p>
                    </div>
                    <div class="text-right">
                        @if($growthContractors > 0)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-arrow-up mr-1 text-xs"></i>
                                +{{ $growthContractors }}%
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                {{ $growthContractors }}%
                            </span>
                        @endif
                        <p class="text-xs text-gray-500 mt-1">Last 30 days</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-4 flex items-center justify-between">
                <a href="{{ route('superadmin.users.contractors') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold transition-colors">
                    <i class="fas fa-eye"></i>
                    View All
                </a>
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-user-tie text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Crew Manager Card --}}
    <div class="bg-gradient-to-br from-white to-emerald-50 rounded-2xl shadow-lg border border-emerald-100 overflow-hidden transition-all duration-300 hover:shadow-xl">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-5">
            <h3 class="text-xl font-bold text-white flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                    <i class="fas fa-people-carry-box text-white text-lg"></i>
                </div>
                <span>Total Crew Managers</span>
            </h3>
        </div>
        <div class="p-6">
            <div class="flex items-end justify-between mb-4">
                <span class="text-5xl font-bold text-gray-900">{{ $subcontractors }}</span>
                <div class="text-right">
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">
                        <i class="fas fa-users mr-1.5"></i> Total
                    </span>
                </div>
            </div>

            {{-- Growth Stats Inside Main Card --}}
            <div class="bg-white rounded-xl p-4 border border-emerald-100 mb-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">New this month</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $subcontractorsLastMonth }}</p>
                    </div>
                    <div class="text-right">
                        @if($growthSubcontractors > 0)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-arrow-up mr-1 text-xs"></i>
                                +{{ $growthSubcontractors }}%
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                {{ $growthSubcontractors }}%
                            </span>
                        @endif
                        <p class="text-xs text-gray-500 mt-1">Last 30 days</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-4 flex items-center justify-between">
                <a href="{{ route('superadmin.subcontractors.index') }}" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-800 font-semibold transition-colors">
                    <i class="fas fa-eye"></i>
                    View All
                </a>
                <div class="p-3 bg-emerald-100 rounded-full">
                    <i class="fas fa-user-gear text-emerald-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Offers Card --}}
    <div class="bg-gradient-to-br from-white to-purple-50 rounded-2xl shadow-lg border border-purple-100 overflow-hidden transition-all duration-300 hover:shadow-xl">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-5">
            <h3 class="text-xl font-bold text-white flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                    <i class="fas fa-briefcase text-white text-lg"></i>
                </div>
                <span>Total Offers</span>
            </h3>
        </div>
        <div class="p-6">
            <div class="flex items-end justify-between mb-4">
                <span class="text-5xl font-bold text-gray-900">{{ $offersTotal }}</span>
                <div class="text-right">
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold bg-purple-100 text-purple-700 border border-purple-200">
                        <i class="far fa-circle mr-1.5"></i> Total
                    </span>
                </div>
            </div>

            {{-- Growth Stats Inside Main Card --}}
            <div class="bg-white rounded-xl p-4 border border-purple-100 mb-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">New this month</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $offersLastMonth }}</p>
                    </div>
                    <div class="text-right">
                        @if($growthOffers > 0)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-arrow-up mr-1 text-xs"></i>
                                +{{ $growthOffers }}%
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                {{ $growthOffers }}%
                            </span>
                        @endif
                        <p class="text-xs text-gray-500 mt-1">Last 30 days</p>
                    </div>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="p-1.5 bg-green-100 rounded-lg">
                            <i class="fas fa-check-circle text-green-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-semibold text-green-800">Assigned</span>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $offersAssigned }}</div>
                    <div class="text-xs font-medium text-green-600 mt-1">{{ $pctAssigned }}%</div>
                </div>
                
                <div class="bg-amber-50 rounded-xl p-4 border border-amber-100">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="p-1.5 bg-amber-100 rounded-lg">
                            <i class="fas fa-user-clock text-amber-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-semibold text-amber-800">Unassigned</span>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $offersUnassigned }}</div>
                    <div class="text-xs font-medium text-amber-600 mt-1">{{ $pctUnassigned }}%</div>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="mb-4">
                <div class="flex justify-between text-sm font-medium text-gray-600 mb-2">
                    <span>Assignment Progress</span>
                    <span>{{ $pctAssigned }}% Complete</span>
                </div>
                <div class="w-full h-3 rounded-full bg-gray-200 overflow-hidden">
                    <div class="h-3 bg-gradient-to-r from-green-500 to-green-400 rounded-full transition-all duration-1000 ease-out" style="width: {{ $pctAssigned }}%"></div>
                </div>
            </div>

            {{-- Footer Links --}}
            <div class="border-t border-gray-100 pt-4 flex items-center justify-between">
                <a href="{{ route('superadmin.calendar.index') }}" class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-800 font-semibold transition-colors">
                    <i class="fas fa-eye"></i>
                    View Calendar
                </a>
            </div>
        </div>
    </div>

    {{-- Pending Approval Card --}}
    <div class="mt-8">
        <div class="bg-gradient-to-br from-white to-amber-50 rounded-2xl shadow-lg border border-amber-100 overflow-hidden transition-all duration-300 hover:shadow-xl">
            <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-5">
                <h3 class="text-xl font-bold text-white flex items-center gap-3">
                    <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                        <i class="fas fa-user-clock text-white text-lg"></i>
                    </div>
                    <span>Users Pending Approval</span>
                </h3>
            </div>

            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Awaiting review</p>
                        <p class="text-5xl font-bold text-gray-900">
                            {{ $pendingUsers }}
                        </p>
                    </div>

                    @if($pendingUsers > 0)
                        <span class="px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-700 border border-red-200 animate-pulse">
                            Action Required
                        </span>
                    @else
                        <span class="px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-700 border border-green-200">
                            All Approved
                        </span>
                    @endif
                </div>

                <div class="border-t border-gray-100 pt-4 flex items-center justify-between">
                    <a href="{{ route('superadmin.users.pending') }}" 
                    class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-800 font-semibold transition-colors">
                        <i class="fas fa-eye"></i>
                        Review Users
                    </a>

                    <div class="p-3 bg-amber-100 rounded-full">
                        <i class="fas fa-shield-alt text-amber-600 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
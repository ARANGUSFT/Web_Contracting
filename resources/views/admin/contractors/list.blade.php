@extends('admin.layouts.superadmin')

@section('title', 'Contractors Management')

@section('actions')
    <div class="flex items-center space-x-3">
    
        <div class="text-sm text-gray-500">
            <i class="fas fa-filter mr-1"></i>
            {{ $contractors }} total contractors
        </div>
    </div>
@endsection

@section('content')

    <!-- Header Card -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl mb-6 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="h-14 w-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                    <i class="fas fa-hard-hat text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Contractors</h1>
                    <p class="text-blue-100 mt-1">Manage all contractors and their information</p>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                    <div class="text-sm text-blue-100">Active Contractors</div>
                    <div class="text-xl font-bold text-white">{{ $contractors }}</div>
                </div>
            </div>
                <a href="{{ route('superadmin.users.index') }}" 
           class="inline-flex items-center px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 rounded-lg p-4 animate-slide-in">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm text-emerald-800">{{ session('success') }}</p>
                </div>
                <button type="button" onclick="this.parentElement.parentElement.style.display='none'" 
                        class="ml-auto -mx-1.5 -my-1.5 text-emerald-500 hover:text-emerald-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-filter text-gray-400 mr-2"></i> Filter Contractors
            </h3>
        </div>
        
        <form method="GET" action="{{ route('superadmin.users.contractors') }}" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Search Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search mr-1 text-gray-400"></i> Search
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               placeholder="Name, email or phone">
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-check mr-1 text-gray-400"></i> Status
                    </label>
                    <div class="relative">
                        <select name="status" 
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-200 appearance-none bg-white">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end space-x-3">
                    <button type="submit" 
                            class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-filter mr-2"></i> Apply Filters
                    </button>
                    <a href="{{ route('superadmin.users.contractors') }}" 
                       class="inline-flex items-center px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-200">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Contractors Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <h3 class="text-lg font-semibold text-gray-800">Contractors List</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $users->total() }} {{ Str::plural('contractor', $users->total()) }}
                    </span>
                </div>
                <div class="text-sm text-gray-500">
                    Page {{ $users->currentPage() }} of {{ $users->lastPage() }}
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50/30">
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Contractor
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Contact
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Company
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-blue-50/30 transition duration-150">
                            <!-- Contractor Info -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-sm">
                                        {{ substr($user->name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">
                                            {{ $user->name }} {{ $user->last_name }}
                                        </div>
                                        @if($user->position)
                                            <div class="text-sm text-gray-500 mt-0.5">
                                                <i class="fas fa-briefcase mr-1"></i> {{ $user->position }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Contact Info -->
                            <td class="px-6 py-4">
                                <div class="space-y-1.5">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                        <a href="mailto:{{ $user->email }}" class="hover:text-blue-600 transition-colors">
                                            {{ $user->email }}
                                        </a>
                                    </div>
                                    @if($user->phone)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-phone mr-2 text-gray-400"></i>
                                            <a href="tel:{{ $user->phone }}" class="hover:text-gray-900 transition-colors">
                                                {{ $user->phone }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Company -->
                            <td class="px-6 py-4">
                                @if($user->company_name)
                                    <div class="flex items-center text-sm text-gray-700">
                                        <i class="fas fa-building mr-2 text-gray-400"></i>
                                        {{ $user->company_name }}
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400 italic">Not specified</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                @if ($user->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        <i class="fas fa-check-circle mr-1.5"></i> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1.5"></i> Inactive
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <!-- Edit Button -->
                                    <a href="{{ route('superadmin.contractors.edit', $user->id) }}" 
                                       class="inline-flex items-center p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition duration-200"
                                       title="Edit contractor">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('superadmin.users.destroy', $user->id) }}" 
                                          method="POST" 
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                onclick="confirmDelete('{{ $user->name }}', this.closest('form'))"
                                                class="inline-flex items-center p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition duration-200"
                                                title="Delete contractor">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-gray-400 mb-3">
                                    <i class="fas fa-users text-4xl mb-4"></i>
                                </div>
                                <h4 class="text-lg font-medium text-gray-600 mb-2">No contractors found</h4>
                                <p class="text-gray-500 max-w-md mx-auto">
                                    @if(request()->has('search') || request()->has('status'))
                                        Try adjusting your filters to find what you're looking for.
                                    @else
                                        No contractors have been added yet. Add your first contractor to get started.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
                {{ $users->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>

    <script>
        function confirmDelete(contractorName, form) {
            Swal.fire({
                title: 'Delete Contractor?',
                html: `<div class="text-center">
                         <div class="mx-auto mb-4 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                           <i class="fas fa-trash-alt text-red-600 text-xl"></i>
                         </div>
                         <p class="text-gray-700">Are you sure you want to delete <strong>${contractorName}</strong>?</p>
                         <p class="text-sm text-gray-500 mt-2">This action cannot be undone.</p>
                       </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'px-5 py-2.5',
                    cancelButton: 'px-5 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        // Add animation for success message
        document.addEventListener('DOMContentLoaded', function() {
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideIn {
                    from {
                        transform: translateY(-20px);
                        opacity: 0;
                    }
                    to {
                        transform: translateY(0);
                        opacity: 1;
                    }
                }
                .animate-slide-in {
                    animation: slideIn 0.3s ease-out;
                }
            `;
            document.head.appendChild(style);
        });
    </script>

    <style>
        /* Custom scrollbar for table */
        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Smooth transitions */
        .transition-colors {
            transition: color 0.2s ease, background-color 0.2s ease;
        }

        /* Table row hover effect */
        tr {
            transition: background-color 0.15s ease;
        }

        /* Card shadow on hover */
        .shadow-sm {
            transition: box-shadow 0.3s ease;
        }
        .shadow-sm:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
@endsection
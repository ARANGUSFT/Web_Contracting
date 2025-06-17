@extends('admin.layouts.superadmin')

@section('title', 'Contractors')

@section('actions')
    <div class="flex space-x-2">
        <a href="{{ URL::previous() }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
  
    </div>
@endsection

@section('content')

    <div class="bg-white shadow-lg rounded-xl overflow-hidden">

        <!-- Table Header -->
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                <div class="h-12 w-12 rounded-full bg-secondary flex items-center justify-center">
                    <i class="fas fa-hard-hat text-white text-xl"></i>
                </div>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white">Contractors Management</h2>
                <div class="mt-1 flex items-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white text-primary">
                        <i class="fas fa-users mr-1"></i>
                        Total: {{ $contractors }} contractors
                    </span>
                
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-50 text-green-700 px-6 py-3 flex items-center border-l-4 border-green-500">
                <i class="fas fa-check-circle mr-3 text-green-600"></i>
                <div>{{ session('success') }}</div>
                <button class="ml-auto text-green-700 hover:text-green-900" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                    
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="#" class="flex items-center">Name <i class="fas fa-sort ml-1"></i></a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($users as $user)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary-light flex items-center justify-center text-white text-sm font-bold">
                                    {{ substr($user->name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }} {{ $user->last_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->position }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="mailto:{{ $user->email }}" class="text-secondary hover:text-accent">{{ $user->email }}</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="tel:{{ $user->phone }}" class="hover:text-gray-900">{{ $user->phone }}</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->company_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($user->is_active)
                                <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Active
                                </span>
                            @else
                                <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-3">
                        
                                <a href="{{ route('superadmin.contractors.edit', $user->id) }}" class="text-secondary hover:text-accent" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                
                                <form id="deleteContractorForm" action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="text-danger bg-transparent border-0" title="Delete" onclick="confirmDeleteContractor()">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                
                                
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

@endsection
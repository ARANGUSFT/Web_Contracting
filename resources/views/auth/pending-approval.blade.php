@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 p-4">
    <div class="bg-white shadow-2xl rounded-2xl p-8 max-w-md w-full text-center transform transition-all hover:scale-[1.02] duration-300">
        
        {{-- Icon with soft animation --}}
        <div class="mb-6 animate-pulse">
            <div class="inline-block p-4 bg-amber-100 rounded-full">
                <i class="fas fa-clock text-6xl text-amber-600"></i>
            </div>
        </div>

        {{-- More descriptive title --}}
        <h1 class="text-3xl font-bold text-gray-800 mb-3">
            Account Registered!
        </h1>
        
        <h2 class="text-xl font-semibold text-amber-600 mb-4">
            Pending Approval
        </h2>

        {{-- Main message with improved wording --}}
        <div class="space-y-3 text-gray-600 mb-8">
            <p class="text-lg">
                Your account has been successfully registered.
            </p>
            <p class="border-t border-b border-gray-100 py-3 text-base">
                An administrator will review your request shortly.<br>
                You will receive an email once your account is activated.
            </p>
        </div>

        {{-- Button with icon and better visual feedback --}}
        <a href="{{ route('login') }}" 
           class="inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-8 py-3 rounded-xl 
                  font-semibold text-lg shadow-md hover:bg-blue-700 hover:shadow-lg 
                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 
                  transition-all duration-200">
            <i class="fas fa-arrow-left text-sm"></i>
            Back to Login
        </a>

        {{-- Optional help link --}}
        <p class="mt-6 text-sm text-gray-400">
            Need help? <a href="#" class="text-blue-500 hover:underline">Contact us</a>
        </p>
    </div>
</div>
@endsection
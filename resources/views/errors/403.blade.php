@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white shadow rounded-lg p-6 text-center">
            <h1 class="text-4xl font-bold mb-4">403 Forbidden</h1>
            <p class="text-xl mb-6">You do not have permission to access this resource.</p>
            <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline">Go Back to Dashboard</a>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-6">Dashboard</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Admin Dashboard -->
        {{-- @if (Auth::user()->role->name === 'Admin') --}}
        @if (Auth::user()->hasRole('Admin'))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-2">Total Users</h2>
                    <p class="text-2xl">{{ $total_users }}</p>
                    <a href="{{ route('users.index') }}" class="text-blue-500 hover:underline">Manage Users</a>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-2">Total Projects</h2>
                    <p class="text-2xl">{{ $total_projects }}</p>
                    <a href="{{ route('projects.index') }}" class="text-blue-500 hover:underline">Manage Projects</a>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-2">Total Tasks</h2>
                    <p class="text-2xl">{{ $total_tasks }}</p>
                    <a href="{{ route('tasks.index') }}" class="text-blue-500 hover:underline">Manage Tasks</a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Recent Projects</h2>
                    <ul>
                        @foreach ($recent_projects as $project)
                            <li class="mb-2">
                                <a href="{{ route('projects.show', $project) }}" class="text-blue-500 hover:underline">
                                    {{ $project->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Recent Tasks</h2>
                    <ul>
                        @foreach ($recent_tasks as $task)
                            <li class="mb-2">
                                <a href="{{ route('tasks.show', $task) }}" class="text-blue-500 hover:underline">
                                    {{ $task->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Recent Users</h2>
                    <ul>
                        @foreach ($recent_users as $recent_user)
                            <li class="mb-2">
                                <a href="{{ route('users.show', $recent_user) }}" class="text-blue-500 hover:underline">
                                    {{ $recent_user->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            {{-- @endif --}}

            <!-- Team Leader Dashboard -->
            {{-- @if (Auth::user()->role->name === 'Team Leader') --}}
        @elseif (Auth::user()->hasRole('Team Leader'))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-2">My Projects</h2>
                    <p class="text-2xl">{{ $my_projects }}</p>
                    <a href="{{ route('projects.index') }}" class="text-blue-500 hover:underline">View My Projects</a>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-2">Assigned Tasks</h2>
                    <p class="text-2xl">{{ $assigned_tasks }}</p>
                    <a href="{{ route('tasks.index') }}" class="text-blue-500 hover:underline">View Assigned Tasks</a>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-2">Team Members</h2>
                    <p class="text-2xl">{{ $recent_users->count() }}</p>
                    <a href="{{ route('users.index') }}" class="text-blue-500 hover:underline">View Team Members</a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Recent Projects</h2>
                    <ul>
                        @foreach ($recent_projects as $project)
                            <li class="mb-2">
                                <a href="{{ route('projects.show', $project) }}" class="text-blue-500 hover:underline">
                                    {{ $project->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Recent Tasks</h2>
                    <ul>
                        @foreach ($recent_tasks as $task)
                            <li class="mb-2">
                                <a href="{{ route('tasks.show', $task) }}" class="text-blue-500 hover:underline">
                                    {{ $task->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            {{-- @endif --}}

            <!-- Team Member Dashboard -->
            {{-- @if (Auth::user()->role->name === 'Team Member') --}}
        @elseif (Auth::user()->hasRole('Team Member'))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-2">Assigned Tasks</h2>
                    <p class="text-2xl">{{ $assigned_tasks }}</p>
                    <a href="{{ route('tasks.index') }}" class="text-blue-500 hover:underline">View My Tasks</a>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Recent Tasks</h2>
                <ul>
                    @foreach ($recent_tasks as $task)
                        <li class="mb-2">
                            <a href="{{ route('tasks.show', $task) }}" class="text-blue-500 hover:underline">
                                {{ $task->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection

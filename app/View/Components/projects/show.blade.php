@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Project Details</h1>
        <div class="mb-3">
            <strong>Name:</strong> {{ $project->name }}
        </div>
        <div class="mb-3">
            <strong>Description:</strong> {{ $project->description }}
        </div>
        <div class="mb-3">
            <strong>Created By:</strong> {{ $project->creator->name }}
        </div>
        <div class="mb-3">
            <strong>Status:</strong> {{ $project->status }}
        </div>

        <h3>Tasks</h3>
        @if (auth()->user()->role->name == 'Admin' || auth()->user()->role->name == 'Team Leader')
            <a href="{{ route('tasks.create', ['project' => $project->id]) }}" class="btn btn-primary mb-3">Add Task</a>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Assigned To</th>
                    <th>Completed</th>
                    @if (auth()->user()->role->name == 'Admin' ||
                            auth()->user()->role->name == 'Team Leader' ||
                            auth()->user()->role->name == 'Team Member')
                        <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($project->tasks as $task)
                    <tr>
                        <td>{{ $task->id }}</td>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->assignee->name }}</td>
                        <td>{{ $task->is_completed ? 'Yes' : 'No' }}</td>
                        <td>
                            @if (auth()->user()->role->name == 'Team Member' && $task->assigned_to == auth()->user()->id)
                                @if (!$task->is_completed)
                                    <form action="{{ route('tasks.complete', $task->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Mark as Completed</button>
                                    </form>
                                @endif
                            @endif
                            <!-- Add more actions if needed -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

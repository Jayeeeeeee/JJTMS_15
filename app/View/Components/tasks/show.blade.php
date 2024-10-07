@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Task Details</h1>
        <p><strong>Name:</strong> {{ $task->name }}</p>
        <p><strong>Description:</strong> {{ $task->description }}</p>
        <p><strong>Assigned To:</strong> {{ $task->assignee->name }}</p>
        <p><strong>Project:</strong> {{ $task->project->name }}</p>
        <p><strong>Status:</strong> {{ $task->status }}</p>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back to Tasks</a>
    </div>
@endsection

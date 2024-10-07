@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Projects</h1>
        @if (auth()->user()->role->name == 'Admin' || auth()->user()->role->name == 'Team Leader')
            <a href="{{ route('projects.create') }}" class="btn btn-primary mb-3">Add Project</a>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Created By</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr>
                        <td>{{ $project->id }}</td>
                        <td><a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a></td>
                        <td>{{ $project->creator->name }}</td>
                        <td>{{ $project->status }}</td>
                        <td>
                            @if (auth()->user()->role->name == 'Admin' || auth()->user()->role->name == 'Team Leader')
                                <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-warning">Edit
                                    Status</a>
                            @endif
                            @if (auth()->user()->role->name == 'Admin')
                                <form action="{{ route('projects.destroy', $project->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $projects->links() }}
    </div>
@endsection

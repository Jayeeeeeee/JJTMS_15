<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Apply middleware for Admin and Team Leader
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin,Team Leader'])->except(['complete']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = Task::with(['project', 'assignee'])->paginate(10);
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(Request $request)
    {
        $project = Project::findOrFail($request->project);
        $teamMembers = User::whereHas('role', function ($query) {
            $query->where('name', 'Team Member');
        })->get();

        return view('tasks.create', compact(
            'project',
            'teamMembers'
        ));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'status' => 'required|string|in:Pending,In Progress,Completed',
        ]);

        Task::create([
            'name' => $request->name,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'project_id' => $request->project_id,
            'status' => $request->status,
            'created_by' => Auth::user()->id,
        ]);

        return redirect()->route('tasks.index')->with('Success', 'Task created successfully.');
    }

    /**
     * Mark a task as completed (for Team Members).
     */
    public function complete(Task $task)
    {
        $user = Auth::user();

        if ($user->role->name == 'Team Member' && $task->assigned_to == $user->id) {
            $task->is_completed = true;
            $task->save();

            return redirect()->route(
                'tasks.show',
                $task->project_id
            )->with('Success', 'Task marked as completed.');
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        if (Auth::user()->role->name == 'Admin' || Auth::user()->role->name == 'Team Leader') {
            $projects = Project::all();
            $users = User::all();
            return view('tasks.edit', compact('task', 'projects', 'users'));
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'status' => 'required|string|in:Pending,In Progress,Completed',
        ]);

        $task->update([
            'name' => $request->name,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'project_id' => $request->project_id,
            'status' => $request->status,
        ]);
        return redirect()->route('tasks.index')->with('Success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if (Auth::user()->role->name == 'Admin' || Auth::user()->role->name == 'Team Leader') {
            $task->delete();
            return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
        }
    }
}

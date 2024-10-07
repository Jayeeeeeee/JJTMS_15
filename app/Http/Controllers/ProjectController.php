<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProjectController extends Controller
{
    // Apply middleware for Admin and Team Leader
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin,Team Leader'])->except(['show', 'index']);
    }

    /**
     * Display a listing of projects.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role->name == 'Admin' || $user->role->name == 'Team Leader') {
            $projects = Project::with('creator')->paginate(10);
        } else {
            // Team Members can see projects related to their tasks
            $projects = Project::whereHas('tasks', function ($query) use ($user) {
                $query->where('assigned_to', $user->id);
            })->with('creator')->paginate(10);
        }
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_by' =>  Auth::user()->id,
            'status' => 'Pending',
        ]);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        $user = Auth::user();

        // Authorization: Admin and Team Leader can view any project
        // Team Members can view only if they have tasks in the project
        if (
            $user->role->name == 'Admin' || $user->role->name == 'Team Leader' ||
            ($user->role->name == 'Team Member' && $project->tasks()->where('assigned_to', $user->id)->exists())
        ) {
            $project->load('creator', 'tasks.assignee');
            return view('projects.show', compact('project'));
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Show the form for editing the project's status.
     */
    public function edit(Project $project)
    {
        // Only Admin and Team Leader can edit
        if (Auth::user()->role->name == 'Admin' || Auth::user()->role->name == 'Team Leader') {
            return view('projects.edit', compact('project'));
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Update the project's status in storage.
     */
    public function update(Request $request, Project $project)
    {
        // Only Admin and Team Leader can update
        if (Auth::user()->role->name == 'Admin' || Auth::user()->role->name == 'Team Leader') {
            $request->validate([
                'status' => 'required|string|in:Pending,In Progress,Completed',
            ]);

            $project->status = $request->status;
            $project->save();

            return redirect()->route('projects.index')->with('success', 'Project status updated successfully.');
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project)
    {
        // Only Admin can delete projects
        if (Auth::user()->role->name == 'Admin' || Auth::user()->role->name == 'Team Leader') {
            $project->delete();
            return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
        }

        abort(403, 'Unauthorized');
    }
}

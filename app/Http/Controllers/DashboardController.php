<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Role; // Ensure Role model is imported
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Constructor to apply middleware
     */
    public function __construct()
    {
        $this->middleware(['auth']); // Ensure only authenticated users can access
    }

    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->role->name === 'Admin') {
            // Admin Dashboard Data
            $data['total_users'] = User::count();
            $data['total_projects'] = Project::count();
            $data['total_tasks'] = Task::count();
            $data['recent_projects'] = Project::latest()->take(5)->get();
            $data['recent_tasks'] = Task::latest()->take(5)->get();
            $data['recent_users'] = User::latest()->take(5)->get();
        } elseif ($user->role->name === 'Team Leader') {
            // Team Leader Dashboard Data
            $data['my_projects'] = Project::where('created_by', $user->id)->count();
            $data['assigned_tasks'] = Task::where('assigned_to', $user->id)->count();
            $data['recent_projects'] = Project::where('created_by', $user->id)->latest()->take(5)->get();
            $data['recent_tasks'] = Task::where('assigned_to', $user->id)->latest()->take(5)->get();

            // Fetch Team Members dynamically
            $teamMemberRole = Role::where('name', 'Team Member')->first();
            if ($teamMemberRole) {
                $data['recent_users'] = User::where('role_id', $teamMemberRole->id)->latest()->take(5)->get();
            } else {
                $data['recent_users'] = collect(); // Empty collection if role not found
            }
        } elseif ($user->role->name === 'Team Member') {
            // Team Member Dashboard Data
            $data['assigned_tasks'] = Task::where('assigned_to', $user->id)->count();
            $data['recent_tasks'] = Task::where('assigned_to', $user->id)->latest()->take(5)->get();
            // No recent_users needed
        }

        return view('dashboard', $data);
    }
}

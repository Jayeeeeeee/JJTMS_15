<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;

class TaskReminderController extends Controller
{
    public function index()
    {
        // Example: Fetch tasks that are not completed and are due within the next day
        // Adjust based on your application's logic
        $tasks = Task::with('assignee')
            ->where('is_completed', false)
            ->whereHas('project', function ($query) {
                $query->where('status', '!=', 'Completed');
            })
            ->get();

        $reminderTasks = $tasks->map(function ($task) {
            return [
                'title' => $task->title,
                'description' => $task->description,
                'assignee_email' => $task->assignee->email,
                'assignee_name' => $task->assignee->name,
            ];
        });

        return response()->json($reminderTasks);
    }
}

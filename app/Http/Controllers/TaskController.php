<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;


class TaskController extends Controller
{

    public function index(){
        $tasks = Task::all();
        return view('tasks.index',compact('tasks'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'deadline' => 'nullable|date'
        ]);
    
        // Create the task
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => $request->user_id,
            'project_id' => $request->project_id,
            'deadline' => $request->deadline,
        ]);
    
        // Create an activity log entry for the task creation
        ActivityLog::create([
            'project_id' => $request->project_id,
            'user_id' => auth()->id(),
            'action' => 'Task Created',
            'description' => 'Task "' . $task->title . '" was created by ' . auth()->user()->name . ' in project "' . $task->project->name . '".',
        ]);
    
        return redirect()->route('projects.show', $request->project_id)->with('success', 'Task added successfully.');
    }
    

    public function edit(Task $task)
    {
        $users = User::all();
        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'deadline' => 'nullable|date'
        ]);
    
        // Store old values for comparison
        $oldValues = $task->only(['title', 'description', 'status', 'user_id', 'deadline']);
    
        // Update the task
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => $request->user_id,
            'deadline' => $request->deadline,
        ]);
    
        // Prepare the description for activity log
        $changes = [];
        foreach ($oldValues as $key => $oldValue) {
            $newValue = $request->$key;
            if ($oldValue != $newValue) {
                $changes[] = ucfirst($key) . ' changed from "' . $oldValue . '" to "' . $newValue . '"';
            }
        }
        
        $description = 'Task updated by ' . auth()->user()->name . ': ' . implode(', ', $changes);
    
        // Create an activity log entry for the task update
        ActivityLog::create([
            'project_id' => $task->project_id,
            'user_id' => auth()->id(),
            'action' => 'Task Updated',
            'description' => $description,
        ]);
    
        return redirect()->route('projects.show', $task->project_id)->with('success', 'Task updated successfully.');
    }
    

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:ask_to_start,not_started,started,done,refused',
            'refusal_reason' => 'nullable|string',
        ]);
    
        // Store the previous status and refusal reason
        $previousStatus = $task->status;
        $previousRefusalReason = $task->refusal_reason;
    
        // Update the task
        $task->update([
            'status' => $request->status,
            'refusal_reason' => $request->status === 'refused' ? $request->refusal_reason : null,
        ]);
    
        // Prepare the activity log description
        $description = "Task status updated by " . auth()->user()->name;
        
        if ($previousStatus !== $request->status) {
            $description .= ", status changed from '$previousStatus' to '{$request->status}'";
        }
    
        if ($request->status === 'refused' && $previousRefusalReason !== $request->refusal_reason) {
            $description .= ", refusal reason updated";
        } elseif ($request->status !== 'refused' && $previousRefusalReason !== null) {
            $description .= ", refusal reason removed";
        }
    
        // Log the activity
        ActivityLog::create([
            'project_id' => $task->project_id,
            'user_id' => auth()->id(),
            'action' => 'Task Status Update',
            'description' => $description,
        ]);
    
        return redirect()->route('projects.show', $task->project_id)->with('success', 'Task status updated successfully.');
    }
    
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('projects.show', $task->project_id)->with('success', 'Task deleted successfully.');
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
    
        $tasks = Task::where('title', 'like', "%{$query}%")
            ->orWhere('status', 'like', "%{$query}%")
            ->with('project', 'user') // Eager load relationships
            ->get()
            ->map(function ($task) {
                // Ensure deadline is a Carbon instance
                $deadline = $task->deadline ? Carbon::parse($task->deadline) : null;
    
                return [
                    'title' => $task->title,
                    'project_name' => $task->project->name,
                    'client_name' => $task->project->client_name,
                    'user_name' => $task->user->name,
                    'status' => $task->status,
                    'refusal_reason' => $task->refusal_reason,
                    'created_at' => $task->created_at->format('Y-m-d'),
                    'deadline' => $deadline ? $deadline->format('Y-m-d') : null,
                ];
            });
    
        return response()->json(['tasks' => $tasks]);
    }
}

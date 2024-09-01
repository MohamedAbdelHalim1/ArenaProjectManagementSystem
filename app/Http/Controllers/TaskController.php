<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'deadline'=>'nullable|date'

        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => $request->user_id,
            'project_id' => $request->project_id,
            'deadline'=>$request->deadline,

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
            'deadline'=>'nullable|date'
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => $request->user_id,
            'deadline'=>$request->deadline,
        ]);

        return redirect()->route('projects.show', $task->project_id)->with('success', 'Task updated successfully.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:not_started,started,done,refused',
            'refusal_reason' => 'nullable|string',
        ]);
    
        $task->update([
            'status' => $request->status,
            'refusal_reason' => $request->status === 'refused' ? $request->refusal_reason : null,
        ]);
    
        return redirect()->route('projects.show', $task->project_id)->with('success', 'Task status updated successfully.');
    }
    
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('projects.show', $task->project_id)->with('success', 'Task deleted successfully.');
    }
}

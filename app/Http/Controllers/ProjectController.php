<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function create()
    {
        $users = User::all();
        return view('projects.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.status' => 'required|string',
            'tasks.*.user_id' => 'required|exists:users,id',
        ]);

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => $request->user_id,
        ]);

        foreach ($request->tasks as $taskData) {
            $project->tasks()->create([
                'title' => $taskData['title'],
                'description' => $taskData['description'],
                'status' => $taskData['status'],
                'user_id' => $taskData['user_id'],
            ]);
        }

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function index()
    {
        $projects = auth()->user()->role === 'admin' ? Project::all() : auth()->user()->projects;
        return view('dashboard', compact('projects'));
    }

    public function show(Project $project)
    {
        $users = User::all();
        return view('projects.show', compact('project','users'));
    }

    public function edit(Project $project)
    {
        $users = User::all();
        return view('projects.edit', compact('project', 'users'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => $request->user_id,
        ]);

        return redirect()->route('projects.show', $project)->with('success', 'Project updated successfully.');
    }

    public function updateStatus(Request $request, Project $project)
    {
        $request->validate([
            'status' => 'required|in:not_started,started,done,refused',
            'refusal_reason' => 'nullable|string',
        ]);

        $project->status = $request->status;
        if ($request->status === 'refused') {
            $project->refusal_reason = $request->refusal_reason;
        } else {
            $project->refusal_reason = null; // Clear refusal reason if not refused
        }
        $project->save();

        return redirect()->route('dashboard')->with('success', 'Project status updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}

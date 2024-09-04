<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Department;
use App\Models\ActivityLog;
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
            'client_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.status' => 'required|string',
            'tasks.*.user_id' => 'required|exists:users,id',
            'tasks.*.deadline' => 'nullable|date',

        ]);

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => $request->user_id,
            'deadline'=>$request->p_deadline,
            'client_name'=>$request->client_name,
        ]);

        ActivityLog::create([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'action' => 'Project Created',
            'description' => 'Project created by ' . auth()->user()->name . ' and assigned to '. $project->user->name,
        ]);
        

        foreach ($request->tasks as $taskData) {
            // Create the task
            $task = $project->tasks()->create([
                'title' => $taskData['title'],
                'description' => $taskData['description'],
                'status' => $taskData['status'],
                'user_id' => $taskData['user_id'],
                'deadline' => $taskData['deadline'], 
            ]);
    
            // Log the creation of the task
            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'action' => 'Task Created',
                'description' => 'Task "' . $task->title . '" created by ' . auth()->user()->name . ' and assigned to ' . $task->user->name,
            ]);
        }

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function index()
    {
        $projects = auth()->user()->role->id === 1 ? Project::all() : auth()->user()->projects;

        $totalProjects = Project::count();
        $totalTasksStarted = Task::where('status','started')->count();
        $totalTasksNotStarted = Task::where('status','not_started')->count();
        $totalTasksDone = Task::where('status','done')->count();
        $totalStaff = User::count();
        $departments = Department::count();

        return view('dashboard', compact('projects', 'totalProjects', 'totalTasksDone' ,'totalTasksStarted' ,'departments','totalTasksNotStarted', 'totalStaff'));
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
            'client_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'deadline' => 'required|date'
        ]);
    
        // Capture old values
        $oldValues = $project->only(['name', 'description', 'status', 'user_id', 'deadline','client_name']);
        
        // Update the project
        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => $request->user_id,
            'deadline' => $request->deadline,
            'client_name' => $request->client_name,
        ]);
    
        // Capture new values
        $newValues = [
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => $request->user_id,
            'deadline' => $request->deadline,
            'client_name' => $request->client_name,
        ];
    
        // Build description
        $description = 'Project updated by ' . auth()->user()->name . '. Changes: ';
    
        foreach ($oldValues as $key => $oldValue) {
            if ($oldValue != $newValues[$key]) {
                $description .= ucfirst($key) . ' changed from "' . $oldValue . '" to "' . $newValues[$key] . '". ';
            }
        }
    
        // Create activity log
        ActivityLog::create([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'action' => 'Project Update',
            'description' => $description,
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
            $project->refusal_reason = null;
        }
        $project->save();


        return redirect()->route('dashboard')->with('success', 'Project status updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    public function logs($id){
        $activities = ActivityLog::where('project_id',$id)->get();
        return view('projects.activity',compact('activities'));
    }
}

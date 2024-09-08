<?php

namespace App\Http\Controllers;

use App\Models\ListItem;
use App\Models\Task;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class ListItemController extends Controller
{
    public function index(Task $task)
    {
        return $task->listItems()->with('children', 'staff')->get();
    }

    public function store(Request $request, Task $task)
    {
        $listItem = $task->listItems()->create([
            'text' => $request->input('text'),
            'parent_id' => $request->input('parent_id'),
            'staff_id' => $request->input('staff_id'),
        ]);

        return response()->json($listItem);
    }

    public function update(Request $request, ListItem $listItem)
    {
        $listItem->update([
            'is_checked' => $request->input('is_checked'),
            'staff_id' => $request->input('staff_id'), // Update staff_id if present
        ]);

        return response()->json($listItem);
    }

 // Fetch departments
    public function getDepartments()
    {
        return response()->json(Department::all());
    }

    // Fetch users based on department
    public function getUsers(Request $request)
    {
        $departmentId = $request->query('department_id');
        $users = User::where('department_id', $departmentId)->get();
        return response()->json($users);
    }

    // Assign user to TaskList
    public function assignTaskList(Request $request)
    {
        $taskList = ListItem::findOrFail($request->input('tasklist_id'));
        $userId = $request->input('user_id');
        $taskList->staff_id = $userId;
        $taskList->save();
    
        $user = User::findOrFail($userId); // Fetch the assigned user
        return response()->json([
            'message' => 'User assigned successfully',
            'assignedUser' => $user->name // Return the user's name
        ]);
    }
    
    
}

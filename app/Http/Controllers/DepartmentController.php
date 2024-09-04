<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;

class DepartmentController extends Controller
{
    public function index(){
        $departments = Department::all();
        $staffs = User::all();
        return view('departments.index', compact('departments', 'staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'staffs' => 'nullable|array|exists:users,id', // Validate staff IDs
        ]);

        $department = Department::create([
            'name' => $request->name,
        ]);

        // Attach selected staff to the department
        if ($request->has('staffs')) {
            User::whereIn('id', $request->staffs)->update(['department_id' => $department->id]);
        }

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function show($id)
    {
        $department = Department::with('users')->findOrFail($id);
        return response()->json($department->users);
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }

    public function edit($id)
    {
        $department = Department::with('users')->findOrFail($id);
        $staffs = User::all(); // Adjust if you have a different model for staff

        return response()->json([
            'department' => $department,
            'staffs' => $staffs
        ]);
    }

    // Update the department
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'staffs' => 'array|exists:users,id',
        ]);
    
        $department = Department::findOrFail($id);
        $department->update([
            'name' => $request->name,
        ]);
    
        if (isset($request->staffs) && is_array($request->staffs) && count($request->staffs) != 0) {
            foreach ($request->staffs as $staffId) {
                User::where('id', $staffId)->update(['department_id' => $department->id]);
            }
        }
        
    
        return redirect()->back();
    }
}

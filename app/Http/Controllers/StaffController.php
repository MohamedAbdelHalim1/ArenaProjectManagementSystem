<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index(){
        $users = User::with('role')
        ->get()
        ->sortBy('role.name');
        return view('staff.index',compact('users'));
    }
    public function create(){
        $roles = Role::all();
        return view('staff.create',compact('roles'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'role_id' => ['required'],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' =>$request->role_id,
            'password' => Hash::make($request->password),
        ]);



        return redirect()->route('staff');
    }
    public function edit($id)
    {
        $staff = User::findOrFail($id);
        $roles = Role::all();
        return view('staff.edit', compact('staff', 'roles'));
    }

    // Handle the update
    public function update(Request $request, $id)
    {
        $staff = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $staff->id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $staff->name = $request->input('name');
        $staff->email = $request->input('email');
        $staff->role_id = $request->input('role_id');
        $staff->save();

        return redirect()->route('staff')->with('success', 'Staff updated successfully.');
    }
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back();
    }

    public function track($id){
        $staff = User::with('tasks')->findOrFail($id);
        return view('staff.track',compact('staff'));
    }
}

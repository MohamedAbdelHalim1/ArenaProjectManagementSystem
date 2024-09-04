<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
class RoleController extends Controller
{
    public function index(){
        $roles = Role::all();
        return view('roles.index',compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Role::create([
            'name' => $request->name,
        ]);

        return redirect()->route('role');
    }
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->back();
    }
    public function getUsers(Role $role)
    {
        return response()->json($role->users);
    }
}

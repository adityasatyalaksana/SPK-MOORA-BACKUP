<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->get();
        $roles = \App\Models\Role::with('permissions')->get();
        $permissions = \App\Models\Permission::all();
        return view('admin.user.index', compact('users', 'roles', 'permissions'));
    }

    public function create()
    {
        $roles = \App\Models\Role::all();
        return view('admin.user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:3|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => $request->password,
            'role_id' => $request->role_id,
        ]);

        ActivityLog::log("Menambahkan user " . $user->username . " sebagai " . $user->role->name);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = \App\Models\Role::all();
        return view('admin.user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'password' => 'nullable|string|min:3|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->role_id = $request->role_id;
        
        if ($request->filled('password')) {
            $user->password = $request->password;
        }

        $user->save();

        ActivityLog::log("Mengubah data user " . $user->username);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }
        $username = $user->username;
        $user->delete();

        ActivityLog::log("Menghapus user " . $username);

        return back()->with('success', 'User berhasil dihapus.');
    }

    public function updatePermissions(Request $request)
    {
        $request->validate([
            'permissions' => 'array',
        ]);

        $roles = \App\Models\Role::all();
        foreach ($roles as $role) {
            // Get permissions checked for this role, default to empty array
            $rolePermissions = $request->input('permissions.' . $role->id, []);
            $role->permissions()->sync($rolePermissions);
        }

        ActivityLog::log("Mengubah hak akses dinamis role");

        return redirect()->route('users.index')->with('success', 'Hak akses role berhasil diperbarui.');
    }
}
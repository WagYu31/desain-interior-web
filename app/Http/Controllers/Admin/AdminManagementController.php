<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class AdminManagementController extends Controller
{
    public function index()
    {
        $admins = User::role('admin')->paginate(10);
        return view('admin.admins.index', compact('admins'));
    }
    public function create()
    {
        $roles = Role::whereIn('name', ['admin', 'owner'])->get();
        return view('admin.admins.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole($request->role);
        return redirect()->route('admin.admins.index')->with('success', 'Admin baru berhasil dibuat.');
    }
    public function edit(User $admin)
    { 
        $roles = Role::whereIn('name', ['admin', 'owner'])->get();
        return view('admin.admins.edit', compact('admin', 'roles'));
    }
    public function update(Request $request, User $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);
        $admin->name = $request->name;
        $admin->email = $request->email;
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }
        $admin->save();
        $admin->syncRoles([$request->role]);
        return redirect()->route('admin.admins.index')->with('success', 'Data admin berhasil diperbarui.');
    }
    public function destroy(User $admin)
    {
        if (auth()->id() == $admin->id) { // Jangan biarkan admin menghapus dirinya sendiri
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        $admin->delete();
        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil dihapus.');
    }
}
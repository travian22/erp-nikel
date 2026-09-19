<?php

namespace App\Http\Controllers;

use App\Models\ApplicationLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('employee');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return redirect()->route('users.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,approver,user',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active') ? (bool) $request->is_active : true;

        $user = User::create($validated);

        ApplicationLog::create([
            'user_id' => Auth::id(),
            'activity' => "Menambahkan pengguna baru: {$user->name} ({$user->role})",
            'module' => 'UserManagement',
            'reference_table' => 'users',
            'reference_id' => $user->id,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return redirect()->route('users.index');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,approver,user',
            'is_active' => 'boolean',
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        $user->update($validated);

        ApplicationLog::create([
            'user_id' => Auth::id(),
            'activity' => "Memperbarui data akun pengguna: {$user->name}",
            'module' => 'UserManagement',
            'reference_table' => 'users',
            'reference_id' => $user->id,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $id = $user->id;
        $name = $user->name;
        $user->delete();

        ApplicationLog::create([
            'user_id' => Auth::id(),
            'activity' => "Menghapus akun pengguna: {$name}",
            'module' => 'UserManagement',
            'reference_table' => 'users',
            'reference_id' => $id,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Tampilkan semua user
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $users = User::orderBy('created_at', 'desc')->get();
        return view('users.index', compact('users'));
    }

    // Tampilkan detail user
    public function show($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    // Tampilkan form edit user
    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,warga',
            'is_active' => 'required|boolean'
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->is_active
        ]);

        return redirect('/users')->with('success', 'Data user berhasil diperbarui!');
    }

    // Toggle status aktif user
    public function toggleStatus($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $user = User::findOrFail($id);
        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect('/users')->with('success', "User berhasil $status!");
    }

    // Hapus user
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        // Cek apakah user mencoba menghapus dirinya sendiri
        if (Auth::id() == $id) {
            return redirect('/users')->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect('/users')->with('success', 'User berhasil dihapus!');
    }
}
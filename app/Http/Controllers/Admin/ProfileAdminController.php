<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfileAdminController extends Controller
{
    public function index()
    {
        return view('admin.profile.profile');
    }

    public function store(Request $request)
    {
        $request->validate([
            'img1' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $foto_profile = null;
        if ($request->hasFile('img1')) {
            $file = $request->file('img1');
            // SECURITY FIRST: Safe File Naming
            $foto_profile = time() . '-' . Str::random(10) . '.' . $file->extension();
            $file->move(public_path('foto_profile'), $foto_profile);
        }

        if ($foto_profile) {
            User::find(Auth::id())->update([
                'foto_profile' => $foto_profile
            ]);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function update_profile(Request $request, $ignored_id = null)
    {
        // IGNORING $ignored_id parameter to prevent IDOR. Always use authenticated user's ID.
        $id = Auth::id();

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
        ]);

        $updateData = [
            'nama' => $request->nama,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        User::find($id)->update($updateData);

        return back()->with('success', 'Berhasil Memperbaharui Profile');
    }
}

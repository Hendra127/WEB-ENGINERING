<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function profile()  
    { 
        return view('engineering.profile'); 
    }

    public function settings() 
    { 
        return view('engineering.settings'); 
    }

    public function profileUpdate(Request $req)
    {
        $user = auth()->user();
        $req->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'phone'      => 'nullable|string|max:20',
            'jabatan'    => 'nullable|string|max:255',
            'departemen' => 'nullable|string|max:255',
        ]);

        $user->update($req->only(['name', 'email', 'phone', 'jabatan', 'departemen']));

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function passwordUpdate(Request $req)
    {
        $user = auth()->user();
        $req->validate([
            'current_password' => 'required|current_password',
            'password'         => 'required|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($req->password),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}

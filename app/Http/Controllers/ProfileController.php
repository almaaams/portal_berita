<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Berita;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil berita yang dibuat oleh user (jika admin)
        $berita = [];
        if ($user->role === 'Admin') {
            $berita = Berita::where('user_id', $user->id)->latest()->get();
        }

        return view('profile', compact('user', 'berita'));
    }

    public function show()
    {
        $user = Auth::user(); // Ambil user yang sedang login
        return view('profile', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|min:3|max:20|unique:users,username,' . Auth::id(),
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'password' => 'nullable|min:6',
        ]);

        $user = Auth::user();
        $user->username = $request->username;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect('/profile')->with('success', 'Profil berhasil diperbarui!');
    }


    public function uploadBerita(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'isi' => 'required|string',
            'kategori' => 'required|in:politik,bisnis,teknologi,kesehatan,olahraga'
        ]);

        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('berita', 'public');
        }

        Berita::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'gambar' => $path,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
        ]);

        return redirect()->route('profile.show')->with('success', 'Berita berhasil diunggah!');
    }
}
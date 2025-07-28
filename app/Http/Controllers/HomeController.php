<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Like;
use App\Models\Comment;
use App\Models\User;

class HomeController extends Controller
{
    protected $semua_berita = [
        1 => [
            'id' => 1,
            'judul' => 'Berita A',
            'kategori' => 'Politik',
            'tanggal' => '14 May 2025',
            'gambar' => '/images/news1.jpg',
            'isi' => "Isi berita A..."
        ],
        2 => [
            'id' => 2,
            'judul' => 'Berita B',
            'kategori' => 'Bisnis',
            'tanggal' => '15 May 2025',
            'gambar' => '/images/news2.jpg',
            'isi' => "Isi berita B..."
        ],
        3 => [
            'id' => 3,
            'judul' => 'Berita C',
            'kategori' => 'Teknologi',
            'tanggal' => '16 May 2025',
            'gambar' => '/images/history1.jpg',
            'isi' => "Isi berita C..."
        ],
    ];

    public function index()
    {
        $berita_terkini = [
            ['judul' => 'Berita A', 'tanggal' => '2025-06-02', 'gambar' => 'news1.jpg'],
            ['judul' => 'Berita B', 'tanggal' => '2025-06-01', 'gambar' => 'news2.jpg']
        ];

        return view('beranda', compact('berita_terkini'));
    }

    public function kategori($kategori)
    {
        // Validasi kategori
        $kategoriList = ['politik', 'bisnis', 'teknologi', 'kesehatan', 'olahraga'];
        if (!in_array($kategori, $kategoriList)) {
            abort(404); // Jika kategori tidak valid
        }

        // Ambil berita berdasarkan kategori
        $berita = \App\Models\Berita::where('kategori', $kategori)
                    ->latest()
                    ->get();

        return view('kategori', compact('berita', 'kategori'));
    }

    public function detail($id)
    {
        if (!isset($this->semua_berita[$id])) {
            abort(404);
        }

        $berita = $this->semua_berita[$id];
        $berita['id'] = $id;

        $jumlah_like = Like::where('berita_id', $id)->count();
        $komentar = Comment::where('berita_id', $id)->latest()->get();

        return view('detail', compact('berita', 'jumlah_like', 'komentar'));
    }

    public function like($id)
    {
        $user = Auth::user();

        $alreadyLiked = Like::where('user_id', $user->id)->where('berita_id', $id)->first();

        if (!$alreadyLiked) {
            Like::create([
                'user_id' => $user->id,
                'berita_id' => $id,
            ]);
        }

        return back()->with('success', 'Berita disukai!');
    }

    public function comment(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'required|string'
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'berita_id' => $id,
            'isi' => $request->komentar,
        ]);

        return back()->with('success', 'Komentar berhasil dikirim.');
    }
}

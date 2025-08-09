<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Like;
use App\Models\Comment;
use App\Models\User;
use App\Models\Berita;
use App\Models\History;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil berita terkini (5 berita terbaru)
        $berita_terkini = Berita::latest()
                                ->take(5)
                                ->get();

        // Ambil berita yang paling sering dibaca (berdasarkan history) untuk section history
        $berita_history = collect();

        if (Auth::check()) {
            // Jika user login, ambil history user tersebut
            $historyIds = \DB::table('histories')
                ->select('berita_id', \DB::raw('COUNT(*) as total'))
                ->where('user_id', Auth::id())
                ->groupBy('berita_id')
                ->orderBy('total', 'DESC')
                ->limit(3)
                ->pluck('berita_id');

            if ($historyIds->isNotEmpty()) {
                $berita_history = Berita::whereIn('id', $historyIds)
                    ->orderByRaw('FIELD(id, ' . $historyIds->implode(',') . ')')
                    ->get();
            }
        } else {
            // Jika guest, ambil berita yang paling sering dibaca secara umum
            $historyIds = \DB::table('histories')
                ->select('berita_id', \DB::raw('COUNT(*) as total'))
                ->groupBy('berita_id')
                ->orderBy('total', 'DESC')
                ->limit(3)
                ->pluck('berita_id');

            if ($historyIds->isNotEmpty()) {
                $berita_history = Berita::whereIn('id', $historyIds)
                    ->orderByRaw('FIELD(id, ' . $historyIds->implode(',') . ')')
                    ->get();
            }
        }

        // Jika history kosong, ambil berita random
        if ($berita_history->isEmpty()) {
            $berita_history = Berita::inRandomOrder()->take(3)->get();
        }

        return view('beranda', compact('berita_terkini', 'berita_history'));
    }

    public function terbaru()
    {
        $berita = \App\Models\Berita::orderBy('created_at', 'desc')->paginate(10);

        return view('home', [
            'berita' => $berita,
            'judul_halaman' => 'Berita Terbaru'
        ]);
    }

    public function kategori($kategori)
    {
        // Validasi kategori
        $kategoriList = ['politik', 'bisnis', 'teknologi', 'kesehatan', 'olahraga'];
        if (!in_array(strtolower($kategori), $kategoriList)) {
            abort(404); // Jika kategori tidak valid
        }

        // Ambil berita berdasarkan kategori
        $berita = Berita::where('kategori', strtolower($kategori))
                        ->latest()
                        ->paginate(10); // Tambahkan pagination untuk performa yang lebih baik

        return view('kategori', compact('berita', 'kategori'));
    }

    public function detail($id)
    {
        // Ambil berita berdasarkan ID
        $berita = Berita::findOrFail($id);

        // Simpan ke history jika user login
        if (Auth::check()) {
            // Cek apakah user sudah pernah membaca berita ini hari ini
            $today = now()->toDateString();
            $existingHistory = History::where('user_id', Auth::id())
                                     ->where('berita_id', $id)
                                     ->whereDate('created_at', $today)
                                     ->first();

            // Jika belum ada history hari ini, buat entry baru
            if (!$existingHistory) {
                History::create([
                    'user_id' => Auth::id(),
                    'berita_id' => $id,
                    // timestamp akan otomatis diisi oleh Laravel jika ada created_at/updated_at
                ]);
            }
        }

        // Hitung jumlah like untuk berita ini
        $jumlah_like = Like::where('berita_id', $id)->count();

        // Ambil komentar untuk berita ini dengan informasi user
        $komentar = Comment::where('berita_id', $id)
                          ->with('user') // Eager loading untuk mendapatkan data user
                          ->latest()
                          ->get();

        // Cek apakah user sudah like berita ini (jika user login)
        $sudah_like = false;
        if (Auth::check()) {
            $sudah_like = Like::where('user_id', Auth::id())
                             ->where('berita_id', $id)
                             ->exists();
        }

        return view('detail', compact('berita', 'jumlah_like', 'komentar', 'sudah_like'));
    }

    public function like($id)
    {
        $user = auth()->user();
        $berita = Berita::findOrFail($id);

        $like = Like::where('user_id', $user->id)
                    ->where('berita_id', $id)
                    ->first();

        if ($like) {
            // Jika sudah like → unlike
            $like->delete();
            $message = 'Anda membatalkan like';
        } else {
            // Jika belum like → buat like baru
            Like::create([
                'user_id'   => $user->id,
                'berita_id' => $id
            ]);
            $message = 'Anda menyukai berita ini';
        }

        return redirect()->back()->with('success', $message);
    }

    public function comment(Request $request, $id)
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Validasi input
        $request->validate([
            'komentar' => 'required|string|min:1|max:1000'
        ], [
            'komentar.required' => 'Komentar tidak boleh kosong.',
            'komentar.min' => 'Komentar minimal 1 karakter.',
            'komentar.max' => 'Komentar maksimal 1000 karakter.'
        ]);

        // Pastikan berita exists
        $berita = Berita::findOrFail($id);

        // Buat komentar baru
        Comment::create([
            'user_id' => Auth::id(),
            'berita_id' => $id,
            'isi' => $request->komentar,
        ]);

        return back()->with('success', 'Komentar berhasil dikirim.');
    }

    // Method tambahan untuk pencarian berita
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return redirect()->route('home');
        }

        $berita = Berita::where('judul', 'LIKE', '%' . $query . '%')
                        ->orWhere('isi', 'LIKE', '%' . $query . '%')
                        ->latest()
                        ->paginate(10);

        return view('search', compact('berita', 'query'));
    }

    // Method untuk menampilkan berita berdasarkan tag (jika ada)
    public function tag($tag)
    {
        $berita = Berita::where('tags', 'LIKE', '%' . $tag . '%')
                        ->latest()
                        ->paginate(10);

        return view('tag', compact('berita', 'tag'));
    }

    public function allHistory()
    {
        if (Auth::check()) {
            // Ambil semua berita dari history user
            $historyIds = \DB::table('histories')
                ->select('berita_id', \DB::raw('COUNT(*) as total'))
                ->where('user_id', Auth::id())
                ->groupBy('berita_id')
                ->orderBy('total', 'DESC')
                ->pluck('berita_id');

            $berita = Berita::whereIn('id', $historyIds)
                            ->orderByRaw('FIELD(id, ' . $historyIds->implode(',') . ')')
                            ->paginate(10);
        } else {
            // Random populer untuk guest
            $berita = Berita::inRandomOrder()->paginate(10);
        }

        return view('history', compact('berita'));
    }
}

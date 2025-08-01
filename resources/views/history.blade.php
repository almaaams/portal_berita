@extends('layouts.app')

@section('content')
<div style="padding: 40px; background-color: #111; color: white; min-height: 80vh;">
  <h2 style="text-align: center; margin-bottom: 30px;">
    @auth
      Riwayat Bacaan Anda
    @else
      Berita Populer
    @endauth
  </h2>

  @forelse ($berita as $item)
    <div style="display: flex; gap: 20px; margin-bottom: 30px;">
      <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" style="width: 180px; height: 120px; object-fit: cover; border-radius: 8px;" onerror="this.src='/images/placeholder.jpg'">
      <div>
        <h3>
          <a href="{{ route('berita.detail', $item->id) }}" style="color: white; text-decoration: none;">
            {{ $item->judul }}
          </a>
        </h3>
        <p>{{ Str::limit(strip_tags($item->isi), 100) }}</p>
        <p><small>{{ $item->created_at->format('d M Y') }} | {{ ucfirst($item->kategori) }}</small></p>
      </div>
    </div>
  @empty
    <p style="text-align: center;">Belum ada berita untuk ditampilkan.</p>
  @endforelse

  <div style="text-align: center;">
    {{ $berita->links('pagination::bootstrap-4') }}
  </div>
</div>
@endsection

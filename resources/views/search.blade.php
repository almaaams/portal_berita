@extends('layouts.app')

@section('content')
<div style="padding: 40px;">
  <h2>Hasil Pencarian: "{{ $query }}"</h2>

  @if($berita->isEmpty())
    <p>Tidak ada berita ditemukan.</p>
  @else
    @foreach($berita as $item)
      <div style="display: flex; gap: 20px; margin-bottom: 30px; border-bottom: 1px solid #555; padding-bottom: 15px;">
        <img src="{{ asset('storage/' . $item->gambar) }}"
             alt="{{ $item->judul }}"
             style="width: 180px; height: 120px; object-fit: cover; border-radius: 8px;"
             onerror="this.src='/images/placeholder.jpg'">

        <div>
          <h3>
            <a href="{{ route('berita.detail', $item->id) }}" style="color:white;">
              {{ $item->judul }}
            </a>
          </h3>
          <p>{{ Str::limit(strip_tags($item->isi), 150) }}</p>
          <p><small>{{ $item->created_at->format('d M Y') }}</small></p>
        </div>
      </div>
    @endforeach

    <div>
      {{ $berita->appends(['q' => $query])->links() }}
    </div>
  @endif
</div>
@endsection
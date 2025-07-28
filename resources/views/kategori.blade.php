@extends('layouts.app')

@section('content')
<style>
  .kategori-headline {
    padding: 30px;
    font-size: 36px;
    font-weight: bold;
  }

  .carousel {
    position: relative;
    width: 100%;
    max-width: 900px;
    margin: 0 auto;
  }

  .carousel img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 10px;
  }

  .carousel-caption {
    position: absolute;
    bottom: 20px;
    left: 30px;
    color: white;
    z-index: 2;
  }

  .carousel-caption h2 {
    font-size: 28px;
    margin-bottom: 5px;
  }

  .carousel-caption p {
    font-size: 16px;
    margin: 0;
  }

  .carousel-button {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 32px;
    color: white;
    background: rgba(0,0,0,0.5);
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    z-index: 3;
  }

  .carousel-button.left {
    left: 10px;
  }

  .carousel-button.right {
    right: 10px;
  }

  .dot-indicators {
    text-align: center;
    margin-top: 10px;
  }

  .dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    margin: 0 4px;
    background-color: #ccc;
    border-radius: 50%;
  }

  .dot.active {
    background-color: white;
  }

  .trending-section {
    padding: 40px 30px;
  }

  .trending-section h2 {
    font-size: 28px;
    margin-bottom: 30px;
  }

  .trending-item {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
  }

  .trending-item img {
    width: 200px;
    height: 130px;
    object-fit: cover;
    border-radius: 8px;
  }

  .trending-item .text h3 {
    margin: 0;
    font-size: 20px;
  }

  .trending-item .text p {
    margin: 6px 0;
  }
</style>

<div class="kategori-headline">Kategori: {{ ucfirst($kategori) }}</div>

<div class="carousel">
  @if(count($berita) > 0)
    <img src="{{ asset('storage/' . $berita[0]->gambar) }}" alt="Berita Utama">
    <div class="carousel-caption">
      <h2>{{ $berita[0]->judul }}</h2>
      <p>{{ $berita[0]->created_at->format('d M Y') }}</p>
    </div>
  @else
    <img src="/images/news1.jpg" alt="Berita Utama">
    <div class="carousel-caption">
      <h2>Belum Ada Berita</h2>
    </div>
  @endif
  <button class="carousel-button left">&#9664;</button>
  <button class="carousel-button right">&#9654;</button>
</div>
<div class="dot-indicators">
  <span class="dot active"></span>
</div>

<div class="trending-section">
  <h2>Trending</h2>
  @forelse($berita as $item)
  <div class="trending-item">
    <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}">
    <div class="text">
      <h3><a href="{{ route('berita.detail', $item->id) }}">{{ $item->judul }}</a></h3>
      <p>{{ Str::limit(strip_tags($item->isi), 100, '...') }}</p>
      <p>{{ $item->created_at->format('d M Y') }}</p>
    </div>
  </div>
  @empty
    <p>Tidak ada berita dalam kategori ini.</p>
  @endforelse
</div>
@endsection

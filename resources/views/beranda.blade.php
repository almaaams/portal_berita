@extends('layouts.app')

@section('content')
<style>
  .hero-section {
    position: relative;
    height: 500px;
    background: url('/images/jakarta.jpg') center/cover no-repeat;
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding-left: 50px;
  }

  .hero-section h1 {
    font-size: 48px;
    font-weight: bold;
  }

  .hero-section p {
    max-width: 600px;
    font-size: 18px;
    margin: 15px 0;
  }

  .hero-section a {
    background-color: #0056d2;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    font-weight: bold;
    border-radius: 6px;
    display: inline-block;
    width: fit-content;
  }

  .kategori-section, .trending-section, .history-section {
    padding: 60px 40px;
    background-color: black;
    color: white;
  }

  .kategori-section h2,
  .trending-section h2,
  .history-section h2 {
    font-size: 32px;
    margin-bottom: 30px;
    text-align: center;
  }

  .kategori-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px;
  }

  .kategori-card {
    width: 160px;
    height: 100px;
    background-size: cover;
    background-position: center;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 18px;
    color: white;
    position: relative;
    overflow: hidden;
    text-shadow: 1px 1px 2px black;
    cursor: pointer;
  }

  .kategori-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.4);
    z-index: 1;
  }

  .kategori-card span {
    position: relative;
    z-index: 2;
  }

  .trending-item, .history-item {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
  }

  .trending-item img,
  .history-item img {
    width: 180px;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
  }

  .trending-item .text,
  .history-item .text {
    flex: 1;
  }

  .trending-item .text h3,
  .history-item .text h3 {
    margin: 0;
    font-size: 20px;
    font-weight: bold;
  }

  .history-section {
    background-color: #111;
  }

  .history-container {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
    justify-content: center;
  }

  .history-left {
    flex: 1;
    max-width: 350px;
  }

  .history-right {
    flex: 2;
    min-width: 300px;
  }

  .more-button {
    background-color: #0056d2;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    display: inline-block;
    margin-top: 10px;
  }
</style>

<div class="hero-section">
  <h1>WELCOME</h1>
  <p>Selamat datang di portal berita!</p>
</div>

<div class="kategori-section" id="kategori">
  <h2>Kategori</h2>
  <div class="kategori-grid">
    <a href="{{ route('kategori.show', 'politik') }}" style="text-decoration: none;">
      <div class="kategori-card" style="background-image: url('/images/kategori/politik.jpg');">
        <span>Politik</span>
      </div>
    </a>

    <a href="{{ route('kategori.show', 'bisnis') }}" style="text-decoration: none;">
      <div class="kategori-card" style="background-image: url('/images/kategori/bisnis.jpg');">
        <span>Bisnis</span>
      </div>
    </a>

    <a href="{{ route('kategori.show', 'teknologi') }}" style="text-decoration: none;">
      <div class="kategori-card" style="background-image: url('/images/kategori/teknologi.jpg');">
        <span>Teknologi</span>
      </div>
    </a>

    <a href="{{ route('kategori.show', 'kesehatan') }}" style="text-decoration: none;">
      <div class="kategori-card" style="background-image: url('/images/kategori/kesehatan.jpg');">
        <span>Kesehatan</span>
      </div>
    </a>

    <a href="{{ route('kategori.show', 'olahraga') }}" style="text-decoration: none;">
      <div class="kategori-card" style="background-image: url('/images/kategori/olahraga.jpg');">
        <span>Olahraga</span>
      </div>
    </a>
  </div>
</div>

<div class="trending-section" id="trending">
  <h2>Terbaru</h2>

  @forelse($berita_terkini->take(2) as $berita)
  <div class="trending-item">
    <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->judul }}" onerror="this.src='/images/placeholder.jpg'">
    <div class="text">
      <h3>
        <a href="{{ route('berita.detail', $berita->id) }}" style="color: white; text-decoration: none;">
          {{ $berita->judul }}
        </a>
      </h3>
      <p>{{ Str::limit(strip_tags($berita->isi), 100) }}</p>
      <p>{{ $berita->created_at->format('d M Y') }}</p>
    </div>
  </div>
  @empty
  <div class="trending-item">
    <img src="/images/placeholder.jpg" alt="No Image">
    <div class="text">
      <h3>
        <a href="#" style="color: white; text-decoration: none;">
          Belum ada berita tersedia
        </a>
      </h3>
      <p>Silakan kembali lagi nanti untuk membaca berita terbaru.</p>
      <p>{{ date('d M Y') }}</p>
    </div>
  </div>
  @endforelse
</div>

<div class="history-section" id="history">
  <h2>
    @auth
      Berdasarkan Riwayat Anda
    @else
      Populer
    @endauth
  </h2>
  <div class="history-container">
    <div class="history-left">
      @if($berita_history->count() > 0)
        @php $featuredBerita = $berita_history->first(); @endphp
        <a href="{{ route('berita.detail', $featuredBerita->id) }}">
          <img src="{{ asset('storage/' . $featuredBerita->gambar) }}" alt="{{ $featuredBerita->judul }}" style="width: 100%; border-radius: 10px;" onerror="this.src='/images/placeholder.jpg'">
        </a>
        <h3><a href="{{ route('berita.detail', $featuredBerita->id) }}" style="color:white; text-decoration:none;">{{ $featuredBerita->judul }}</a></h3>
        <p>{{ Str::limit(strip_tags($featuredBerita->isi), 120) }}</p>
        <p><small>{{ $featuredBerita->created_at->format('d M Y') }}</small></p>
      @else
        <img src="/images/placeholder.jpg" alt="No Image" style="width: 100%; border-radius: 10px;">
        <h3><a href="#" style="color:white; text-decoration:none;">Belum ada history</a></h3>
        <p>
          @auth
            Mulai membaca berita untuk melihat rekomendasi berdasarkan riwayat Anda.
          @else
            Silakan login untuk melihat rekomendasi berdasarkan riwayat bacaan Anda.
          @endauth
        </p>
      @endif
    </div>

    <div class="history-right">
      @if($berita_history->count() > 1)
        @foreach($berita_history->skip(1)->take(2) as $berita)
        <div class="history-item">
          <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->judul }}" onerror="this.src='/images/placeholder.jpg'">
          <div class="text">
            <h3><a href="{{ route('berita.detail', $berita->id) }}" style="color:white; text-decoration:none;">{{ Str::limit($berita->judul, 60) }}</a></h3>
            <p>{{ $berita->created_at->format('d M Y') }}</p>
            <p><small>{{ ucfirst($berita->kategori) }}</small></p>
          </div>
        </div>
        @endforeach
      @else
        <div class="history-item">
          <img src="/images/placeholder.jpg" alt="No Image">
          <div class="text">
            <h3><a href="#" style="color:white; text-decoration:none;">Belum ada berita lainnya</a></h3>
            <p>{{ date('d M Y') }}</p>
          </div>
        </div>
      @endif

      <a href="{{ route('kategori.show', 'semua') }}" class="more-button">More ></a>
    </div>
  </div>
</div>
@endsection

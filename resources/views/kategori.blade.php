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
    overflow: hidden;
    border-radius: 10px;
  }

  .carousel-inner {
    display: flex;
    transition: transform 0.5s ease-in-out;
  }

  .carousel-item {
    min-width: 100%;
    position: relative;
  }

  .carousel-item img {
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
    background-color: rgba(0,0,0,0.5);
    padding: 10px 15px;
    border-radius: 8px;
  }

  .carousel-caption h2 {
    font-size: 24px;
    margin-bottom: 5px;
  }

  .carousel-caption p {
    font-size: 14px;
    margin: 0;
  }

  .carousel-button {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 28px;
    color: white;
    background: rgba(0,0,0,0.6);
    border: none;
    padding: 5px 12px;
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
    cursor: pointer;
  }

  .dot.active {
    background-color: #333;
  }

  .terbaru-section {
    padding: 40px 30px;
  }

  .terbaru-section h2 {
    font-size: 28px;
    margin-bottom: 30px;
  }

  .terbaru-item {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
  }

  .terbaru-item img {
    width: 200px;
    height: 130px;
    object-fit: cover;
    border-radius: 8px;
  }

  .terbaru-item .text h3 {
    margin: 0;
    font-size: 20px;
  }

  .terbaru-item .text p {
    margin: 6px 0;
  }

  .terbaru-item .text h3 a {
    color: white;
    text-decoration: none;
    transition: color 0.3s ease;
  }

  .terbaru-item .text h3 a:hover {
    color: #009dffff; 
  }
</style>

<div class="kategori-headline">{{ ucfirst($kategori) }}</div>

<div class="carousel">
  <div class="carousel-inner" id="carousel-inner">
    @forelse($berita as $item)
    <div class="carousel-item">
      <a href="{{ route('berita.detail', $item->id) }}">
        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}">
        <div class="carousel-caption">
          <h2>{{ $item->judul }}</h2>
          <p>{{ $item->created_at->format('d M Y') }}</p>
        </div>
      </a>
    </div>
    @empty
    <div class="carousel-item">
      <img src="/images/news1.jpg" alt="Berita Utama">
      <div class="carousel-caption">
        <h2>Belum Ada Berita</h2>
      </div>
    </div>
    @endforelse
  </div>
  <button class="carousel-button left" onclick="prevSlide()">&#10094;</button>
  <button class="carousel-button right" onclick="nextSlide()">&#10095;</button>
</div>

<div class="dot-indicators" id="dot-indicators">
  @foreach($berita as $index => $item)
    <span class="dot {{ $index === 0 ? 'active' : '' }}" onclick="goToSlide({{ $index }})"></span>
  @endforeach
</div>

<div class="terbaru-section">
  <h2>Terbaru</h2>
  @forelse($berita as $item)
  <div class="terbaru-item">
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

<script>
  let currentSlide = 0;
  const slides = document.querySelectorAll('.carousel-item');
  const totalSlides = slides.length;
  const carouselInner = document.getElementById('carousel-inner');
  const dots = document.querySelectorAll('.dot');

  function updateSlide() {
    carouselInner.style.transform = 'translateX(-' + (currentSlide * 100) + '%)';
    dots.forEach(dot => dot.classList.remove('active'));
    if (dots[currentSlide]) {
      dots[currentSlide].classList.add('active');
    }
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    updateSlide();
  }

  function prevSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    updateSlide();
  }

  function goToSlide(index) {
    currentSlide = index;
    updateSlide();
  }
</script>
@endsection

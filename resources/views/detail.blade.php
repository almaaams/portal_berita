@extends('layouts.app')

@section('content')
<style>
  .detail-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 40px 20px;
    color: white;
  }

  .detail-title {
    font-size: 36px;
    font-weight: bold;
    margin-bottom: 10px;
  }

  .detail-meta {
    color: #ccc;
    font-size: 14px;
    margin-bottom: 20px;
  }

  .detail-image {
    width: 100%;
    border-radius: 8px;
    margin-bottom: 20px;
  }

  .detail-content {
    font-size: 16px;
    line-height: 1.6;
    white-space: pre-line;
    margin-bottom: 30px;
  }

  .detail-footer {
    display: flex;
    gap: 15px;
    font-size: 14px;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 20px;
  }

  .action-button {
    display: inline-flex;
    align-items: center;
    background: linear-gradient(145deg, #3a3a3a, #2a2a2a);
    color: #fff;
    padding: 8px 14px;
    border-radius: 30px;
    border: none;
    font-size: 14px;
    transition: all 0.3s ease;
    cursor: pointer;
  }

  .action-button i {
    margin-right: 6px;
  }

  .action-button:hover {
    background: linear-gradient(145deg, #4e4e4e, #1c1c1c);
    transform: translateY(-2px);
  }

  .comment-box {
    margin-top: 40px;
  }

  .comment-box textarea {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 10px;
    resize: vertical;
  }

  .comment-box button {
    background-color: #007bff;
    border: none;
    padding: 8px 16px;
    color: white;
    border-radius: 6px;
  }
</style>

<div class="detail-container">
  <div class="detail-title">{{ $berita['judul'] }}</div>
  <div class="detail-meta">{{ $berita['kategori'] }} &nbsp;&nbsp; {{ $berita['tanggal'] }}</div>
  <img src="{{ asset('storage/' . $berita->gambar) }}" alt="Gambar Berita" class="detail-image">
  <div class="detail-content">{{ $berita['isi'] }}</div>

  <div class="detail-footer">
    @auth
      <form action="{{ route('berita.like', $berita['id']) }}" method="POST">
        @csrf
        <button type="submit" class="action-button">
          <i class="fa-regular fa-heart"></i> Suka ({{ $jumlah_like }})
        </button>
      </form>
    @else
      <div class="action-button" style="background: #444; cursor: default;">
        <i class="fa-regular fa-heart"></i> Login untuk Like ({{ $jumlah_like }})
      </div>
    @endauth

    <div class="action-button" onclick="copyLink('{{ url()->current() }}')">
      <i class="fa-solid fa-share-nodes"></i> Bagikan
    </div>

    @auth
      <a href="#comment-section" class="action-button">
        <i class="fa-regular fa-comment"></i> Komentar
      </a>
    @else
      <div class="action-button" style="background: #444; cursor: default;">
        <i class="fa-regular fa-comment"></i> Login untuk Komentar
      </div>
    @endauth
  </div>

  <!-- Comment Box -->
  @auth
  <div class="comment-box" id="comment-section">
    <form action="{{ route('berita.comment', $berita['id']) }}" method="POST">
      @csrf
      <textarea name="komentar" rows="4" placeholder="Tulis komentar..."></textarea>
      <button type="submit">Kirim Komentar</button>
    </form>
  </div>
  @endauth

  @if($komentar->count())
    <div style="margin-top: 40px;">
      <h4 style="color: white; margin-bottom: 15px;">Komentar</h4>
      @foreach($komentar as $komen)
        <div style="margin-bottom: 20px; background-color: #222; padding: 10px 15px; border-radius: 8px;">
          <strong>{{ $komen->user->username }}</strong> 
          <small style="color: #aaa;">{{ $komen->created_at->diffForHumans() }}</small>
          <p style="margin-top: 5px;">{{ $komen->isi }}</p>
        </div>
      @endforeach
    </div>
  @endif
</div>

<script>
  function copyLink(link) {
    navigator.clipboard.writeText(link).then(() => {
      alert("Link berhasil disalin!");
    });
  }
</script>

<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endsection

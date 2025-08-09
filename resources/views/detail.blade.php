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
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.5);
  }

  .detail-content {
    font-size: 16px;
    line-height: 1.7;
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

  /* Base button style */
  .action-button {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 12px 20px;
    border-radius: 50px;
    background-size: 400% 400%;
    color: white;
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    backdrop-filter: blur(8px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    transition: transform 0.2s ease, box-shadow 0.3s ease;
    animation: gradientMove 6s ease infinite;
  }

  .action-button i {
    font-size: 16px;
    transition: transform 0.3s ease;
  }

  .action-button:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 6px 25px rgba(0,0,0,0.4);
  }

  .action-button:hover i {
    transform: scale(1.3);
  }

  @keyframes gradientMove {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  /* Warna khusus transparan */
  .btn-like {
    background: linear-gradient(270deg, rgba(255,0,80,0.7), rgba(255,50,120,0.7), rgba(255,0,80,0.7));
  }

  .btn-comment {
    background: linear-gradient(270deg, rgba(0,150,255,0.7), rgba(0,200,255,0.7), rgba(0,150,255,0.7));
  }

  .btn-share {
    background: linear-gradient(270deg, rgba(170,0,255,0.7), rgba(200,80,255,0.7), rgba(170,0,255,0.7));
  }

  /* Kotak komentar */
  .comment-box {
    margin-top: 40px;
    background-color: rgba(31, 31, 31, 0.8);
    padding: 20px;
    border-radius: 12px;
    backdrop-filter: blur(6px);
    display: none; /* awalnya disembunyikan */
  }

  .comment-box textarea {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 10px;
    resize: vertical;
    border: none;
    background-color: rgba(44,44,44,0.9);
    color: white;
  }

  .comment-box button {
    background: linear-gradient(270deg, #00ff87, #60efff, #00ff87);
    background-size: 300% 300%;
    border: none;
    padding: 10px 18px;
    color: white;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    animation: gradientMove 5s ease infinite;
  }

  .comment-box button:hover {
    transform: translateY(-2px) scale(1.03);
  }

  /* Komentar */
  .comment-item {
    margin-bottom: 20px;
    background-color: rgba(34,34,34,0.85);
    padding: 12px 15px;
    border-radius: 8px;
    backdrop-filter: blur(6px);
  }

  .comment-item strong {
    color: #4da3ff;
  }

  .comment-item small {
    color: #aaa;
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
        @if($sudah_like)
          <button type="submit" class="action-button btn-like">
            <i class="fa-solid fa-heart"></i> Batal Suka ({{ $jumlah_like }})
          </button>
        @else
          <button type="submit" class="action-button btn-like">
            <i class="fa-regular fa-heart"></i> Suka ({{ $jumlah_like }})
          </button>
        @endif
      </form>
    @else
      <a href="{{ route('login') }}" class="action-button btn-like">
        <i class="fa-regular fa-heart"></i> Suka ({{ $jumlah_like }})
      </a>
    @endauth

    <div class="action-button btn-share" onclick="copyLink('{{ url()->current() }}')">
      <i class="fa-solid fa-share-nodes"></i> Bagikan
    </div>

    @auth
      <div class="action-button btn-comment" onclick="toggleCommentBox()">
        <i class="fa-regular fa-comment"></i> Komentar
      </div>
    @else
      <a href="{{ route('login') }}" class="action-button btn-comment">
        <i class="fa-regular fa-comment"></i> Komentar
      </a>
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
  @else
  <div class="comment-box" id="comment-section">
    <p style="color: #ccc;">Silakan <a href="{{ route('login') }}" style="color: #4da3ff;">login</a> untuk menulis komentar.</p>
  </div>
  @endauth

  <!-- Daftar Komentar -->
  @if($komentar->count())
    <div style="margin-top: 40px;">
      <h4 style="color: white; margin-bottom: 15px;">Komentar</h4>
      @foreach($komentar as $komen)
        <div class="comment-item">
          <strong>{{ $komen->user->username }}</strong> 
          <small>{{ $komen->created_at->diffForHumans() }}</small>
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

  function toggleCommentBox() {
    const commentBox = document.getElementById('comment-section');
    commentBox.style.display = (commentBox.style.display === 'none' || commentBox.style.display === '') ? 'block' : 'none';
  }
</script>

<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endsection

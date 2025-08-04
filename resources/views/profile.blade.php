@extends('layouts.app')

@section('content')
<style>
  .profile-container {
    max-width: 800px;
    margin: auto;
    padding: 20px;
    background-color: rgba(30, 30, 30, 0.85);
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    color: white;
  }

  .form-group {
    margin-bottom: 15px;
  }

  .form-label {
    font-weight: bold;
  }

  .form-input, .form-select, textarea {
    width: 100%;
    padding: 8px;
    border-radius: 8px;
    border: 1px solid #ccc;
    margin-top: 5px;
  }

  .save-btn {
    background-color: #3490dc;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    margin-top: 10px;
  }

  .save-btn:hover {
    background-color: #2779bd;
  }

  .edit-btn {
    background-color: #f39c12;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 8px;
    cursor: pointer;
    margin-bottom: 15px;
  }

  .edit-btn:hover {
    background-color: #e67e22;
  }

  .section-divider {
    margin-top: 40px;
    margin-bottom: 20px;
    border-top: 2px solid #ddd;
  }

  .berita-item {
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
    background: white;
    color: black;
  }

  .berita-title {
    font-size: 18px;
    font-weight: bold;
  }

  .berita-kategori {
    font-size: 14px;
    color: gray;
    margin-bottom: 5px;
  }

  .gambar-berita {
    max-width: 100%;
    max-height: 200px;
    object-fit: cover;
    margin-top: 10px;
  }
</style>

<div class="profile-container">
  <h2>Profil Pengguna</h2>

  @if(session('success'))
    <div style="color: green; margin-bottom: 10px;">
      {{ session('success') }}
    </div>
  @endif

  {{-- Tombol Edit Profil --}}
  <button type="button" class="edit-btn" onclick="enableEdit()">Edit Profil</button>

  {{-- Form Update Profile --}}
  <form method="POST" action="{{ route('profile.update') }}">
    @csrf

    <div class="form-group">
      <label class="form-label">Username</label>
      <input type="text" name="username" class="form-input" value="{{ $user->username }}" required disabled>
    </div>

    <div class="form-group">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-input" value="{{ $user->email }}" required disabled>
    </div>

    <div class="form-group">
      <label class="form-label">Nama Depan</label>
      <input type="text" name="first_name" class="form-input" value="{{ $user->first_name }}" required disabled>
    </div>

    <div class="form-group">
      <label class="form-label">Nama Belakang</label>
      <input type="text" name="last_name" class="form-input" value="{{ $user->last_name }}" required disabled>
    </div>

    <div class="form-group">
      <label class="form-label">Password (kosongkan jika tidak ingin diubah)</label>
      <input type="password" name="password" class="form-input" disabled>
    </div>

    <input type="hidden" name="role" value="{{ $user->role }}">

    <button type="submit" class="save-btn" disabled id="submitBtn">Simpan Perubahan</button>
  </form>

  {{-- Form Upload Berita --}}
  @if($user->role === 'Admin')
    <div class="section-divider"></div>

    <h3>Upload Berita Baru</h3>

    <form action="{{ route('berita.upload') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="form-group">
        <label class="form-label">Judul Berita</label>
        <input type="text" name="judul" class="form-input" required>
      </div>

      <div class="form-group">
        <label class="form-label">Gambar (opsional)</label>
        <input type="file" name="gambar" class="form-input">
      </div>

      <div class="form-group">
        <label class="form-label">Isi Cerita</label>
        <textarea name="isi" rows="5" class="form-input" required></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Kategori</label>
        <select name="kategori" class="form-select" required>
          <option value="">Pilih Kategori</option>
          <option value="politik">Politik</option>
          <option value="bisnis">Bisnis</option>
          <option value="teknologi">Teknologi</option>
          <option value="kesehatan">Kesehatan</option>
          <option value="olahraga">Olahraga</option>
        </select>
      </div>

      <button type="submit" class="save-btn">Upload Berita</button>
    </form>

    {{-- List Berita --}}
    @if(isset($berita) && count($berita) > 0)
      <div class="section-divider"></div>
      <h3>Berita yang Telah Diunggah</h3>

      @foreach($berita->groupBy('kategori') as $kategori => $group)
        <h4 style="margin-top: 25px;">Kategori: {{ ucfirst($kategori) }}</h4>
        @foreach($group as $item)
          <div class="berita-item">
            <div class="berita-title">{{ $item->judul }}</div>
            <div class="berita-kategori">{{ $item->created_at->format('d M Y') }}</div>
            <div>{{ Str::limit(strip_tags($item->isi), 100, '...') }}</div>
            @if($item->gambar)
              <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar Berita" class="gambar-berita">
            @endif
            <a href="{{ route('berita.detail', $item->id) }}" style="color: #3490dc; font-weight: bold; text-decoration: none;">
              📖 Baca Selengkapnya
            </a>
            <form action="{{ route('berita.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(event, '{{ $item->judul }}')">
              @csrf
              @method('DELETE')
              <button type="submit" class="edit-btn" style="background-color: #e74c3c;">🗑 Hapus</button>
            </form>
          </div>
        @endforeach
      @endforeach
    @else
      <p style="margin-top: 20px;">Belum ada berita yang Anda upload.</p>
    @endif
  @endif
</div>

<script>
  function enableEdit() {
    const inputs = document.querySelectorAll('form input, form textarea');
    inputs.forEach(input => input.disabled = false);

    document.getElementById('submitBtn').disabled = false;
  }
  
  function confirmDelete(event, judul) {
    event.preventDefault();

    if (confirm(`Apakah Anda yakin ingin menghapus berita: "${judul}"? Tindakan ini tidak dapat dibatalkan.`)) {
      event.target.submit();
    }

    return false;
  }
</script>
@endsection
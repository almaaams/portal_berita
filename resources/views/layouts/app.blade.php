<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Portal Berita</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: black;
      color: white;
    }

    .navbar {
      background-color: #31393C;
      color: white;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar-left {
      display: flex;
      align-items: center;
      gap: 30px;
    }

    .navbar-left a,
    .navbar-right a {
      color: white;
      text-decoration: none;
      margin-right: 20px;
    }

    .navbar-right {
      display: flex;
      align-items: center;
      gap: 25px;
      margin-top: 0px;
    }

    .search-input {
      padding: 8px 16px;
      border-radius: 20px;
      border: none;
      font-size: 14px;
    }

    .navbar-right .submit-btn {
      padding: 8px 18px;
      background-color: #4285f4;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      margin-top: 0px;
    }

    .main-content {
      padding: 20px;
    }
  </style>
</head>
<body>
  <div class="navbar">
    <div class="navbar-left">
      <strong><span style="color: white">News</span></strong>
      <a href="/">Beranda</a>
      <a href="#kategori">Kategori</a>
      <a href="#trending">Terbaru</a>
      <a href="#history">History</a>
    </div>
    <div class="navbar-right">
      <!-- <input type="text" class="search-input" placeholder="Search"> -->
      <form action="{{ route('search') }}" method="GET" style="display: flex; align-items: center;">
        <input type="text" name="q" class="search-input" placeholder="Search..." value="{{ request('q') }}">
      </form>


      @auth
        <a href="/profile" style="color:white; text-decoration:none;">Profile</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
          @csrf
          <button type="submit" class="submit-btn">Logout</button>
        </form>
      @else
        <a href="/login"><button class="submit-btn">Login</button></a>
      @endauth
    </div>
  </div>

  <div class="main-content">
    @yield('content')
  </div>
</body>
</html>
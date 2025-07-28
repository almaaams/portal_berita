<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: black;
      color: black;
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

    .navbar-left a, .navbar-right a {
      color: white;
      text-decoration: none;
      margin-right: 20px;
    }

    .navbar-right {
      display: flex;
      align-items: center;
      gap: 25px;
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
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: calc(100vh - 70px);
    }

    .form-box {
      background-color: #f2f2f2;
      padding: 30px;
      border-radius: 10px;
      width: 300px;
      text-align: center;
    }

    .form-input,
    input[type="email"],
    input[type="password"] {
      width: 90%;
      padding: 10px;
      margin: 10px 0;
      background-color: #e0e0e0;
      border: none;
      border-radius: 5px;
    }

    .form-footer {
      margin-top: 10px;
      font-size: 14px;
    }

    .form-footer a {
      color: blue;
      text-decoration: none;
    }

    .submit-btn {
      margin-top: 10px;
      padding: 10px 20px;
      background-color: #4285f4;
      color: white;
      border: none;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <div class="navbar">
    <div class="navbar-left">
      <strong>Sport<span style="color: white">News</span></strong>
      <a href="/">Beranda</a>
      <a href="#">Kategori</a>
      <a href="#">Trending</a>
      <a href="#">History</a>
    </div>
    <div class="navbar-right">
      <input type="text" class="search-input" placeholder="Search">
      <a href="/login"><button class="submit-btn">Login</button></a>
    </div>
  </div>

  <div class="container">
    <div class="form-box">
      <h2>Login</h2>

      @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
      @endif

      @if($errors->any())
        <div style="color:red; font-size:14px;">
          <ul>
            @foreach($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}"><br>
        <input type="password" name="password" placeholder="Password" required><br>

        <button type="submit" class="submit-btn">Login</button>
      </form>

      <div class="form-footer">
        Belum punya akun? <a href="/signup">Sign Up</a>
      </div>
    </div>
  </div>
</body>
</html>
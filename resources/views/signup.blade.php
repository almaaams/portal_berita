<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Sign Up</title>
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
      <h2>Sign Up</h2>

      @if($errors->any())
        <div style="color:red; font-size:14px;">
          <ul>
            @foreach($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('signup.submit') }}">
        @csrf

        <input type="text" name="username" placeholder="Username" required class="form-input" value="{{ old('username') }}"><br>
        <input type="text" name="first_name" placeholder="First Name" required class="form-input" value="{{ old('first_name') }}"><br>
        <input type="text" name="last_name" placeholder="Last Name" required class="form-input" value="{{ old('last_name') }}"><br>
        <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}"><br>
        <input type="password" name="password" placeholder="Password" required><br>

        <div class="role-options" style="margin-top: 10px; text-align: left;">
          <label><input type="radio" name="role" value="visitor" required> Visitor</label><br>
          <label><input type="radio" name="role" value="admin" required> Admin</label>
        </div>

        <div class="form-footer">
          Already have an account? <a href="/login">Login</a>
        </div>

        <button type="submit" class="submit-btn">Sign Up</button>
      </form>
    </div>
  </div>
</body>
</html>
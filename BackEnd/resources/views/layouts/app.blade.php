<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'TiketBus')</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@stack('head')
</head>
<body>

<header class="topbar">
  <div class="topbar-inner">
    <a href="{{ route('search.index') }}" class="brand"><span class="brand-mark">🚌</span>Tiket<span>Bus</span></a>
    <input type="checkbox" id="navToggle" class="nav-toggle-cb"><label for="navToggle" class="nav-toggle-label">☰</label>
    <nav class="nav">
      <a href="{{ route('search.index') }}" class="{{ request()->routeIs('search.index', 'results', 'schedules.*') ? 'active' : '' }}">Cari Tiket</a>
      <a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.*', 'bookings.*', 'tickets.*') ? 'active' : '' }}">Cek Pesanan</a>
      <a href="#">Bantuan</a>
      <div class="nav-divider"></div>
      @auth
        <a href="{{ route('profile.show') }}" class="btn-account outline">{{ \Illuminate\Support\Str::of(auth()->user()->name)->before(' ') }}</a>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
          @csrf
          <button type="submit" class="btn-account">Keluar</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="btn-account outline">Masuk/Daftar</a>
      @endauth
    </nav>
  </div>
</header>

@if (session('status'))
  <div class="container" style="margin-top:18px;">
    <div class="flash flash-success">{{ session('status') }}</div>
  </div>
@endif

@if (session('error'))
  <div class="container" style="margin-top:18px;">
    <div class="flash flash-error">{{ session('error') }}</div>
  </div>
@endif

@if ($errors->any())
  <div class="container" style="margin-top:18px;">
    <div class="flash flash-error">
      <strong>Mohon periksa kembali isian Anda:</strong>
      <ul style="margin:6px 0 0 18px;">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  </div>
@endif

<main>@yield('content')</main>

<footer class="site-footer">
  <div class="container">
    <div class="footer-brand">🚌 TiketBus</div>
    <div class="footer-links">
      <a href="#">Syarat &amp; Ketentuan</a>
      <a href="#">Kebijakan Privasi</a>
      <a href="#">Bantuan</a>
      <a href="#">Hubungi Kami</a>
    </div>
  </div>
</footer>

</body>
</html>

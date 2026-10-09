@extends("layouts.app")
@section("content")
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk — TiketBus</title>
<link rel="stylesheet" href="style.css">
<style>
  .login-wrap{
    min-height:calc(100vh - 72px);
    display:flex;align-items:center;justify-content:center;
    padding:48px 24px;
    background:
      radial-gradient(circle at 15% 20%, var(--green-50) 0%, transparent 45%),
      var(--cream);
  }
  .login-card{
    display:flex;max-width:920px;width:100%;
    border-radius:20px;overflow:hidden;box-shadow:var(--shadow);
  }
  .login-photo{
    flex:1;position:relative;min-height:520px;
    background:url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=800&auto=format&fit=crop') center/cover;
  }
  .login-photo::after{
    content:"";position:absolute;inset:0;
    background:linear-gradient(180deg, rgba(15,61,46,0) 40%, rgba(15,61,46,.55) 100%);
  }
  .login-photo-caption{
    position:absolute;left:24px;bottom:22px;color:#fff;z-index:1;
  }
  .login-photo-caption strong{display:block;font-size:17px;}
  .login-photo-caption span{font-size:13px;opacity:.85;}
  .login-form{
    flex:1;background:var(--green-900);color:#fff;
    padding:44px 40px;display:flex;flex-direction:column;
  }
  .login-form .brand-mark{background:#fff;color:var(--green-700);}
  .login-form .brand-top{display:flex;align-items:center;gap:9px;font-weight:800;margin-bottom:36px;}
  .login-form h1{font-size:26px;margin:0 0 8px;letter-spacing:-0.01em;}
  .login-form p.sub{margin:0 0 28px;color:#B9D3C4;font-size:14px;line-height:1.55;}
  .login-form .field label{color:#E7F2EC;}
  .login-form .field input{background:rgba(255,255,255,.06);border-color:rgba(255,255,255,.18);color:#fff;}
  .login-form .field input::placeholder{color:#7FA692;}
  .login-form .field input:focus{border-color:var(--green-500);background:rgba(255,255,255,.1);}
  .row-between{display:flex;justify-content:space-between;align-items:center;font-size:13.5px;margin:2px 0 22px;color:#CFE4D8;}
  .row-between a{color:#fff;font-weight:700;}
  .checkbox-row{display:flex;align-items:center;gap:8px;}
  .divider-or{text-align:center;color:#7FA692;font-size:12.5px;margin:14px 0;position:relative;}
  .login-form .btn-google{background:#fff;color:var(--ink);}
  .signup-line{text-align:center;margin-top:22px;font-size:13.5px;color:#CFE4D8;}
  .signup-line a{color:#fff;font-weight:800;}
  @media (max-width:640px){
    .login-wrap{padding:0;min-height:auto;}
    .login-card{flex-direction:column;border-radius:0;}
    .login-photo{min-height:200px;}
    .login-form{padding:30px 24px 40px;}
  }
</style>
</head>
<body>

<header class="topbar">
  <div class="topbar-inner">
    <a href="search.html" class="brand"><span class="brand-mark">🚌</span>Tiket<span>Bus</span></a>
    <input type="checkbox" id="navToggle" class="nav-toggle-cb"><label for="navToggle" class="nav-toggle-label">☰</label>
    <nav class="nav">
      <a href="search.html">Cari Tiket</a>
      <a href="profile.html">Cek Pesanan</a>
      <a href="#">Bantuan</a>
      <div class="nav-divider"></div>
      <a href="#" style="color:var(--green-700);font-weight:800;">Masuk/Daftar</a>
    </nav>
  </div>
</header>

<div class="login-wrap">
  <div class="login-card">
    <div class="login-photo">
      <div class="login-photo-caption">
        <strong>Trans-Jawa, malam ini.</strong>
        <span>Ribuan rute, satu tiket.</span>
      </div>
    </div>
    <div class="login-form">
      <div class="brand-top"><span class="brand-mark">🚌</span>TiketBus</div>
      <h1>Selamat datang kembali</h1>
      <p class="sub">Masukkan email dan kata sandi untuk mengakses akun dan riwayat perjalananmu.</p>

      <form onsubmit="event.preventDefault(); window.location.href='search.html';">
        <div class="field">
          <label for="email">Email</label>
          <input id="email" type="email" placeholder="nama@email.com" required>
        </div>
        <div class="field" style="margin-bottom:10px;">
          <label for="pass">Kata sandi</label>
          <input id="pass" type="password" placeholder="••••••••" required>
        </div>
        <div class="row-between">
          <label class="checkbox-row"><input type="checkbox" style="accent-color:var(--green-500);">Ingat saya</label>
          <a href="#">Lupa kata sandi?</a>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Masuk</button>
        <div class="divider-or">atau</div>
        <button type="button" class="btn btn-google btn-block" onclick="window.location.href='search.html'">Masuk dengan Google</button>
      </form>

      <div class="signup-line">Belum punya akun? <a href="#">Daftar sekarang</a></div>
    </div>
  </div>
</div>

<footer class="site-footer">
  <div class="container">
    <div class="footer-brand">🚌 TiketBus</div>
    <div class="footer-links">
      <a href="#">Syarat &amp; Ketentuan</a>
      <a href="#">Privasi</a>
      <a href="#">Bantuan</a>
    </div>
  </div>
</footer>

</body>
</html>

@endsection

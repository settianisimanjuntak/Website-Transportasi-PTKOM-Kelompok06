@extends("layouts.app")
@section("content")
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cari Tiket Bus — TiketBus</title>
<link rel="stylesheet" href="style.css">
<style>
  .hero{
    background:linear-gradient(180deg, var(--green-900) 0%, var(--green-700) 100%);
    color:#fff;padding:56px 0 130px;position:relative;overflow:hidden;
  }
  .hero::after{
    content:"";position:absolute;right:-60px;top:-60px;width:340px;height:340px;
    background:radial-gradient(circle, rgba(255,255,255,.08) 0%, transparent 70%);
  }
  .hero h1{font-size:34px;margin:0 0 10px;letter-spacing:-0.01em;text-align:center;}
  .hero p{text-align:center;color:#CFE4D8;margin:0;font-size:15px;}
  .search-card{
    max-width:960px;margin:-72px auto 0;background:#fff;border-radius:16px;
    box-shadow:var(--shadow);padding:26px 28px;position:relative;z-index:5;
    display:flex;gap:18px;align-items:flex-end;flex-wrap:wrap;
  }
  .search-card .field{flex:1;min-width:180px;margin-bottom:0;}
  .search-card .btn{white-space:nowrap;padding:13px 26px;}
  .results-section{max-width:960px;margin:48px auto 0;padding:0 32px 60px;}
  .results-head{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:18px;}
  .results-head h2{font-size:18px;margin:0 0 4px;font-weight:800;}
  .results-head .muted{font-size:13px;}
  .results-list{display:flex;flex-direction:column;gap:14px;}
  .bus-row{padding:20px 22px;display:flex;justify-content:space-between;align-items:center;gap:18px;flex-wrap:wrap;transition:box-shadow .15s;}
  .bus-row:hover{box-shadow:var(--shadow);}
  .bus-main{display:flex;align-items:center;gap:30px;flex-wrap:wrap;}
  .bus-id .name{font-weight:800;font-size:15.5px;margin-bottom:6px;}
  .bus-id .sub{font-size:12px;color:var(--ink-soft);}
  .bus-time{display:flex;align-items:center;gap:12px;}
  .bus-time .t{text-align:center;}
  .bus-time .t .clock{font-weight:800;font-size:17px;}
  .bus-time .t .place{font-size:11.5px;color:var(--ink-soft);margin-top:2px;}
  .bus-time .mid{text-align:center;color:var(--ink-soft);font-size:11px;}
  .bus-time .mid .line{width:60px;height:1px;background:var(--line);margin:4px 0;}
  .bus-seats{font-size:12.5px;font-weight:700;}
  .bus-seats.low{color:var(--red);}
  .bus-seats.ok{color:var(--green-700);}
  .bus-price{text-align:right;}
  .bus-price .lbl{font-size:11px;color:var(--ink-soft);}
  @media (max-width:640px){
    .hero{padding:32px 0 100px;}
    .hero h1{font-size:24px;padding:0 20px;}
    .search-card{margin:-56px 16px 0;padding:18px;flex-direction:column;align-items:stretch;}
    .search-card .field{min-width:0;}
    .search-card .btn{width:100%;}
    .results-section{margin-top:36px;padding:0 18px 40px;}
    .bus-row{flex-direction:column;align-items:stretch;padding:16px;}
    .bus-row > div:last-child{justify-content:space-between;display:flex;align-items:center;}
  }
</style>
</head>
<body>

<header class="topbar">
  <div class="topbar-inner">
    <a href="search.html" class="brand"><span class="brand-mark">🚌</span>Tiket<span>Bus</span></a>
    <input type="checkbox" id="navToggle" class="nav-toggle-cb"><label for="navToggle" class="nav-toggle-label">☰</label>
    <nav class="nav">
      <a href="search.html" class="active">Cari Tiket</a>
      <a href="profile.html">Cek Pesanan</a>
      <a href="#">Bantuan</a>
      <div class="nav-divider"></div>
      <a href="index.html" class="btn-account outline">Masuk/Daftar</a>
    </nav>
  </div>
</header>

<section class="hero">
  <h1>Pesan Tiket Bus Antarkota</h1>
  <p>Pencarian rute cepat, jadwal resmi langsung dari operator bus ternama.</p>
</section>

<form class="search-card" onsubmit="event.preventDefault(); window.location.href='results.html';">
  <div class="field">
    <label>Asal</label>
    <select>
      <option>Jakarta (Semua Terminal)</option>
      <option>Bandung (Semua Terminal)</option>
      <option>Surabaya (Semua Terminal)</option>
    </select>
  </div>
  <div class="field">
    <label>Tujuan</label>
    <select>
      <option>Yogyakarta (Semua Terminal)</option>
      <option>Solo (Semua Terminal)</option>
      <option>Surabaya (Semua Terminal)</option>
    </select>
  </div>
  <div class="field">
    <label>Tanggal</label>
    <input type="date" value="2026-06-24">
  </div>
  <button type="submit" class="btn btn-primary">Cari Bus</button>
</form>

<section class="results-section">
  <div class="results-head">
    <div>
      <h2>Jakarta → Yogyakarta</h2>
      <div class="muted">Kamis, 24 Juni 2026</div>
    </div>
    <span class="tag tag-green">3 JADWAL TERSEDIA</span>
  </div>
  <div class="results-list">

    <a href="detail.html" class="card bus-row">
      <div class="bus-main">
        <div class="bus-id">
          <div class="name">PO Harapan Jaya <span class="tag tag-green">Executive</span></div>
          <div class="sub">Executive 2-2 · AC, Toilet</div>
        </div>
        <div class="bus-time">
          <div class="t"><div class="clock">16:30</div><div class="place">Pulo Gebang</div></div>
          <div class="mid">8j 15m<div class="line"></div></div>
          <div class="t"><div class="clock">00:45</div><div class="place">Giwangan</div></div>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:24px;">
        <div class="bus-seats ok">Sisa 6 kursi</div>
        <div class="bus-price"><div class="lbl">per kursi</div><div class="price">Rp 230.000</div></div>
      </div>
    </a>

    <a href="detail.html" class="card bus-row">
      <div class="bus-main">
        <div class="bus-id">
          <div class="name">Rosalia Indah <span class="tag tag-amber">Sleeper</span></div>
          <div class="sub">Sleeper Class · Flat Bed, AVOD</div>
        </div>
        <div class="bus-time">
          <div class="t"><div class="clock">18:00</div><div class="place">Pulo Gebang</div></div>
          <div class="mid">7j 45m<div class="line"></div></div>
          <div class="t"><div class="clock">01:45</div><div class="place">Giwangan</div></div>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:24px;">
        <div class="bus-seats low">Sisa 2 kursi</div>
        <div class="bus-price"><div class="lbl">per kursi</div><div class="price">Rp 420.000</div></div>
      </div>
    </a>

    <a href="detail.html" class="card bus-row">
      <div class="bus-main">
        <div class="bus-id">
          <div class="name">PO Sinar Jaya <span class="tag tag-amber">VIP</span></div>
          <div class="sub">VIP 2-2 · AC, Port USB</div>
        </div>
        <div class="bus-time">
          <div class="t"><div class="clock">19:30</div><div class="place">Pulo Gebang</div></div>
          <div class="mid">8j 20m<div class="line"></div></div>
          <div class="t"><div class="clock">04:00</div><div class="place">Giwangan</div></div>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:24px;">
        <div class="bus-seats ok">Sisa 12 kursi</div>
        <div class="bus-price"><div class="lbl">per kursi</div><div class="price">Rp 195.000</div></div>
      </div>
    </a>

  </div>
</section>

<footer class="site-footer" style="margin-top:56px;">
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

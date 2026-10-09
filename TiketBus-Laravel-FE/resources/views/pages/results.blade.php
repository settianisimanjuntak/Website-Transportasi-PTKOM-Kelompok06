@extends("layouts.app")
@section("content")
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hasil Pencarian — TiketBus</title>
<link rel="stylesheet" href="style.css">
<style>
  .route-bar{
    background:#fff;border-bottom:1px solid var(--line);padding:20px 0;
  }
  .route-bar-inner{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;}
  .route-bar h2{font-size:19px;margin:0 0 4px;font-weight:800;}
  .route-bar .muted{font-size:13.5px;}
  .route-bar a{font-weight:800;color:var(--green-700);font-size:14px;}
  .filters{padding:20px 0 8px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;}
  .chip-row{display:flex;gap:10px;flex-wrap:wrap;}
  .chip{
    border:1.5px solid var(--line);background:#fff;padding:9px 16px;border-radius:999px;
    font-size:13.5px;font-weight:700;color:var(--ink-soft);
  }
  .chip.active{background:var(--green-600);border-color:var(--green-600);color:#fff;}
  .sort{font-size:13.5px;color:var(--ink-soft);display:flex;align-items:center;gap:6px;}
  .sort select{border:none;font-weight:800;color:var(--ink);background:transparent;}
  .results-list{display:flex;flex-direction:column;gap:14px;padding-bottom:60px;}
  .bus-row{padding:22px 24px;display:flex;justify-content:space-between;align-items:center;gap:20px;flex-wrap:wrap;transition:box-shadow .15s;}
  .bus-row:hover{box-shadow:var(--shadow);}
  .bus-main{display:flex;align-items:center;gap:36px;flex-wrap:wrap;}
  .bus-id .name{font-weight:800;font-size:16px;margin-bottom:6px;}
  .bus-id .sub{font-size:12.5px;color:var(--ink-soft);}
  .bus-time{display:flex;align-items:center;gap:14px;}
  .bus-time .t{text-align:center;}
  .bus-time .t .clock{font-weight:800;font-size:18px;}
  .bus-time .t .place{font-size:12px;color:var(--ink-soft);margin-top:2px;}
  .bus-time .mid{text-align:center;color:var(--ink-soft);font-size:11.5px;}
  .bus-time .mid .line{width:70px;height:1px;background:var(--line);margin:4px 0;position:relative;}
  .bus-seats{font-size:13px;font-weight:700;}
  .bus-seats.low{color:var(--red);}
  .bus-seats.ok{color:var(--green-700);}
  .bus-price{text-align:right;}
  .bus-price .lbl{font-size:11.5px;color:var(--ink-soft);}
  @media (max-width:640px){
    .route-bar-inner{flex-direction:column;align-items:flex-start;}
    .filters{flex-direction:column;align-items:flex-start;}
    .chip-row{overflow-x:auto;flex-wrap:nowrap;width:100%;padding-bottom:4px;}
    .chip{white-space:nowrap;}
    .bus-row{flex-direction:column;align-items:stretch;padding:18px;}
    .bus-main{gap:16px;}
    .bus-time{gap:10px;}
    .bus-row > div:last-child{justify-content:space-between;}
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

<div class="route-bar">
  <div class="container route-bar-inner">
    <div>
      <h2>Jakarta → Yogyakarta</h2>
      <div class="muted">Rabu, 24 Juni 2026 · 1 Penumpang</div>
    </div>
    <a href="search.html">Ganti pencarian ✎</a>
  </div>
</div>

<div class="container">
  <div class="filters">
    <div class="chip-row">
      <button class="chip active">Semua Bus</button>
      <button class="chip">Pagi (06:00–12:00)</button>
      <button class="chip">Malam (18:00–24:00)</button>
      <button class="chip">Executive &amp; Sleeper</button>
    </div>
    <div class="sort">Urutkan:
      <select>
        <option>Termurah</option>
        <option>Tercepat</option>
        <option>Keberangkatan terawal</option>
      </select>
    </div>
  </div>

  <div class="results-list">

    <a href="detail.html" class="card bus-row">
      <div class="bus-main">
        <div class="bus-id">
          <div class="name">PO Rosalia Indah <span class="tag tag-green">Executive</span></div>
          <div class="sub">Executive 2-2 · AC, Toilet</div>
        </div>
        <div class="bus-time">
          <div class="t"><div class="clock">07:30</div><div class="place">Pulo Gebang</div></div>
          <div class="mid">9j 30m<div class="line"></div>Langsung</div>
          <div class="t"><div class="clock">17:00</div><div class="place">Giwangan</div></div>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:28px;">
        <div class="bus-seats ok">4 kursi tersisa</div>
        <div class="bus-price">
          <div class="lbl">per kursi</div>
          <div class="price">Rp 230.000</div>
        </div>
      </div>
    </a>

    <a href="detail.html" class="card bus-row">
      <div class="bus-main">
        <div class="bus-id">
          <div class="name">PO Sinar Jaya <span class="tag tag-amber">VIP AC</span></div>
          <div class="sub">VIP 2-2 · AC, Port USB</div>
        </div>
        <div class="bus-time">
          <div class="t"><div class="clock">08:30</div><div class="place">Kp. Rambutan</div></div>
          <div class="mid">9j 45m<div class="line"></div>Tol Trans-Jawa</div>
          <div class="t"><div class="clock">18:15</div><div class="place">Giwangan</div></div>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:28px;">
        <div class="bus-seats low">2 kursi tersisa</div>
        <div class="bus-price">
          <div class="lbl">per kursi</div>
          <div class="price">Rp 195.000</div>
        </div>
      </div>
    </a>

    <a href="detail.html" class="card bus-row">
      <div class="bus-main">
        <div class="bus-id">
          <div class="name">PO Harapan Jaya <span class="tag tag-green">Super Luxury</span></div>
          <div class="sub">Sleeper Class · Flat Bed, AVOD</div>
        </div>
        <div class="bus-time">
          <div class="t"><div class="clock">19:00</div><div class="place">Cirogol</div></div>
          <div class="mid">10j 00m<div class="line"></div>Tol Trans-Jawa</div>
          <div class="t"><div class="clock">05:00</div><div class="place">Giwangan (+1)</div></div>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:28px;">
        <div class="bus-seats ok">8 kursi tersisa</div>
        <div class="bus-price">
          <div class="lbl">per kursi</div>
          <div class="price">Rp 420.000</div>
        </div>
      </div>
    </a>

  </div>
</div>

<footer class="site-footer">
  <div class="container">
    <div class="footer-brand">🚌 TiketBus</div>
    <div class="footer-links">
      <a href="#">Syarat &amp; Ketentuan</a>
      <a href="#">Kebijakan Privasi</a>
      <a href="#">Bantuan</a>
    </div>
  </div>
</footer>

</body>
</html>

@endsection

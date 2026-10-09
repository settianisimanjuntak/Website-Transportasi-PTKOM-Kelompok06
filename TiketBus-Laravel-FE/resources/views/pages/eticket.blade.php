@extends("layouts.app")
@section("content")
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>E-Tiket Saya — TiketBus</title>
<link rel="stylesheet" href="style.css">
<style>
  .page{padding:32px 0 60px;display:flex;justify-content:center;}
  .ticket{width:100%;max-width:760px;border-radius:16px;overflow:hidden;box-shadow:var(--shadow);}
  .ticket-head{
    background:var(--green-700);color:#fff;padding:18px 24px;display:flex;justify-content:space-between;align-items:center;
  }
  .ticket-head .po{font-weight:800;font-size:15px;}
  .ticket-head .sub{font-size:11.5px;opacity:.85;margin-top:2px;}
  .status-pill{background:rgba(255,255,255,.16);padding:6px 14px;border-radius:999px;font-size:12px;font-weight:800;}
  .ticket-body{background:#fff;padding:26px 24px;display:grid;grid-template-columns:1fr 190px;gap:20px;}
  .booking-row{display:flex;justify-content:space-between;align-items:center;background:var(--green-50);
    border-radius:10px;padding:14px 16px;margin-bottom:20px;}
  .booking-row .code{font-size:20px;font-weight:800;color:var(--green-700);letter-spacing:.02em;}
  .booking-row .lbl{font-size:11px;color:var(--ink-soft);}
  .btn-tiny{background:#fff;border:1px solid var(--line);padding:8px 14px;border-radius:8px;font-size:12.5px;font-weight:700;color:var(--green-700);}
  .route-row{display:flex;align-items:center;gap:18px;margin-bottom:22px;}
  .route-time{text-align:left;}
  .route-time .clock{font-size:24px;font-weight:800;}
  .route-time .city{font-weight:700;font-size:13.5px;margin-top:4px;}
  .route-time .place{font-size:11.5px;color:var(--ink-soft);}
  .route-mid{flex:1;text-align:center;color:var(--ink-soft);font-size:11px;}
  .route-mid .line{height:1px;background:var(--line);margin:6px 0;position:relative;}
  .route-mid .line::before,.route-mid .line::after{content:"";position:absolute;top:-3px;width:7px;height:7px;border-radius:50%;background:var(--green-600);}
  .route-mid .line::before{left:0;} .route-mid .line::after{right:0;background:var(--ink);}
  .info-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:20px;}
  .info-grid .lbl{font-size:11px;color:var(--ink-soft);margin-bottom:4px;}
  .info-grid .val{font-weight:800;font-size:14px;}
  .fac-pills{display:flex;flex-wrap:wrap;gap:8px;}
  .fac-pill{border:1px solid var(--line-soft);border-radius:999px;padding:6px 12px;font-size:12px;color:var(--ink-soft);}
  .qr-side{border-left:1px dashed var(--line);padding-left:20px;text-align:center;}
  .qr-mini{width:130px;height:130px;margin:0 auto 10px;background:
      repeating-linear-gradient(0deg, #152A21 0 6px, transparent 6px 12px),
      repeating-linear-gradient(90deg, #152A21 0 6px, transparent 6px 12px);
    background-blend-mode:multiply;background-color:#fff;border-radius:6px;}
  .qr-side .lbl{font-size:11px;color:var(--ink-soft);margin-bottom:10px;}
  .qr-side .pnr{font-size:11.5px;font-weight:700;margin-bottom:14px;}
  .gate-note{background:var(--green-50);border-radius:8px;padding:10px;font-size:11px;color:var(--green-700);text-align:left;}
  .ticket-actions{display:flex;gap:12px;padding:0 24px 26px;}
  @media (max-width:720px){.ticket-body{grid-template-columns:1fr;} .qr-side{border-left:none;border-top:1px dashed var(--line);padding-left:0;padding-top:20px;}}
  @media (max-width:640px){
    .page{padding:20px 0 40px;}
    .ticket-head{flex-direction:column;align-items:flex-start;gap:10px;}
    .ticket-body{padding:18px;}
    .route-row{flex-direction:column;align-items:flex-start;gap:14px;}
    .route-time, .route-time[style]{text-align:left !important;}
    .route-mid{display:none;}
    .info-grid{grid-template-columns:1fr 1fr;row-gap:16px;}
    .ticket-actions{flex-direction:column;padding:0 18px 20px;}
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
      <a href="profile.html" class="active">Cek Pesanan</a>
      <a href="#">Bantuan</a>
      <div class="nav-divider"></div>
      <span style="font-size:17px;color:var(--ink-soft);cursor:pointer;">🔔</span>
      <span style="font-size:16px;color:var(--ink-soft);cursor:pointer;">❓</span>
      <a href="index.html" class="btn-account outline">Masuk/Daftar</a>
    </nav>
  </div>
</header>

<div class="container page">
  <div class="ticket">
    <div class="ticket-head">
      <div>
        <div class="po">PO Rosalia Indah <span style="font-weight:600;opacity:.85;">· Executive Plus</span></div>
        <div class="sub">Armada Scania K360IB Air Suspension · Kode Bus BUS-42-329</div>
      </div>
      <span class="status-pill">● LUNAS / CONFIRMED</span>
    </div>

    <div class="ticket-body">
      <div>
        <div class="booking-row">
          <div>
            <div class="lbl">Kode Booking (PNR) · Lunas &amp; Terkonfirmasi</div>
            <div class="code">PNR-882947291</div>
          </div>
          <button class="btn-tiny">⧉ Salin PNR</button>
        </div>

        <div class="route-row">
          <div class="route-time">
            <div class="clock">19:30</div>
            <div class="city">Jakarta (Pulo Debang)</div>
            <div class="place">Terminal Terpadu Pulo Gebang, Jalur 4-5</div>
            <div class="place">Kamis, 14 November 2026</div>
          </div>
          <div class="route-mid">
            <div>🚌</div>
            <div class="line"></div>
            <div>9j 15m</div>
          </div>
          <div class="route-time" style="text-align:right;">
            <div class="clock">04:45</div>
            <div class="city">Yogyakarta (Giwangan)</div>
            <div class="place">Terminal Penumpang Tipe A Giwangan</div>
            <div class="place">Jumat, 15 November 2026</div>
          </div>
        </div>

        <div class="info-grid">
          <div><div class="lbl">Nama Penumpang</div><div class="val">Budi Pratama Santoso</div></div>
          <div><div class="lbl">Nomor Kursi</div><div class="val">🪑 04A (Jendela)</div></div>
          <div><div class="lbl">Total Tarif Lunas</div><div class="val" style="color:var(--green-700);">Rp 240.000</div></div>
        </div>

        <div class="lbl" style="margin-bottom:10px;">Fasilitas Armada Termasuk:</div>
        <div class="fac-pills">
          <span class="fac-pill">❄️ Full AC</span>
          <span class="fac-pill">🛋️ Reclining Seat 2-2</span>
          <span class="fac-pill">🔌 USB Charger Port</span>
          <span class="fac-pill">🍪 Snack &amp; Air Mineral</span>
          <span class="fac-pill">🍱 Makan Prasmanan 1x</span>
          <span class="fac-pill">🚻 Toilet Dalam Bus</span>
          <span class="fac-pill">🧳 Bagasi 20kg</span>
        </div>
      </div>

      <div class="qr-side">
        <div class="lbl">Tiket Boarding Pass<br>Tunjukkan kode QR ini kepada petugas di terminal</div>
        <div class="qr-mini"></div>
        <div class="pnr">TK-9884-2026</div>
        <div class="gate-note">🕒 Pintu Dibuka: 19:00 WIB<br>Harap tiba di peron minimal 30 menit sebelum keberangkatan armada.</div>
      </div>
    </div>

    <div class="ticket-actions">
      <button class="btn btn-primary" style="flex:1;">⬇ Download PDF Tiket</button>
      <a href="search.html" class="btn btn-ghost" style="flex:1;text-align:center;">🏠 Kembali ke Beranda</a>
    </div>
  </div>
</div>

<footer class="site-footer">
  <div class="container">
    <div class="footer-brand">🚌 TiketBus</div>
    <div class="footer-links">
      <a href="#">Syarat &amp; Ketentuan</a>
      <a href="#">Kebijakan Privasi</a>
      <a href="#">Mitra Otobus</a>
      <a href="#">Pusat Terminal</a>
      <a href="#">Hubungi Kami</a>
    </div>
  </div>
</footer>

</body>
</html>

@endsection

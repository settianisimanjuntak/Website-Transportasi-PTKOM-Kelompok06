@extends("layouts.app")
@section("content")
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pembayaran — TiketBus</title>
<link rel="stylesheet" href="style.css">
<style>
  .pay-wrap{min-height:calc(100vh - 72px);display:flex;align-items:center;justify-content:center;padding:48px 24px;}
  .pay-card{width:100%;max-width:400px;padding:28px;text-align:center;}
  .pay-card .amount{font-size:26px;font-weight:800;color:var(--green-700);margin:6px 0 18px;}
  .qr-poster{
    position:relative;border:1.5px solid var(--line);border-radius:14px;padding:22px 20px 18px;margin-bottom:18px;
    overflow:hidden;background:#fff;
  }
  .qr-poster::before{
    content:"";position:absolute;top:-40px;left:-40px;width:110px;height:110px;
    background:#D14343;transform:rotate(45deg);
  }
  .qr-poster-head{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;position:relative;z-index:1;}
  .qr-poster-head .qlabel{font-size:11px;font-weight:800;color:var(--ink);line-height:1.2;text-align:left;}
  .qr-poster-head .qlabel span{display:block;font-size:9px;font-weight:600;color:var(--ink-soft);}
  .qr-poster-head .gpn{
    background:var(--red);color:#fff;font-size:11px;font-weight:800;padding:4px 10px;border-radius:5px;
  }
  .qr-brand-row{text-align:center;font-size:13px;font-weight:800;color:var(--ink);margin:10px 0 2px;}
  .qr-nmid{text-align:center;font-size:10.5px;color:var(--ink-soft);margin-bottom:4px;}
  .qr-code{width:190px;height:190px;margin:10px auto 12px;background:
      repeating-linear-gradient(0deg, #152A21 0 8px, transparent 8px 16px),
      repeating-linear-gradient(90deg, #152A21 0 8px, transparent 8px 16px);
    background-blend-mode:multiply;background-color:#fff;border-radius:6px;position:relative;}
  .qr-code::before{content:"QRIS";position:absolute;inset:0;display:flex;align-items:center;justify-content:center;
    background:#fff;width:60px;height:60px;margin:auto;font-size:10.5px;font-weight:800;color:var(--green-700);border-radius:4px;}
  .qr-foot{font-size:9.5px;color:var(--ink-soft);display:flex;justify-content:space-between;margin-top:6px;}
  .qr-tag{text-align:center;font-size:10px;font-weight:700;color:var(--ink-soft);margin-top:10px;letter-spacing:.03em;}
  .qr-label{font-size:12px;color:var(--ink-soft);margin-bottom:2px;}
  .timer{font-size:13px;color:var(--red);font-weight:700;margin-bottom:18px;}
  .status-badge{display:inline-flex;align-items:center;gap:8px;background:var(--green-100);color:var(--green-700);
    font-weight:800;padding:12px 22px;border-radius:10px;font-size:15px;margin-top:6px;}
  @media (max-width:640px){
    .pay-wrap{padding:24px 16px;}
    .pay-card{padding:20px;}
    .qr-code{width:170px;height:170px;}
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

<div class="pay-wrap">
  <div class="card pay-card">
    <div class="muted" style="font-size:13px;">Total Pembayaran</div>
    <div class="amount">Rp 220.000</div>

    <div class="qr-poster">
      <div class="qr-poster-head">
        <div class="qlabel">QRIS<span>QR Code Standar<br>Pembayaran Nasional</span></div>
        <div class="gpn">GPN</div>
      </div>
      <div class="qr-brand-row">TIKET BUS</div>
      <div class="qr-nmid">NMID : ID1021087375114 &nbsp;·&nbsp; A01</div>
      <div class="qr-code"></div>
      <div class="qr-tag">SATU QRIS UNTUK SEMUA</div>
      <div class="muted" style="font-size:10.5px;text-align:center;margin-top:4px;">Cek aplikasi penyelenggara di: www.aspi-qris.id</div>
      <div class="qr-foot"><span>Dicetak oleh: 93600918</span><span>Versi Cetak: 1.0-2021.08.12</span></div>
    </div>

    <div class="timer">⏱ Selesaikan dalam 09:47</div>

    <span class="status-badge">✓ Pembayaran Sukses</span>

    <a href="eticket.html" class="btn btn-primary btn-block" style="margin-top:22px;">Lihat E-Tiket Saya</a>
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

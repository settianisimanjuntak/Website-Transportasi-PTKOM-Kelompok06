@extends("layouts.app")
@section("content")
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PO Rosalia Indah — TiketBus</title>
<link rel="stylesheet" href="style.css">
<style>
  .page{padding:28px 0 60px;}
  .back-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:10px;}
  .back-row a.back{font-weight:700;font-size:14px;color:var(--ink-soft);}
  .title-row{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:22px;flex-wrap:wrap;gap:16px;}
  .title-row h1{font-size:26px;margin:0 0 6px;}
  .title-row .muted{font-size:14px;}
  .facts{display:flex;gap:22px;flex-wrap:wrap;}
  .fact{font-size:12.5px;color:var(--ink-soft);text-align:right;}
  .fact strong{display:block;color:var(--ink);font-size:13.5px;}
  .layout{display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start;}
  .photos{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:22px;}
  .photo-item{position:relative;border-radius:12px;overflow:hidden;height:210px;}
  .photo-item img{height:100%;width:100%;object-fit:cover;display:block;}
  .photo-item .cap{
    position:absolute;left:12px;bottom:12px;background:rgba(15,42,33,.72);color:#fff;
    font-size:11.5px;font-weight:700;padding:5px 11px;border-radius:999px;
  }
  .block{padding:22px 24px;margin-bottom:18px;}
  .block-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;}
  .route-timeline{position:relative;padding-left:26px;}
  .route-timeline::before{content:"";position:absolute;left:6px;top:8px;bottom:8px;width:2px;background:var(--line);border-style:dashed;border-color:var(--line);}
  .stop{position:relative;padding-bottom:26px;}
  .stop:last-child{padding-bottom:0;}
  .stop::before{content:"";position:absolute;left:-26px;top:3px;width:12px;height:12px;border-radius:50%;background:var(--green-600);border:3px solid var(--green-100);}
  .stop.end::before{background:var(--ink);}
  .stop-top{display:flex;justify-content:space-between;font-weight:800;font-size:15px;}
  .stop-sub{font-size:12.5px;color:var(--ink-soft);margin-top:3px;}
  .fac-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;}
  .fac{border:1px solid var(--line-soft);border-radius:10px;padding:12px 10px;text-align:center;font-size:12px;color:var(--ink-soft);}
  .fac .ic{font-size:19px;margin-bottom:6px;}
  .policy-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
  .policy{border:1px solid var(--line-soft);border-radius:10px;padding:14px;}
  .policy .h{font-weight:800;font-size:13.5px;margin-bottom:6px;}
  .policy .d{font-size:12.5px;color:var(--ink-soft);line-height:1.5;}
  .summary{position:sticky;top:88px;padding:22px;}
  .summary h3{font-size:14px;margin:0 0 14px;}
  .sum-row{display:flex;justify-content:space-between;font-size:13.5px;margin-bottom:8px;color:var(--ink-soft);}
  .sum-row span.v{color:var(--ink);font-weight:700;}
  .sum-total{display:flex;justify-content:space-between;align-items:baseline;border-top:1px solid var(--line);margin-top:14px;padding-top:14px;}
  .sum-total .lbl{font-size:13px;color:var(--ink-soft);}
  .sum-total .val{font-size:22px;font-weight:800;color:var(--green-700);}
  @media (max-width:900px){.layout{grid-template-columns:1fr;} .photos{grid-template-columns:1fr;} .fac-grid{grid-template-columns:repeat(2,1fr);} .policy-grid{grid-template-columns:1fr;}}
  @media (max-width:640px){
    .title-row{flex-direction:column;}
    .facts{gap:14px;width:100%;}
    .fact{text-align:left;flex:1;}
    .block{padding:16px;}
    .summary{position:static;}
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
      <span style="font-size:17px;color:var(--ink-soft);cursor:pointer;">🔔</span>
      <span style="font-size:16px;color:var(--ink-soft);cursor:pointer;">❓</span>
      <a href="index.html" class="btn-account outline">Masuk/Daftar</a>
    </nav>
  </div>
</header>

<div class="container page">
  <div class="back-row">
    <a href="results.html" class="back">← Ganti Bus &amp; Jadwal Lain</a>
    <span class="tag tag-green">Executive Plus</span>
  </div>

  <div class="title-row">
    <div>
      <h1>PO Rosalia Indah</h1>
      <div class="muted">Jakarta (Pulo Gebang) → Surabaya (Purabaya Bungurasih) · Sabtu, 28 Oktober 2026</div>
    </div>
    <div class="facts">
      <div class="fact"><strong>30 Kursi (2-2)</strong>Kapasitas</div>
      <div class="fact"><strong>Scania K360IB</strong>Armada &amp; Sasis</div>
      <div class="fact"><strong>Tersedia di Bus</strong>Toilet Kabin</div>
    </div>
  </div>

  <div class="layout">
    <div>
      <div class="photos">
        <div class="photo-item">
          <img src="https://images.unsplash.com/photo-1570125909232-eb263c188f7e?q=80&w=700&auto=format&fit=crop" alt="Interior kabin">
          <span class="cap">Interior Kabin</span>
        </div>
        <div class="photo-item">
          <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=700&auto=format&fit=crop" alt="Tampak luar bus">
          <span class="cap">Tampak Luar Bus</span>
        </div>
      </div>

      <div class="card block">
        <div class="block-head"><h3 class="section-title" style="margin:0;">Rute &amp; Jadwal Perjalanan</h3><span class="tag tag-green">Trans-Jawa · 11j 30m</span></div>
        <div class="route-timeline">
          <div class="stop">
            <div class="stop-top"><span>Terminal Pulo Gebang (Jakarta)</span><span>18:00 WIB</span></div>
            <div class="stop-sub">Titik awal keberangkatan · Pintu keberangkatan jalur 5</div>
          </div>
          <div class="stop">
            <div class="stop-top"><span style="font-weight:600;color:var(--ink-soft);">Rest Area KM 207 Cipali</span><span>21:30 WIB</span></div>
            <div class="stop-sub">Istirahat &amp; makan malam prasmanan gratis (30 menit)</div>
          </div>
          <div class="stop">
            <div class="stop-top"><span style="font-weight:600;color:var(--ink-soft);">Terminal Tirtonadi (Solo)</span><span>03:30 WIB</span></div>
            <div class="stop-sub">Transit &amp; penurunan penumpang wilayah Surakarta</div>
          </div>
          <div class="stop end">
            <div class="stop-top"><span>Terminal Purabaya Bungurasih (Surabaya)</span><span>05:30 WIB</span></div>
            <div class="stop-sub">Tujuan akhir perjalanan · Jalur kedatangan bus antarkota</div>
          </div>
        </div>
      </div>

      <div class="card block">
        <div class="block-head"><h3 class="section-title" style="margin:0;">Fasilitas Unggulan Armada</h3><span class="tag tag-green">Executive Plus Class</span></div>
        <div class="fac-grid">
          <div class="fac"><div class="ic">❄️</div>Full AC Dingin</div>
          <div class="fac"><div class="ic">🛋️</div>Reclining &amp; Legrest</div>
          <div class="fac"><div class="ic">🛏️</div>Bantal &amp; Selimut</div>
          <div class="fac"><div class="ic">🔌</div>USB Charger</div>
          <div class="fac"><div class="ic">📶</div>Wi-Fi Onboard</div>
          <div class="fac"><div class="ic">🎬</div>AVOD Pribadi</div>
          <div class="fac"><div class="ic">🍱</div>Makan &amp; Snack</div>
          <div class="fac"><div class="ic">🚻</div>Toilet Bersih</div>
        </div>
      </div>

      <div class="card block">
        <h3 class="section-title">Ketentuan Pembatalan &amp; Perubahan Jadwal</h3>
        <div class="policy-grid">
          <div class="policy">
            <div class="h">🔁 Bisa Reschedule</div>
            <div class="d">Pengajuan perubahan waktu paling lambat 12 jam sebelum keberangkatan via aplikasi. Dikenakan biaya administrasi PO 10%.</div>
          </div>
          <div class="policy">
            <div class="h">⊘ Pembatalan &amp; Pengembalian Dana</div>
            <div class="d">Pengembalian dana 75% jika dibatalkan minimal 24 jam sebelum keberangkatan. Di bawah 24 jam tiket hangus sesuai regulasi operator bus.</div>
          </div>
        </div>
      </div>
    </div>

    <div class="card summary">
      <h3>Ringkasan Tiket <span style="float:right;color:var(--ink-soft);font-weight:600;">1 Kursi</span></h3>
      <div class="sum-row"><span>PO Rosalia Indah</span></div>
      <div class="sum-row"><span>Executive Plus · Jakarta → Surabaya</span></div>
      <div class="sum-row" style="margin-bottom:16px;"><span>Sabtu, 24 Juni 2026 · 18:00 WIB</span></div>
      <div class="sum-row"><span>Tarif Bus</span><span class="v">Rp 375.000</span></div>
      <div class="sum-row"><span>Asuransi Jasa Raharja</span><span class="v">Rp 5.000</span></div>
      <div class="sum-row"><span>Biaya Layanan</span><span class="v">Gratis</span></div>
      <div class="sum-total">
        <div><div class="lbl">Total Bayar</div><div class="val">Rp 380.000</div></div>
        <span class="tag tag-green">Termasuk PPN</span>
      </div>
      <a href="seat.html" class="btn btn-primary btn-block" style="margin-top:16px;">Lanjut Pilih Kursi →</a>
      <div class="muted" style="font-size:12px;text-align:center;margin-top:10px;">Tiket resmi diterbitkan langsung oleh PO bus</div>
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

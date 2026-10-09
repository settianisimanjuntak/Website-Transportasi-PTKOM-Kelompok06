@extends("layouts.app")
@section("content")
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Profil Saya — TiketBus</title>
<link rel="stylesheet" href="style.css">
<style>
  .page{padding:28px 0 60px;}
  .profile-hero{
    background:linear-gradient(120deg, var(--green-700), var(--green-900));
    border-radius:16px;color:#fff;padding:26px 28px;display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:16px;
  }
  .p-left{display:flex;align-items:center;gap:16px;}
  .avatar{width:64px;height:64px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;color:var(--green-700);font-size:20px;}
  .p-name{font-size:18px;font-weight:800;}
  .p-name .tag{background:rgba(255,255,255,.18);color:#fff;margin-left:8px;}
  .p-sub{font-size:12.5px;opacity:.85;margin-top:3px;}
  .p-stats{display:flex;gap:32px;text-align:center;}
  .p-stats .num{font-size:20px;font-weight:800;}
  .p-stats .lbl{font-size:11px;opacity:.8;}

  .profile-layout{display:grid;grid-template-columns:220px 1fr;gap:24px;align-items:start;}
  .side-menu{padding:10px;}
  .side-menu a{display:block;padding:12px 14px;border-radius:8px;font-size:13.5px;font-weight:700;color:var(--ink-soft);margin-bottom:2px;}
  .side-menu a.active{background:var(--green-100);color:var(--green-700);}
  .side-menu a.danger{color:var(--red);margin-top:10px;}

  .block{padding:24px;margin-bottom:20px;}
  .form-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:10px;}
  .btn-outline{background:#fff;border:1.5px solid var(--line);color:var(--ink-soft);padding:12px 20px;border-radius:10px;font-weight:700;font-size:14px;}

  .trip-row{display:flex;justify-content:space-between;align-items:center;padding:16px;border:1px solid var(--line-soft);border-radius:10px;flex-wrap:wrap;gap:12px;}
  .trip-info{display:flex;align-items:center;gap:18px;}
  .trip-info .t{font-weight:800;}
  .trip-info .p{font-size:11.5px;color:var(--ink-soft);}
  @media (max-width:640px){
    .profile-hero{flex-direction:column;align-items:flex-start;padding:20px;}
    .p-stats{width:100%;justify-content:space-between;gap:10px;}
    .profile-layout{grid-template-columns:1fr;}
    .side-menu{display:flex;overflow-x:auto;gap:6px;padding:8px;}
    .side-menu a{white-space:nowrap;margin-bottom:0;}
    .block{padding:16px;}
    .field-row{flex-direction:column;gap:0;}
    .trip-row{flex-direction:column;align-items:stretch;}
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
      <div class="avatar" style="width:34px;height:34px;font-size:13px;background:var(--green-100);color:var(--green-700);">BS</div>
    </nav>
  </div>
</header>

<div class="container page">

  <div class="profile-hero">
    <div class="p-left">
      <div class="avatar">BS</div>
      <div>
        <div class="p-name">Budi Santoso <span class="tag">TiketBus Priority Member</span></div>
        <div class="p-sub">budi.santoso@email.com · Bergabung sejak Maret 2023</div>
      </div>
    </div>
    <div class="p-stats">
      <div><div class="num">14</div><div class="lbl">Perjalanan</div></div>
      <div><div class="num">2</div><div class="lbl">Tiket Aktif</div></div>
      <div><div class="num">1.250</div><div class="lbl">Poin TiketBus</div></div>
    </div>
  </div>

  <div class="profile-layout">
    <div class="card side-menu">
      <a class="active">👤 Data Diri &amp; Akun</a>
      <a>🎫 Riwayat Tiket &amp; Perjalanan</a>
      <a>💳 Metode Pembayaran</a>
      <a>🛡 Keamanan Akun</a>
      <a class="danger">⎋ Keluar</a>
    </div>

    <div>
      <div class="card block">
        <h3 class="section-title">Informasi Pribadi</h3>
        <p class="muted" style="font-size:12.5px;margin-top:-10px;">Perbarui data diri Anda untuk kemudahan verifikasi dan pemesanan tiket bus.</p>
        <div class="field-row">
          <div class="field"><label>Nama Lengkap Sesuai KTP</label><input type="text" value="Budi Santoso"></div>
          <div class="field"><label>Nomor Induk Kependudukan (NIK)</label><input type="text" value="3201************"></div>
        </div>
        <div class="field-row">
          <div class="field"><label>Alamat Email <span class="tag tag-green" style="margin-left:6px;">Terverifikasi</span></label><input type="email" value="budi.santoso@email.com"></div>
          <div class="field"><label>Nomor Ponsel (WhatsApp Aktif)</label><input type="tel" value="+62 812 3456 7890"></div>
        </div>
        <div class="field-row">
          <div class="field"><label>Tanggal Lahir</label><input type="text" value="14 Agustus 1992"></div>
          <div class="field">
            <label>Jenis Kelamin</label>
            <div style="display:flex;gap:10px;">
              <label class="checkbox-row" style="border:1.5px solid var(--green-500);background:var(--green-50);padding:12px 16px;border-radius:9px;flex:1;font-weight:700;color:var(--ink);"><input type="radio" name="gender" checked style="accent-color:var(--green-500);">Pria</label>
              <label class="checkbox-row" style="border:1.5px solid var(--line);padding:12px 16px;border-radius:9px;flex:1;">Wanita</label>
            </div>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn-outline">Batal</button>
          <button class="btn btn-primary">✓ Simpan Perubahan</button>
        </div>
      </div>

      <div class="card block">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
          <h3 class="section-title" style="margin:0;">Perjalanan Mendatang</h3>
          <a href="#" style="font-size:13px;font-weight:800;color:var(--green-700);">Lihat Semua (2)</a>
        </div>
        <div class="trip-row">
          <div class="trip-info">
            <span class="tag tag-green">Suite Class</span>
            <div>
              <div class="t">PO Sinar Jaya · Nomor Armada: SJ-702</div>
              <div class="p">19:30 Terminal Pulo Gebang, Jakarta · Langsung · 03:45 Terminal Giwangan, Yogyakarta</div>
            </div>
          </div>
          <div style="text-align:right;">
            <div class="muted" style="font-size:11.5px;">Selasa, 25 Nov 2026 · Kursi: 02A (Single Deck)</div>
            <a href="eticket.html" class="btn btn-primary" style="padding:8px 16px;font-size:12.5px;margin-top:6px;display:inline-flex;">⬇ E-Tiket</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="site-footer">
  <div class="container">
    <div class="footer-brand">🚌 TiketBus</div>
    <div style="font-size:12px;opacity:.8;">© 2026 TiketBus. Hak cipta dilindungi undang-undang.</div>
    <div class="footer-links">
      <a href="#">Tentang TiketBus</a>
      <a href="#">Syarat &amp; Ketentuan</a>
      <a href="#">Kebijakan Privasi</a>
      <a href="#">Pusat Bantuan</a>
      <a href="#">Mitra Operator</a>
      <a href="#">Terminal Bus</a>
    </div>
  </div>
</footer>

</body>
</html>

@endsection

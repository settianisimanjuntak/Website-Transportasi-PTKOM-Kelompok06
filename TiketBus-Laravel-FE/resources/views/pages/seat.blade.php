@extends("layouts.app")
@section("content")
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pilih Kursi &amp; Bayar — TiketBus</title>
<link rel="stylesheet" href="style.css">
<style>
  .page{padding:28px 0 60px;}
  .layout{display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start;}
  .block{padding:22px 24px;margin-bottom:18px;}
  .checkbox-row{display:flex;align-items:center;gap:8px;font-size:13.5px;color:var(--ink-soft);}

  .bus-frame{
    max-width:320px;margin:0 auto;border:1.5px solid var(--line);border-radius:16px;padding:20px 22px 26px;background:var(--green-50);
  }
  .bus-frame-top{display:flex;justify-content:space-between;font-size:11.5px;color:var(--ink-soft);font-weight:700;margin-bottom:14px;}
  .seat-grid{display:grid;grid-template-columns:repeat(2,44px) 26px repeat(2,44px);gap:10px;justify-content:center;}
  .seat{
    width:44px;height:44px;border-radius:9px;border:1.5px solid var(--line);background:#fff;
    display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:var(--ink-soft);
  }
  .seat.taken{background:#EDEFED;border-color:#EDEFED;color:#B7BFB9;cursor:not-allowed;}
  .seat.available{cursor:pointer;}
  .seat.available:hover{border-color:var(--green-500);}
  .seat.selected{background:var(--green-600);border-color:var(--green-600);color:#fff;}
  .aisle{visibility:hidden;}
  .legend{display:flex;gap:18px;justify-content:center;margin-top:18px;font-size:12px;color:var(--ink-soft);}
  .legend span{display:inline-flex;align-items:center;gap:6px;}
  .dot{width:12px;height:12px;border-radius:4px;display:inline-block;}
  .dot.av{background:#fff;border:1.5px solid var(--line);}
  .dot.sel{background:var(--green-600);}
  .dot.tk{background:#EDEFED;}
  .seat-picked{text-align:center;margin-top:16px;font-size:13.5px;}
  .seat-picked b{color:var(--green-700);}

  .pay-methods{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;}
  .pay-opt{border:1.5px solid var(--line);border-radius:10px;padding:14px;cursor:pointer;font-size:13px;}
  .pay-opt.selected{border-color:var(--green-600);background:var(--green-50);}
  .pay-opt .top{display:flex;justify-content:space-between;font-weight:800;margin-bottom:6px;}
  .pay-opt .sub{color:var(--ink-soft);font-size:11.5px;}

  .toggle{position:relative;width:42px;height:24px;flex-shrink:0;}
  .toggle input{opacity:0;width:0;height:0;}
  .toggle .slider{position:absolute;inset:0;background:var(--line);border-radius:999px;transition:.15s;}
  .toggle .slider::before{content:"";position:absolute;width:18px;height:18px;left:3px;top:3px;background:#fff;border-radius:50%;transition:.15s;}
  .toggle input:checked + .slider{background:var(--green-600);}
  .toggle input:checked + .slider::before{transform:translateX(18px);}
  .protect-row{display:flex;justify-content:space-between;align-items:center;padding:16px;border:1px solid var(--line-soft);border-radius:10px;}
  .protect-row .h{font-weight:800;font-size:13.5px;}
  .protect-row .d{font-size:12px;color:var(--ink-soft);margin-top:4px;line-height:1.5;max-width:420px;}

  .summary{position:sticky;top:88px;padding:22px;}
  .sum-row{display:flex;justify-content:space-between;font-size:13.5px;margin-bottom:10px;color:var(--ink-soft);}
  .sum-row span.v{color:var(--ink);font-weight:700;}
  .sum-total{display:flex;justify-content:space-between;align-items:baseline;border-top:1px solid var(--line);margin-top:14px;padding-top:14px;}
  .sum-total .val{font-size:22px;font-weight:800;color:var(--green-700);}
  @media (max-width:900px){.layout{grid-template-columns:1fr;} .pay-methods{grid-template-columns:1fr;}}
  @media (max-width:640px){
    .block{padding:16px;}
    .field-row{flex-direction:column;gap:0;}
    .bus-frame{max-width:100%;padding:16px 14px 20px;}
    .seat{width:38px;height:38px;}
    .seat-grid{grid-template-columns:repeat(2,38px) 20px repeat(2,38px);}
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
  <div class="layout">
    <div>
      <div class="card block">
        <h3 class="section-title">Data Kontak Pemesan</h3>
        <p class="muted" style="font-size:12.5px;margin-top:-8px;">E-tiket dan kode booking akan dikirimkan ke nomor WhatsApp dan email di bawah ini.</p>
        <div class="field"><label>Nama Lengkap Pemesan</label><input type="text" placeholder="Budi Hendrawan"></div>
        <div class="field-row">
          <div class="field"><label>Nomor HP / WhatsApp</label><input type="tel" placeholder="+62 812 3456 7890"></div>
          <div class="field"><label>Alamat Email</label><input type="email" placeholder="budi.hendrawan@email.com"></div>
        </div>
        <label class="checkbox-row"><input type="checkbox" style="accent-color:var(--green-500);" checked>Saya juga merupakan salah satu penumpang</label>
      </div>

      <div class="card block">
        <h3 class="section-title">Pilih Kursi Bus <span style="float:right;font-weight:600;color:var(--ink-soft);">Format 2-2</span></h3>
        <div class="bus-frame">
          <div class="bus-frame-top"><span>🚪 Pintu Depan</span><span>🧑‍✈️ Supir</span></div>
          <div class="seat-grid" id="seatGrid"></div>
          <div class="legend">
            <span><i class="dot av"></i>Tersedia</span>
            <span><i class="dot sel"></i>Terpilih</span>
            <span><i class="dot tk"></i>Terisi</span>
          </div>
          <div style="text-align:center;font-size:11px;color:var(--ink-soft);margin-top:14px;">🚻 Toilet · Belakang Bus</div>
        </div>
        <div class="seat-picked">Kursi yang dipilih: <b id="seatLabel">—</b></div>
      </div>

      <div class="card block">
        <h3 class="section-title">Data Penumpang 1 <span style="float:right;font-weight:600;color:var(--green-700);" id="seatBadge">Kursi —</span></h3>
        <div class="field-row">
          <div class="field"><label>Nama Lengkap Penumpang</label><input type="text" placeholder="Budi Hendrawan"></div>
          <div class="field"><label>Nomor Induk Kependudukan (NIK)</label><input type="text" placeholder="3273152004890002"></div>
        </div>
        <p class="muted" style="font-size:12px;margin:0;">Pastikan nama dan NIK sesuai dokumen identitas resmi untuk proses boarding.</p>
      </div>

      <div class="card block">
        <div class="protect-row">
          <div>
            <div class="h">Perlindungan Perjalanan Aman <span class="muted" style="font-weight:600;">+Rp 10.000</span></div>
            <div class="d">Santunan keterlambatan armada lebih dari 90 menit (Rp 50.000), serta kompensasi bagasi hilang atau rusak hingga Rp 1.500.000, dan proteksi kecelakaan perjalanan.</div>
          </div>
          <label class="toggle"><input type="checkbox" checked><span class="slider"></span></label>
        </div>
      </div>

      <div class="card block">
        <h3 class="section-title">Metode Pembayaran Instan</h3>
        <div class="pay-methods">
          <div class="pay-opt selected"><div class="top">QRIS <span>●</span></div><div class="sub">BCA, Mandiri, GoPay, OVO</div></div>
          <div class="pay-opt"><div class="top">Transfer VA <span></span></div><div class="sub">BCA, BRI, Mandiri, BNI</div></div>
          <div class="pay-opt"><div class="top">E-Wallet <span></span></div><div class="sub">ShopeePay, DANA</div></div>
        </div>
      </div>
    </div>

    <div class="card summary">
      <div style="margin-bottom:16px;">
        <span class="tag tag-green">Executive</span>
        <div style="font-weight:800;font-size:16px;margin-top:8px;">PO Sinar Jaya</div>
      </div>
      <div class="sum-row"><span>19:30 WIB</span><span class="v">Jumat, 26 Jun 2026</span></div>
      <div class="sum-row" style="margin-bottom:14px;"><span>Terminal Pulo Gebang, Jakarta Timur</span></div>
      <div class="sum-row"><span>04:30 WIB (+1 hari)</span><span class="v">Terminal Tirtonadi, Solo</span></div>
      <div style="border-top:1px solid var(--line);margin:14px 0;"></div>
      <div class="sum-row"><span>Nomor Kursi</span><span class="v" id="seatSummary">Belum dipilih</span></div>
      <div class="sum-row"><span>Tarif Tiket Bus (1 Penumpang)</span><span class="v">Rp 210.000</span></div>
      <div class="sum-row"><span>Perlindungan Perjalanan</span><span class="v">Rp 10.000</span></div>
      <div class="sum-row"><span>Biaya Layanan Sistem</span><span class="v">Gratis</span></div>
      <div class="sum-total">
        <div><div class="muted" style="font-size:13px;">Total Pembayaran</div><div class="val">Rp 220.000</div></div>
        <span class="tag tag-green">Termasuk Pajak</span>
      </div>
      <a href="payment.html" class="btn btn-primary btn-block" style="margin-top:16px;">Lanjut Pembayaran →</a>
      <div class="muted" style="font-size:12px;text-align:center;margin-top:10px;">🔒 Transaksi terenkripsi dan aman 100%</div>
    </div>

    <div class="card" style="padding:16px 18px;margin-top:16px;display:flex;align-items:center;gap:12px;">
      <span style="font-size:20px;">📞</span>
      <div style="font-size:12.5px;">
        <div style="font-weight:700;">Butuh bantuan formulir?</div>
        <div class="muted">Layanan CS TiketBus siap 24 jam melalui WhatsApp.</div>
      </div>
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

<script>
  const rows = [
    ['1A','1B',null,'1C','1D'],
    ['2A','2B',null,'2C','2D'],
    ['3A','3B',null,'3C','3D'],
    ['4A','4B',null,'4C','4D'],
  ];
  const taken = ['1C','2C','3C','3D'];
  const grid = document.getElementById('seatGrid');
  let selected = null;

  rows.flat().forEach(id=>{
    const el = document.createElement('div');
    if(id === null){ el.className='seat aisle'; grid.appendChild(el); return; }
    el.className = 'seat ' + (taken.includes(id) ? 'taken' : 'available');
    el.textContent = id;
    if(id === '3A'){ el.classList.add('selected'); el.classList.remove('available'); selected = id; }
    if(!taken.includes(id)){
      el.addEventListener('click', ()=>{
        document.querySelectorAll('.seat.selected').forEach(s=>{ s.classList.remove('selected'); s.classList.add('available'); });
        el.classList.add('selected');
        el.classList.remove('available');
        selected = id;
        updateSeatLabel();
      });
    }
    grid.appendChild(el);
  });

  function updateSeatLabel(){
    document.getElementById('seatLabel').textContent = selected + ' (Jendela)';
    document.getElementById('seatBadge').textContent = 'Kursi ' + selected;
    document.getElementById('seatSummary').textContent = selected + ' (Jendela)';
  }
  updateSeatLabel();

  document.querySelectorAll('.pay-opt').forEach(opt=>{
    opt.addEventListener('click', ()=>{
      document.querySelectorAll('.pay-opt').forEach(o=>o.classList.remove('selected'));
      opt.classList.add('selected');
    });
  });
</script>

</body>
</html>

@endsection

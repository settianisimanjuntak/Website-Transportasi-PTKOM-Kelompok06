@extends("layouts.app")
@section("content")
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin — TiketBus</title>
<link rel="stylesheet" href="style.css">
<style>
  body{background:#F4F6F4;}
  .admin-shell{display:grid;grid-template-columns:230px 1fr;min-height:100vh;}
  .admin-side{background:var(--green-900);color:#CFE4D8;padding:22px 16px;}
  .admin-brand{display:flex;align-items:center;gap:9px;color:#fff;font-weight:800;font-size:16px;padding:0 8px 24px;}
  .admin-user{display:flex;align-items:center;gap:10px;padding:12px 8px 22px;border-bottom:1px solid rgba(255,255,255,.12);margin-bottom:14px;}
  .admin-user .av{width:34px;height:34px;border-radius:8px;background:var(--green-600);display:flex;align-items:center;justify-content:center;font-weight:800;color:#fff;font-size:12px;}
  .admin-user .name{color:#fff;font-weight:700;font-size:13px;}
  .admin-user .role{font-size:11px;opacity:.75;}
  .admin-nav a{display:flex;align-items:center;gap:10px;padding:11px 12px;border-radius:8px;font-size:13.5px;font-weight:600;margin-bottom:2px;color:#CFE4D8;}
  .admin-nav a.active{background:var(--green-600);color:#fff;}

  .admin-main{padding:28px 32px;}
  .admin-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px;}
  .search-box{position:relative;}
  .search-box input{width:280px;padding:11px 14px 11px 36px;border-radius:9px;border:1px solid var(--line);font-size:13.5px;background:#fff;}
  .search-box::before{content:"🔍";position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:12px;opacity:.6;}
  .admin-top-right{display:flex;align-items:center;gap:14px;}
  .term-select{background:#fff;border:1px solid var(--line);padding:9px 14px;border-radius:9px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;}
  .online-dot{width:8px;height:8px;border-radius:50%;background:var(--green-500);display:inline-block;}
  .btn-add{background:var(--green-600);color:#fff;padding:11px 18px;border-radius:9px;font-weight:700;font-size:13.5px;border:none;}

  h1.page-h{font-size:22px;margin:0 0 4px;}
  .page-sub{font-size:13.5px;color:var(--ink-soft);margin-bottom:22px;}

  .kpi-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;}
  .kpi{padding:20px;}
  .kpi .lbl{font-size:12.5px;color:var(--ink-soft);margin-bottom:10px;display:flex;justify-content:space-between;}
  .kpi .val{font-size:26px;font-weight:800;}
  .kpi .sub{font-size:11.5px;color:var(--ink-soft);margin-top:4px;}

  .table-card{padding:22px;}
  .table-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:12px;}
  .table-filters{display:flex;gap:10px;}
  .table-filters select{border:1px solid var(--line);border-radius:8px;padding:8px 12px;font-size:12.5px;font-weight:600;background:#fff;}
  table{width:100%;border-collapse:collapse;font-size:13.5px;}
  th{text-align:left;color:var(--ink-soft);font-size:11.5px;text-transform:none;font-weight:700;padding:10px 12px;border-bottom:1px solid var(--line);}
  td{padding:14px 12px;border-bottom:1px solid var(--line-soft);vertical-align:middle;}
  .po-name{font-weight:700;}
  .po-code{font-size:11px;color:var(--ink-soft);}
  .status-dot{display:inline-flex;align-items:center;gap:6px;font-weight:700;font-size:12.5px;}
  .status-dot::before{content:"●";font-size:9px;}
  .st-schedule::before{color:#B79A2E;} .st-schedule{color:#8A711F;}
  .st-boarding::before{color:var(--green-600);} .st-boarding{color:var(--green-700);}
  .st-berangkat{color:#fff;background:var(--green-600);padding:5px 10px;border-radius:6px;font-size:11.5px;font-weight:700;}
  .st-selesai::before{color:var(--ink-soft);} .st-selesai{color:var(--ink-soft);}
  .detail-link{color:var(--green-700);font-weight:700;font-size:12.5px;}
  .table-foot{display:flex;justify-content:space-between;align-items:center;margin-top:16px;font-size:12.5px;color:var(--ink-soft);}
  .pager{display:flex;gap:6px;}
  .pager span{width:28px;height:28px;border-radius:6px;display:flex;align-items:center;justify-content:center;border:1px solid var(--line);}
  .pager span.active{background:var(--green-600);color:#fff;border-color:var(--green-600);}
  @media (max-width:1100px){.kpi-grid{grid-template-columns:repeat(2,1fr);}}
  @media (max-width:760px){
    .admin-shell{grid-template-columns:1fr;}
    .admin-side{
      position:fixed;top:0;left:0;bottom:0;width:220px;z-index:50;
      transform:translateX(-100%);transition:transform .2s ease;
    }
    .admin-side.open{transform:translateX(0);}
    .admin-main{padding:18px;}
    .admin-top{flex-direction:column;align-items:stretch;}
    .search-box input{width:100%;}
    .admin-top-right{flex-wrap:wrap;}
    .kpi-grid{grid-template-columns:1fr;}
    table{display:block;overflow-x:auto;white-space:nowrap;}
    .menu-btn{display:inline-flex;}
  }
  .menu-btn{display:none;align-items:center;justify-content:center;width:38px;height:38px;border-radius:9px;
    border:1px solid var(--line);background:#fff;font-size:16px;flex-shrink:0;cursor:pointer;}
  .sidebar-backdrop{display:none;}
  @media (max-width:760px){
    .sidebar-backdrop.open{display:block;position:fixed;inset:0;background:rgba(0,0,0,.35);z-index:40;}
  }
</style>
</head>
<body>
<div class="sidebar-backdrop" onclick="document.querySelector('.admin-side').classList.remove('open');this.classList.remove('open');"></div>
<div class="admin-shell">

  <aside class="admin-side">
    <div class="admin-brand"><span class="brand-mark">🚌</span>TiketBus</div>
    <div class="admin-user">
      <div class="av">AD</div>
      <div><div class="name">Admin TiketBus</div><div class="role">Superadmin Terminal Pusat</div></div>
    </div>
    <nav class="admin-nav">
      <a class="active">▦ Dashboard</a>
      <a>🚌 Data Bus</a>
      <a>🕒 Jadwal</a>
      <a>🎫 Pemesanan</a>
      <a style="margin-top:14px;">⚙ Pengaturan</a>
      <a>🧑 Profil Admin</a>
    </nav>
  </aside>

  <main class="admin-main">
    <div class="admin-top">
      <div style="display:flex;align-items:center;gap:12px;">
        <button class="menu-btn" onclick="document.querySelector('.admin-side').classList.toggle('open');document.querySelector('.sidebar-backdrop').classList.toggle('open');">☰</button>
        <div class="search-box"><input type="text" placeholder="Cari rute, nomor bus, PO..."></div>
      </div>
      <div class="admin-top-right">
        <div class="term-select">📍 Terminal Pulo Gebang (Jakarta Timur)</div>
        <div class="term-select"><span class="online-dot"></span>Sistem Online</div>
        <button class="btn-add">+ Tambah Jadwal Baru</button>
      </div>
    </div>

    <h1 class="page-h">Pusat Kendali Operasional</h1>
    <div class="page-sub">Pemantauan armada antarkota antarprovinsi hari ini, 27 September 2026</div>

    <div class="kpi-grid">
      <div class="card kpi">
        <div class="lbl">Total Armada <span>🚌</span></div>
        <div class="val">48 Bus</div>
        <div class="sub">Siap &amp; beroperasi hari ini</div>
      </div>
      <div class="card kpi">
        <div class="lbl">Okupansi Hari Ini <span>👥</span></div>
        <div class="val">94%</div>
        <div class="sub">Ketersediaan bangku optimal</div>
      </div>
      <div class="card kpi">
        <div class="lbl">Penumpang Berangkat <span>🧑‍🤝‍🧑</span></div>
        <div class="val">1.240 Orang</div>
        <div class="sub">Keberangkatan terkonfirmasi</div>
      </div>
      <div class="card kpi">
        <div class="lbl">Status Ketepatan Waktu <span>⏱</span></div>
        <div class="val">98.5%</div>
        <div class="sub">Sesuai jadwal keberangkatan</div>
      </div>
    </div>

    <div class="card table-card">
      <div class="table-head">
        <div style="font-weight:800;">Menampilkan 5 dari 48 keberangkatan</div>
        <div class="table-filters">
          <select><option>Semua Status</option><option>On Schedule</option><option>Boarding</option></select>
          <select><option>Semua Operator Bus</option><option>PO Rosalia Indah</option><option>PO Sinar Jaya</option></select>
          <button class="btn-outline" style="padding:8px 14px;font-size:12.5px;">⬇ Ekspor Data</button>
        </div>
      </div>
      <table>
        <thead>
          <tr><th>No</th><th>PO Bus</th><th>Rute</th><th>Jam</th><th>Kursi</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          <tr>
            <td>01</td>
            <td><div class="po-name">PO Rosalia Indah <span class="tag tag-green">Executive Plus</span></div><div class="po-code">BUS-42-329</div></td>
            <td>Jakarta (Pulo Gebang) → Surabaya</td>
            <td>18:00 – 05:30 WIB</td>
            <td>28 / 30 terisi</td>
            <td><span class="status-dot st-schedule">On Schedule</span></td>
            <td><a class="detail-link">Detail</a></td>
          </tr>
          <tr>
            <td>02</td>
            <td><div class="po-name">PO Sinar Jaya <span class="tag tag-amber">Suite Class</span></div><div class="po-code">BUS-53-166</div></td>
            <td>Jakarta → Solo (Tirtonadi)</td>
            <td>19:30 – 04:30 WIB</td>
            <td>21 / 22 terisi</td>
            <td><span class="status-dot st-boarding">Boarding</span></td>
            <td><a class="detail-link">Detail</a></td>
          </tr>
          <tr>
            <td>03</td>
            <td><div class="po-name">PO Harapan Jaya <span class="tag tag-green">Super Luxury</span></div><div class="po-code">BUS-42-215</div></td>
            <td>Jakarta → Yogyakarta</td>
            <td>16:30 – 01:45 WIB</td>
            <td>26 / 28 (Penuh)</td>
            <td><span class="st-berangkat">Siap Berangkat</span></td>
            <td><a class="detail-link">Detail</a></td>
          </tr>
          <tr>
            <td>04</td>
            <td><div class="po-name">PO Gunung Harta <span class="tag tag-green">Green Platinum</span></div><div class="po-code">BUS-58-772</div></td>
            <td>Jakarta → Malang (Arjosari)</td>
            <td>14:00 – 05:00 WIB</td>
            <td>30 / 32 terisi</td>
            <td><span class="status-dot st-schedule">On Schedule</span></td>
            <td><a class="detail-link">Detail</a></td>
          </tr>
          <tr>
            <td>05</td>
            <td><div class="po-name">PO Juragan 99 <span class="tag tag-amber">Sleeper Dream</span></div><div class="po-code">BUS-280-84</div></td>
            <td>Jakarta → Surabaya</td>
            <td>20:00 – 06:50 WIB</td>
            <td>18 / 18 (Penuh)</td>
            <td><span class="status-dot st-selesai">Sedang Check-in</span></td>
            <td><a class="detail-link">Detail</a></td>
          </tr>
        </tbody>
      </table>
      <div class="table-foot">
        <div>Halaman 1 dari 8</div>
        <div class="pager"><span class="active">1</span><span>2</span><span>3</span><span>…</span><span>8</span><span>›</span></div>
      </div>
    </div>
  </main>
</div>
</body>
</html>

@endsection

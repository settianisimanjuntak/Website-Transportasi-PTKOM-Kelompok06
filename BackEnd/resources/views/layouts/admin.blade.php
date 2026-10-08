<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Admin — TiketBus')</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@stack('head')
<style>
  body{background:#F4F6F4;}
  .admin-shell{display:grid;grid-template-columns:230px 1fr;min-height:100vh;}
  .admin-side{background:var(--green-900);color:#CFE4D8;padding:22px 16px;}
  .admin-brand{display:flex;align-items:center;gap:9px;color:#fff;font-weight:800;font-size:16px;padding:0 8px 24px;}
  .admin-user{display:flex;align-items:center;gap:10px;padding:12px 8px 22px;border-bottom:1px solid rgba(255,255,255,.12);margin-bottom:14px;}
  .admin-user .av{width:34px;height:34px;border-radius:8px;background:var(--green-600);display:flex;align-items:center;justify-content:center;font-weight:800;color:#fff;font-size:12px;}
  .admin-user .name{color:#fff;font-weight:700;font-size:13px;}
  .admin-user .role{font-size:11px;opacity:.75;}
  .admin-nav a{display:flex;align-items:center;gap:10px;padding:11px 12px;border-radius:8px;font-size:13.5px;font-weight:600;margin-bottom:2px;color:#CFE4D8;text-decoration:none;}
  .admin-nav a:hover{background:rgba(255,255,255,.06);color:#fff;}
  .admin-nav a.active{background:var(--green-600);color:#fff;}
  .admin-nav .nav-gap{height:14px;}
  .admin-nav form{margin:0;}
  .admin-nav .logout{color:#F3B4B4;background:none;border:none;width:100%;text-align:left;font-family:inherit;cursor:pointer;}

  .admin-main{padding:28px 32px;min-width:0;}
  h1.page-h{font-size:22px;margin:0 0 4px;}
  .page-sub{font-size:13.5px;color:var(--ink-soft);margin-bottom:22px;}
  .admin-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;flex-wrap:wrap;gap:12px;}
  .search-box{position:relative;}
  .search-box input{width:280px;max-width:100%;padding:11px 14px 11px 36px;border-radius:9px;border:1px solid var(--line);font-size:13.5px;background:#fff;}
  .search-box::before{content:"🔍";position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:12px;opacity:.6;}
  .btn-add{background:var(--green-600);color:#fff;padding:11px 18px;border-radius:9px;font-weight:700;font-size:13.5px;border:none;text-decoration:none;display:inline-flex;align-items:center;gap:8px;cursor:pointer;}
  .btn-outline{background:#fff;border:1.5px solid var(--line);color:var(--ink-soft);padding:9px 16px;border-radius:9px;font-weight:700;font-size:12.5px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;font-family:inherit;}

  .kpi-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;}
  .kpi{padding:20px;}
  .kpi .lbl{font-size:12.5px;color:var(--ink-soft);margin-bottom:10px;display:flex;justify-content:space-between;}
  .kpi .val{font-size:26px;font-weight:800;}
  .kpi .sub{font-size:11.5px;color:var(--ink-soft);margin-top:4px;}

  .table-card{padding:22px;}
  .table-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:12px;}
  .table-head .th-title{font-weight:800;font-size:15px;}
  .table-filters{display:flex;gap:10px;flex-wrap:wrap;align-items:center;}
  .table-filters select,.table-filters input{border:1px solid var(--line);border-radius:8px;padding:9px 12px;font-size:12.5px;font-weight:600;background:#fff;font-family:inherit;}
  table{width:100%;border-collapse:collapse;font-size:13.5px;}
  th{text-align:left;color:var(--ink-soft);font-size:11.5px;font-weight:700;padding:10px 12px;border-bottom:1px solid var(--line);}
  td{padding:14px 12px;border-bottom:1px solid var(--line-soft);vertical-align:middle;}
  tr:last-child td{border-bottom:none;}
  .po-name{font-weight:700;}
  .po-code{font-size:11px;color:var(--ink-soft);}
  .detail-link{color:var(--green-700);font-weight:700;font-size:12.5px;}
  .status-pill{display:inline-flex;align-items:center;gap:6px;font-weight:700;font-size:12px;padding:5px 10px;border-radius:6px;background:var(--line-soft);color:var(--ink-soft);}
  .status-pill::before{content:"●";font-size:9px;}
  .pill-scheduled{background:#FBF4E0;color:#8A711F;} .pill-scheduled::before{color:#B79A2E;}
  .pill-boarding{background:var(--green-100);color:var(--green-700);} .pill-boarding::before{color:var(--green-600);}
  .pill-departed{background:var(--green-600);color:#fff;} .pill-departed::before{color:#fff;}
  .pill-completed{background:#E9ECEF;color:#5C6670;} .pill-completed::before{color:#9AA4AC;}
  .pill-cancelled{background:#FBE8E8;color:var(--red);} .pill-cancelled::before{color:var(--red);}
  .pill-paid{background:var(--green-100);color:var(--green-700);} .pill-paid::before{color:var(--green-600);}
  .pill-pending{background:#FBF4E0;color:#8A711F;} .pill-pending::before{color:#B79A2E;}
  .pill-expired{background:#FBE8E8;color:var(--red);} .pill-expired::before{color:var(--red);}

  .table-foot{display:flex;justify-content:space-between;align-items:center;margin-top:16px;font-size:12.5px;color:var(--ink-soft);flex-wrap:wrap;gap:10px;}

  .admin-form{max-width:840px;padding:26px;}
  .admin-form .field-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
  .admin-form .field{display:block;margin-bottom:16px;}
  .admin-form .field label{display:block;font-size:13px;font-weight:700;margin-bottom:7px;}
  .admin-form .field input,.admin-form .field select,.admin-form .field textarea{
    width:100%;padding:12px 14px;border:1.5px solid var(--line);border-radius:9px;font-size:14px;font-family:inherit;background:#fff;
  }
  .admin-form .field textarea{min-height:90px;resize:vertical;}
  .admin-form .check-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;}
  .admin-form .check-item{display:flex;align-items:center;gap:8px;border:1px solid var(--line);border-radius:8px;padding:11px 13px;font-size:13px;background:#fff;}
  .form-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:18px;}

  .menu-btn{display:none;align-items:center;justify-content:center;width:38px;height:38px;border-radius:9px;border:1px solid var(--line);background:#fff;font-size:16px;flex-shrink:0;cursor:pointer;}
  .sidebar-backdrop{display:none;}

  @media (max-width:1100px){.kpi-grid{grid-template-columns:repeat(2,1fr);}}
  @media (max-width:760px){
    .admin-shell{grid-template-columns:1fr;}
    .admin-side{position:fixed;top:0;left:0;bottom:0;width:230px;z-index:50;transform:translateX(-100%);transition:transform .2s ease;overflow-y:auto;}
    .admin-side.open{transform:translateX(0);}
    .admin-main{padding:18px;}
    .menu-btn{display:inline-flex;}
    .kpi-grid{grid-template-columns:1fr;}
    table{display:block;overflow-x:auto;white-space:nowrap;}
    .admin-form .field-row,.admin-form .check-grid{grid-template-columns:1fr;}
    .sidebar-backdrop.open{display:block;position:fixed;inset:0;background:rgba(0,0,0,.35);z-index:40;}
  }
</style>
</head>
<body>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>
<div class="admin-shell">

  <aside class="admin-side" id="adminSide">
    <div class="admin-brand"><span class="brand-mark">🚌</span>TiketBus <span style="font-size:11px;font-weight:600;opacity:.7;">ADMIN</span></div>
    <div class="admin-user">
      <div class="av">{{ auth()->user()->initials() }}</div>
      <div>
        <div class="name">{{ auth()->user()->name }}</div>
        <div class="role">{{ auth()->user()->email }}</div>
      </div>
    </div>
    <nav class="admin-nav">
      <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">▦ Dashboard</a>
      <a href="{{ route('admin.buses.index') }}" class="{{ request()->routeIs('admin.buses.*') ? 'active' : '' }}">🚌 Data Bus</a>
      <a href="{{ route('admin.schedules.index') }}" class="{{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">🕒 Jadwal</a>
      <a href="{{ route('admin.bookings.index') }}" class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">🎫 Pemesanan</a>
      <div class="nav-gap"></div>
      <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">⚙ Pengaturan</a>
      <a href="{{ route('admin.profile.index') }}" class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">🧑 Profil Admin</a>
      <div class="nav-gap"></div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout">⎋ Keluar</button>
      </form>
    </nav>
  </aside>

  <main class="admin-main">
    <div style="margin-bottom:14px;">
      <button class="menu-btn" id="menuBtn">☰</button>
    </div>

    @if (session('status'))
      <div class="flash flash-success" style="margin-bottom:18px;">{{ session('status') }}</div>
    @endif
    @if (session('error'))
      <div class="flash flash-error" style="margin-bottom:18px;">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
      <div class="flash flash-error" style="margin-bottom:18px;">
        <strong>Mohon periksa kembali isian Anda:</strong>
        <ul style="margin:6px 0 0 18px;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @yield('content')
  </main>
</div>

<script>
  (function () {
    var side = document.getElementById('adminSide');
    var backdrop = document.getElementById('sidebarBackdrop');
    var btn = document.getElementById('menuBtn');
    if (btn) {
      btn.addEventListener('click', function () {
        side.classList.toggle('open');
        backdrop.classList.toggle('open');
      });
    }
    if (backdrop) {
      backdrop.addEventListener('click', function () {
        side.classList.remove('open');
        backdrop.classList.remove('open');
      });
    }
  })();
</script>
</body>
</html>

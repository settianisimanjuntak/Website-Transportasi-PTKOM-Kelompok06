@extends("layouts.admin")

@section("title", "Dashboard Admin — TiketBus")

@section("content")
@php
  $statusLabels = [
    'scheduled' => 'On Schedule',
    'boarding' => 'Boarding',
    'departed' => 'Berangkat',
    'completed' => 'Selesai',
    'cancelled' => 'Dibatalkan',
  ];
  $total = $schedules->total();
@endphp

<style>
  .time-range{white-space:nowrap;}
  .route-cell{font-weight:600;}
</style>

<div class="admin-top">
  <div style="display:flex;align-items:center;gap:12px;">
    <form class="search-box" method="GET" action="{{ route('admin.dashboard') }}">
      @if ($filters['status'])<input type="hidden" name="status" value="{{ $filters['status'] }}">@endif
      @if ($filters['operator_id'])<input type="hidden" name="operator_id" value="{{ $filters['operator_id'] }}">@endif
      <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari rute, nomor bus, PO...">
    </form>
  </div>
  <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
    <span class="status-pill pill-boarding">Sistem Online</span>
    <a href="{{ route('admin.schedules.create') }}" class="btn-add">+ Tambah Jadwal Baru</a>
  </div>
</div>

<h1 class="page-h">Pusat Kendali Operasional</h1>
<div class="page-sub">Pemantauan armada antarkota antarprovinsi hari ini, {{ now()->locale('id')->translatedFormat('d F Y') }}</div>

<div class="kpi-grid">
  <div class="card kpi">
    <div class="lbl">Total Armada <span>🚌</span></div>
    <div class="val">{{ $kpis['fleet'] }} Bus</div>
    <div class="sub">Siap &amp; beroperasi hari ini</div>
  </div>
  <div class="card kpi">
    <div class="lbl">Okupansi Hari Ini <span>👥</span></div>
    <div class="val">{{ $kpis['occupancy'] }}%</div>
    <div class="sub">Ketersediaan bangku optimal</div>
  </div>
  <div class="card kpi">
    <div class="lbl">Penumpang Berangkat <span>🧑‍🤝‍🧑</span></div>
    <div class="val">{{ number_format($kpis['passengers']) }} Orang</div>
    <div class="sub">Keberangkatan terkonfirmasi</div>
  </div>
  <div class="card kpi">
    <div class="lbl">Status Ketepatan Waktu <span>⏱</span></div>
    <div class="val">{{ $kpis['onTime'] }}%</div>
    <div class="sub">Sesuai jadwal keberangkatan</div>
  </div>
</div>

<div class="card table-card">
  <div class="table-head">
    <div class="th-title">Menampilkan {{ $schedules->count() }} dari {{ $total }} keberangkatan</div>
    <form class="table-filters" method="GET" action="{{ route('admin.dashboard') }}">
      @if ($filters['q'])<input type="hidden" name="q" value="{{ $filters['q'] }}">@endif
      <select name="status" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        @foreach ($statusLabels as $value => $label)
          <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
        @endforeach
      </select>
      <select name="operator_id" onchange="this.form.submit()">
        <option value="">Semua Operator Bus</option>
        @foreach ($operators as $operator)
          <option value="{{ $operator->id }}" @selected((int) $filters['operator_id'] === $operator->id)>{{ $operator->name }}</option>
        @endforeach
      </select>
      <a href="{{ route('admin.dashboard') }}" class="btn-outline">⟳ Reset</a>
    </form>
  </div>

  <table>
    <thead>
      <tr><th>No</th><th>PO Bus</th><th>Rute</th><th>Jam</th><th>Kursi</th><th>Status</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @forelse ($schedules as $index => $schedule)
        <tr>
          <td>{{ str_pad($schedules->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}</td>
          <td>
            <div class="po-name">{{ $schedule->bus->operator->name }} <span class="tag tag-{{ $schedule->bus->busClass->badge }}">{{ $schedule->bus->busClass->name }}</span></div>
            <div class="po-code">{{ $schedule->bus->code }}</div>
          </td>
          <td class="route-cell">{{ $schedule->route->originCity->name }} → {{ $schedule->route->destinationCity->name }}</td>
          <td class="time-range">{{ time_short($schedule->departure_time) }} – {{ time_short($schedule->arrival_time) }} WIB</td>
          <td>{{ $schedule->booked_count }} / {{ $schedule->bus->capacity }} terisi</td>
          <td><span class="status-pill pill-{{ $schedule->status }}">{{ $statusLabels[$schedule->status] ?? $schedule->status }}</span></td>
          <td><a class="detail-link" href="{{ route('admin.schedules.show', $schedule) }}">Detail</a></td>
        </tr>
      @empty
        <tr><td colspan="7" style="text-align:center;color:var(--ink-soft);padding:32px;">Tidak ada jadwal hari ini yang cocok dengan filter.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="table-foot">
    <div>Halaman {{ $schedules->currentPage() }} dari {{ $schedules->lastPage() }}</div>
    {{ $schedules->links('partials.pagination') }}
  </div>
</div>
@endsection

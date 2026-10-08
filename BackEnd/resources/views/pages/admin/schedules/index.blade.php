@extends("layouts.admin")

@section("title", "Jadwal — Admin TiketBus")

@section("content")
@php
  $statusLabels = [
    'scheduled' => 'On Schedule',
    'boarding' => 'Boarding',
    'departed' => 'Berangkat',
    'completed' => 'Selesai',
    'cancelled' => 'Dibatalkan',
  ];
@endphp

<div class="admin-top">
  <form class="search-box" method="GET" action="{{ route('admin.schedules.index') }}">
    <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari kode bus / operator...">
  </form>
  <a href="{{ route('admin.schedules.create') }}" class="btn-add">+ Tambah Jadwal Baru</a>
</div>

<h1 class="page-h">Manajemen Jadwal Keberangkatan</h1>
<div class="page-sub">Semua jadwal rute antarkota beserta status, okupansi, dan detail perjalanan.</div>

<div class="card table-card">
  <div class="table-head">
    <div class="th-title">Menampilkan {{ $schedules->count() }} dari {{ $schedules->total() }} jadwal</div>
    <form class="table-filters" method="GET" action="{{ route('admin.schedules.index') }}">
      @if ($filters['q'])<input type="hidden" name="q" value="{{ $filters['q'] }}">@endif
      <input type="date" name="date" value="{{ $filters['date'] }}" onchange="this.form.submit()" title="Filter tanggal">
      <select name="status" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        @foreach ($statusLabels as $value => $label)
          <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
        @endforeach
      </select>
      <a href="{{ route('admin.schedules.index') }}" class="btn-outline">⟳ Reset</a>
    </form>
  </div>

  <table>
    <thead>
      <tr><th>Tanggal</th><th>PO Bus</th><th>Rute</th><th>Jam</th><th>Kursi</th><th>Tarif</th><th>Status</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @forelse ($schedules as $schedule)
        <tr>
          <td>{{ $schedule->service_date->locale('id')->translatedFormat('d M Y') }}</td>
          <td>
            <div class="po-name">{{ $schedule->bus->operator->name }} <span class="tag tag-{{ $schedule->bus->busClass->badge }}">{{ $schedule->bus->busClass->name }}</span></div>
            <div class="po-code">{{ $schedule->bus->code }}</div>
          </td>
          <td>{{ $schedule->route->originCity->name }} → {{ $schedule->route->destinationCity->name }}</td>
          <td>{{ time_short($schedule->departure_time) }} – {{ time_short($schedule->arrival_time) }} WIB</td>
          <td>{{ $schedule->booked_count }} / {{ $schedule->bus->capacity }}</td>
          <td>{{ rp($schedule->price) }}</td>
          <td><span class="status-pill pill-{{ $schedule->status }}">{{ $statusLabels[$schedule->status] ?? $schedule->status }}</span></td>
          <td style="white-space:nowrap;">
            <a class="detail-link" href="{{ route('admin.schedules.show', $schedule) }}">Detail</a>
            <a class="detail-link" href="{{ route('admin.schedules.edit', $schedule) }}" style="margin-left:8px;">Edit</a>
            <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" style="display:inline;margin-left:8px;" onsubmit="return confirm('Hapus jadwal ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="detail-link" style="background:none;border:none;color:var(--red);cursor:pointer;font-family:inherit;padding:0;">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="8" style="text-align:center;color:var(--ink-soft);padding:32px;">Tidak ada jadwal yang cocok dengan filter.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="table-foot">
    <div>Halaman {{ $schedules->currentPage() }} dari {{ $schedules->lastPage() }}</div>
    {{ $schedules->links('partials.pagination') }}
  </div>
</div>
@endsection

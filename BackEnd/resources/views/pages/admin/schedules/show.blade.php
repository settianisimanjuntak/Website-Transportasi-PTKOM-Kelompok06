@extends("layouts.admin")

@section("title", 'Detail Jadwal — Admin TiketBus')

@section("content")
@php
  $statusLabels = ['scheduled' => 'On Schedule', 'boarding' => 'Boarding', 'departed' => 'Berangkat', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];
  $depDate = $schedule->service_date;
  $arrDate = $schedule->service_date->copy()->addDays($schedule->arrival_day_offset);
@endphp

<style>
  .detail-grid{display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start;}
  .meta-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;}
  .meta-grid .lbl{font-size:11.5px;color:var(--ink-soft);margin-bottom:4px;}
  .meta-grid .val{font-weight:800;font-size:14px;}
  .stops-timeline{list-style:none;margin:0;padding:0;}
  .stops-timeline li{position:relative;padding:0 0 18px 26px;border-left:2px solid var(--line);}
  .stops-timeline li:last-child{border-left-color:transparent;padding-bottom:0;}
  .stops-timeline li::before{content:"";position:absolute;left:-7px;top:2px;width:12px;height:12px;border-radius:50%;background:var(--green-600);border:2px solid #fff;}
  .stops-timeline .st-name{font-weight:700;font-size:13.5px;}
  .stops-timeline .st-meta{font-size:12px;color:var(--ink-soft);margin-top:2px;}
  .seg{display:flex;justify-content:space-between;gap:14px;padding:14px 0;border-bottom:1px solid var(--line-soft);}
  .seg:last-child{border-bottom:none;}
  .seg .t{font-weight:800;font-size:14px;}
  .seg .s{font-size:12.5px;color:var(--ink-soft);margin-top:3px;}
  @media (max-width:900px){.detail-grid{grid-template-columns:1fr;} .meta-grid{grid-template-columns:1fr 1fr;}}
</style>

<div class="admin-top">
  <div style="display:flex;gap:14px;align-items:center;flex-wrap:wrap;">
    <a href="{{ route('admin.schedules.index') }}" class="detail-link">← Kembali ke Jadwal</a>
    <a href="{{ route('admin.schedules.edit', $schedule) }}" class="detail-link">Edit Jadwal</a>
  </div>
  <span class="status-pill pill-{{ $schedule->status }}">{{ $statusLabels[$schedule->status] ?? $schedule->status }}</span>
</div>

<h1 class="page-h">{{ $schedule->route->originCity->name }} → {{ $schedule->route->destinationCity->name }}</h1>
<div class="page-sub">
  {{ $depDate->locale('id')->translatedFormat('l, d F Y') }} · {{ time_short($schedule->departure_time) }} – {{ time_short($schedule->arrival_time) }} WIB ·
  {{ $schedule->durationText() }} · {{ $schedule->bus->operator->name }}
</div>

<div class="detail-grid">
  <div>
    <div class="card table-card" style="margin-bottom:20px;">
      <div class="table-head"><div class="th-title">Rincian Perjalanan</div></div>
      <div class="seg">
        <div>
          <div class="t">Keberangkatan {{ time_short($schedule->departure_time) }} WIB</div>
          <div class="s">{{ $schedule->departureTerminal->name }} ({{ $schedule->departureTerminal->city->name }}) · {{ $depDate->locale('id')->translatedFormat('d M Y') }}</div>
        </div>
        <div style="text-align:right;">
          <div class="t">{{ rp($schedule->price) }}</div>
          <div class="s">Tarif per kursi</div>
        </div>
      </div>
      <div class="seg">
        <div>
          <div class="t">Kedatangan {{ time_short($schedule->arrival_time) }} WIB{{ $schedule->arrival_day_offset > 0 ? ' (+1 hari)' : '' }}</div>
          <div class="s">{{ $schedule->arrivalTerminal->name }} ({{ $schedule->arrivalTerminal->city->name }}) · {{ $arrDate->locale('id')->translatedFormat('d M Y') }}</div>
        </div>
        <div style="text-align:right;">
          <div class="t">{{ $schedule->booked_count ?? $passengers->count() }} / {{ $schedule->bus->capacity }} kursi</div>
          <div class="s">Terisi · <a class="detail-link" href="{{ route('admin.schedules.show', $schedule) }}">Okupansi {{ round(($passengers->count() / max(1, $schedule->bus->capacity)) * 100) }}%</a></div>
        </div>
      </div>
      <div class="seg">
        <div>
          <div class="t">{{ $schedule->bus->code }} · {{ $schedule->bus->busClass->name }}</div>
          <div class="s">Layout {{ $schedule->bus->layout }} · {{ $schedule->bus->capacity }} kursi · {{ $schedule->bus->model ?: 'Standar' }}</div>
        </div>
        <div style="text-align:right;">
          <div class="t">{{ $schedule->stops->count() }} titik</div>
          <div class="s">Rencana perjalanan</div>
        </div>
      </div>
      @if ($schedule->notes)
        <div class="seg"><div><div class="t">Catatan</div><div class="s">{{ $schedule->notes }}</div></div></div>
      @endif
    </div>

    <div class="card table-card">
      <div class="table-head">
        <div class="th-title">Penumpang Terdaftar ({{ $passengers->count() }})</div>
      </div>
      <table>
        <thead>
          <tr><th>Kursi</th><th>Nama Penumpang</th><th>Booking</th><th>Kode Tiket</th><th>Status</th></tr>
        </thead>
        <tbody>
          @forelse ($passengers as $passenger)
            <tr>
              <td class="po-name">{{ $passenger->seat_no ?? '—' }}</td>
              <td>
                <div class="po-name">{{ $passenger->full_name }}</div>
                <div class="po-code">{{ $passenger->nik ?? 'NIK tidak terdaftar' }}</div>
              </td>
              <td>
                <a class="detail-link" href="{{ route('admin.bookings.show', $passenger->booking) }}">{{ $passenger->booking->code }}</a>
                <div class="po-code">{{ $passenger->booking->user?->name ?? $passenger->booking->contact_name }}</div>
              </td>
              <td>{{ $passenger->ticket?->code ?? '—' }}</td>
              <td>
                <span class="status-pill {{ $passenger->status === 'active' ? 'pill-paid' : 'pill-completed' }}">
                  {{ $passenger->status === 'active' ? 'Aktif' : 'Lepas' }}
                </span>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" style="text-align:center;color:var(--ink-soft);padding:32px;">Belum ada penumpang pada jadwal ini.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="card table-card">
    <div class="table-head"><div class="th-title">Titik Perjalanan</div></div>
    <ul class="stops-timeline">
      @foreach ($schedule->stops as $stop)
        <li>
          <div class="st-name">{{ $stop->name }} {{ $stop->is_terminal ? '<span class="tag tag-green">Terminal</span>' : '' }}</div>
          <div class="st-meta">
            {{ $stop->stop_time ? time_short($stop->stop_time) . ' WIB' : '—' }}
            @if ($stop->note) · {{ $stop->note }} @endif
          </div>
        </li>
      @endforeach
    </ul>
  </div>
</div>
@endsection

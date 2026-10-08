@extends("layouts.app")

@section("title", "E-Tiket Saya — TiketBus")

@section("content")
@php
  $schedule = $booking->schedule;
  $depDate = $schedule->service_date;
  $arrDate = $schedule->service_date->copy()->addDays($schedule->arrival_day_offset);
  $gateTime = date('H:i', strtotime($schedule->departure_time) - $boardingLead * 60);
  $duration = $schedule->durationText();
  $isDirect = $schedule->stops->count() <= 2;
  $photos = $schedule->bus->photo;
@endphp

<style>
  .page{padding:32px 0 60px;display:flex;flex-direction:column;align-items:center;gap:24px;}
  .ticket{width:100%;max-width:760px;border-radius:16px;overflow:hidden;box-shadow:var(--shadow);background:#fff;}
  .ticket-head{
    background:var(--green-700);color:#fff;padding:18px 24px;display:flex;justify-content:space-between;align-items:center;
  }
  .ticket-head .po{font-weight:800;font-size:15px;}
  .ticket-head .sub{font-size:11.5px;opacity:.85;margin-top:2px;}
  .status-pill{background:rgba(255,255,255,.16);padding:6px 14px;border-radius:999px;font-size:12px;font-weight:800;}
  .ticket-body{background:#fff;padding:26px 24px;display:grid;grid-template-columns:1fr 190px;gap:20px;}
  .booking-row{display:flex;justify-content:space-between;align-items:center;background:var(--green-50);
    border-radius:10px;padding:14px 16px;margin-bottom:20px;gap:10px;}
  .booking-row .code{font-size:20px;font-weight:800;color:var(--green-700);letter-spacing:.02em;}
  .booking-row .lbl{font-size:11px;color:var(--ink-soft);}
  .btn-tiny{background:#fff;border:1px solid var(--line);padding:8px 14px;border-radius:8px;font-size:12.5px;font-weight:700;color:var(--green-700);cursor:pointer;white-space:nowrap;}
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
  .qr-mini{width:130px;height:130px;margin:0 auto 10px;display:block;}
  .qr-side .lbl{font-size:11px;color:var(--ink-soft);margin-bottom:10px;line-height:1.5;}
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

<div class="container page">
  @foreach ($tickets as $ticket)
    <div class="ticket">
      <div class="ticket-head">
        <div>
          <div class="po">{{ $schedule->operator->name }} <span style="font-weight:600;opacity:.85;">· {{ $schedule->bus->busClass->name }}</span></div>
          <div class="sub">Armada {{ $schedule->bus->model ?: 'Standar' }} · Kode Bus {{ $schedule->bus->code }}</div>
        </div>
        <span class="status-pill">● LUNAS / CONFIRMED</span>
      </div>

      <div class="ticket-body">
        <div>
          <div class="booking-row">
            <div>
              <div class="lbl">Kode Booking (PNR) · Lunas &amp; Terkonfirmasi</div>
              <div class="code">{{ $booking->code }}</div>
            </div>
            <button type="button" class="btn-tiny" onclick="navigator.clipboard.writeText('{{ $booking->code }}').then(() => this.textContent='✓ Tersalin')">⧉ Salin PNR</button>
          </div>

          <div class="route-row">
            <div class="route-time">
              <div class="clock">{{ time_short($schedule->departure_time) }}</div>
              <div class="city">{{ terminal_short($schedule->departureTerminal->name) }} ({{ $schedule->departureTerminal->city->name }})</div>
              <div class="place">{{ $schedule->departureTerminal->name }}</div>
              <div class="place">{{ $depDate->locale('id')->translatedFormat('l, d F Y') }}</div>
            </div>
            <div class="route-mid">
              <div>🚌</div>
              <div class="line"></div>
              <div>{{ $duration }}</div>
            </div>
            <div class="route-time" style="text-align:right;">
              <div class="clock">{{ time_short($schedule->arrival_time) }}</div>
              <div class="city">{{ terminal_short($schedule->arrivalTerminal->name) }} ({{ $schedule->arrivalTerminal->city->name }})</div>
              <div class="place">{{ $schedule->arrivalTerminal->name }}</div>
              <div class="place">{{ $arrDate->locale('id')->translatedFormat('l, d F Y') }}</div>
            </div>
          </div>

          <div class="info-grid">
            <div><div class="lbl">Nama Penumpang</div><div class="val">{{ $ticket->passenger->full_name }}</div></div>
            <div><div class="lbl">Nomor Kursi</div><div class="val">🪑 {{ $ticket->passenger->seat_no }}</div></div>
            <div><div class="lbl">Total Tarif Lunas</div><div class="val" style="color:var(--green-700);">{{ rp($booking->total) }}</div></div>
          </div>

          <div class="lbl" style="font-size:11px;color:var(--ink-soft);margin-bottom:10px;">Fasilitas Armada Termasuk:</div>
          <div class="fac-pills">
            @foreach ($schedule->bus->facilities as $facility)
              <span class="fac-pill">{{ $facility->icon }} {{ $facility->name }}</span>
            @endforeach
          </div>
        </div>

        <div class="qr-side">
          <div class="lbl">Tiket Boarding Pass<br>Tunjukkan kode QR ini kepada petugas di terminal</div>
          <img class="qr-mini" src="{{ $qrImages[$ticket->id] }}" alt="QR {{ $ticket->code }}">
          <div class="pnr">{{ $ticket->code }}</div>
          <div class="gate-note">🕒 Pintu Dibuka: {{ $gateTime }} WIB<br>Harap tiba di peron minimal {{ $boardingLead }} menit sebelum keberangkatan armada.</div>
        </div>
      </div>

      <div class="ticket-actions">
        <a href="{{ route('tickets.pdf', $ticket) }}" class="btn btn-primary" style="flex:1;">⬇ Download PDF Tiket</a>
        <a href="{{ route('search.index') }}" class="btn btn-ghost" style="flex:1;text-align:center;">🏠 Kembali ke Beranda</a>
      </div>
    </div>
  @endforeach
</div>
@endsection

<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>E-Tiket {{ $ticket->code }}</title>
<style>
  * { box-sizing: border-box; }
  body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1A1E23; margin: 0; padding: 24px; }
  .head { background: #14532D; color: #fff; padding: 14px 18px; border-radius: 8px 8px 0 0; display: block; }
  .brand { font-size: 16px; font-weight: bold; }
  .brand span { color: #4ADE80; }
  .head .sub { font-size: 10px; opacity: .85; margin-top: 3px; }
  .body { border: 1px solid #DDE1DE; border-top: none; border-radius: 0 0 8px 8px; padding: 16px 18px; }
  .pnr { background: #E8F5EC; border-radius: 6px; padding: 10px 14px; margin-bottom: 14px; }
  .pnr .lbl { font-size: 9px; color: #5C6670; }
  .pnr .code { font-size: 20px; font-weight: bold; color: #14532D; letter-spacing: 1px; }
  .route { display: table; width: 100%; margin-bottom: 14px; }
  .route > div { display: table-cell; vertical-align: top; width: 40%; }
  .route .mid { width: 20%; text-align: center; font-size: 9px; color: #5C6670; padding-top: 6px; }
  .clock { font-size: 20px; font-weight: bold; }
  .city { font-weight: bold; font-size: 12px; margin-top: 2px; }
  .place { font-size: 9.5px; color: #5C6670; margin-top: 2px; }
  table.meta { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
  table.meta td { border-top: 1px dashed #DDE1DE; padding: 7px 4px; font-size: 10.5px; }
  table.meta td.lbl { color: #5C6670; width: 22%; }
  table.meta td.val { font-weight: bold; }
  .qr-wrap { text-align: center; border-top: 1px dashed #DDE1DE; padding-top: 14px; }
  .qr-wrap img { width: 130px; height: 130px; }
  .qr-code { font-size: 12px; font-weight: bold; letter-spacing: 2px; margin-top: 6px; }
  .gate { background: #E8F5EC; color: #14532D; border-radius: 6px; padding: 9px 12px; font-size: 10px; text-align: center; margin-top: 10px; }
  .footer { font-size: 8.5px; color: #5C6670; margin-top: 14px; line-height: 1.6; text-align: justify; }
  .badge { display: inline-block; background: #16A34A; color: #fff; font-size: 9px; font-weight: bold; padding: 3px 10px; border-radius: 10px; margin-left: 8px; vertical-align: middle; }
</style>
</head>
<body>

@php
  $depDate = $schedule->service_date;
  $arrDate = $schedule->service_date->copy()->addDays($schedule->arrival_day_offset);
  $gateTime = date('H:i', strtotime($schedule->departure_time) - $boardingLead * 60);
@endphp

<div class="head">
  <div class="brand">Tiket<span>Bus</span> — E-Tiket Resmi <span class="badge">LUNAS / CONFIRMED</span></div>
  <div class="sub">Tunjukkan tiket ini kepada petugas terminal · Berlaku sebagai bukti perjalanan</div>
</div>

<div class="body">

  <div class="pnr">
    <div class="lbl">KODE BOOKING (PNR)</div>
    <div class="code">{{ $booking->code }}</div>
  </div>

  <div class="route">
    <div>
      <div class="clock">{{ time_short($schedule->departure_time) }}</div>
      <div class="city">{{ $schedule->departureTerminal->city->name }}</div>
      <div class="place">{{ $schedule->departureTerminal->name }}</div>
      <div class="place">{{ $depDate->locale('id')->translatedFormat('l, d F Y') }}</div>
    </div>
    <div class="mid">
      <div>{{ $schedule->durationText() }}</div>
      <div>──────────────</div>
      <div>{{ $schedule->stops->count() <= 2 ? 'Langsung' : ($schedule->stops->count() - 2).' Transit' }}</div>
    </div>
    <div style="text-align:right;">
      <div class="clock">{{ time_short($schedule->arrival_time) }}</div>
      <div class="city">{{ $schedule->arrivalTerminal->city->name }}</div>
      <div class="place">{{ $schedule->arrivalTerminal->name }}</div>
      <div class="place">{{ $arrDate->locale('id')->translatedFormat('l, d F Y') }}</div>
    </div>
  </div>

  <table class="meta">
    <tr>
      <td class="lbl">Penumpang</td><td class="val">{{ $ticket->passenger->full_name }}</td>
      <td class="lbl">Nomor Kursi</td><td class="val">{{ $ticket->passenger->seat_no }}</td>
    </tr>
    <tr>
      <td class="lbl">Operator</td><td class="val">{{ $schedule->bus->operator->name }}</td>
      <td class="lbl">Kelas</td><td class="val">{{ $schedule->bus->busClass->name }}</td>
    </tr>
    <tr>
      <td class="lbl">Armada</td><td class="val">{{ $schedule->bus->code }} {{ $schedule->bus->model ? '· '.$schedule->bus->model : '' }}</td>
      <td class="lbl">Total Dibayar</td><td class="val">{{ rp($booking->total) }}</td>
    </tr>
    <tr>
      <td class="lbl">Fasilitas</td>
      <td colspan="3">{{ $schedule->bus->facilities->pluck('name')->implode(', ') ?: '—' }}</td>
    </tr>
  </table>

  <div class="qr-wrap">
    <img src="{{ $qrImage }}" alt="QR {{ $ticket->code }}">
    <div class="qr-code">{{ $ticket->code }}</div>
    <div class="gate">🕒 Pintu dibuka {{ $gateTime }} WIB · Hadir minimal {{ $boardingLead }} menit sebelum keberangkatan</div>
  </div>

  <div class="footer">
    Tiket ini bersifat pribadi dan tidak dapat dijual kembali. Penumpang wajib membawa identitas resmi (KTP/SIM) yang sesuai dengan data pemesanan untuk proses check-in.
    Perubahan jadwal atau pembatalan mengikuti kebijakan penyedia jasa angkutan. Diterbitkan oleh sistem TiketBus pada {{ now()->locale('id')->translatedFormat('d F Y, H:i') }} WIB.
  </div>
</div>

</body>
</html>

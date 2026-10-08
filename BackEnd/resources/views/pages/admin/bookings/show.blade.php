@extends("layouts.admin")

@section("title", 'Detail Booking — Admin TiketBus')

@section("content")
@php
  $statusLabels = ['pending' => 'Menunggu Bayar', 'paid' => 'Lunas', 'expired' => 'Kedaluwarsa', 'cancelled' => 'Dibatalkan'];
  $pillMap = ['pending' => 'pill-pending', 'paid' => 'pill-paid', 'expired' => 'pill-expired', 'cancelled' => 'pill-cancelled'];
  $payMethodLabels = ['qris' => 'QRIS', 'va' => 'Transfer Virtual Account', 'ewallet' => 'E-Wallet'];
  $schedule = $booking->schedule;
@endphp

<style>
  .detail-grid{display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start;}
  .meta-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;}
  .meta-grid .lbl{font-size:11.5px;color:var(--ink-soft);margin-bottom:4px;}
  .meta-grid .val{font-weight:800;font-size:14px;}
  .sum-row{display:flex;justify-content:space-between;font-size:13.5px;margin-bottom:10px;color:var(--ink-soft);gap:10px;}
  .sum-row span.v{color:var(--ink);font-weight:700;text-align:right;}
  .sum-total{display:flex;justify-content:space-between;align-items:baseline;border-top:1px solid var(--line);margin-top:12px;padding-top:12px;}
  .sum-total .val{font-size:20px;font-weight:800;color:var(--green-700);}
  @media (max-width:900px){.detail-grid{grid-template-columns:1fr;} .meta-grid{grid-template-columns:1fr 1fr;}}
</style>

<div class="admin-top">
  <div style="display:flex;gap:14px;align-items:center;flex-wrap:wrap;">
    <a href="{{ route('admin.bookings.index') }}" class="detail-link">← Kembali ke Pemesanan</a>
  </div>
  <span class="status-pill {{ $pillMap[$booking->status] ?? 'pill-completed' }}">{{ $statusLabels[$booking->status] ?? $booking->status }}</span>
</div>

<h1 class="page-h">Booking {{ $booking->code }}</h1>
<div class="page-sub">Dibuat {{ $booking->created_at->locale('id')->translatedFormat('d F Y, H:i') }} WIB · Berlaku bayar sampai {{ $booking->expires_at?->format('H:i') ?? '—' }} WIB</div>

<div class="detail-grid">
  <div>
    <div class="card table-card" style="margin-bottom:20px;">
      <div class="table-head"><div class="th-title">Data Perjalanan</div></div>
      <div class="meta-grid">
        <div><div class="lbl">Rute</div><div class="val">{{ $schedule->route->originCity->name }} → {{ $schedule->route->destinationCity->name }}</div></div>
        <div><div class="lbl">Operator</div><div class="val">{{ $schedule->bus->operator->name }}</div></div>
        <div><div class="lbl">Armada</div><div class="val">{{ $schedule->bus->code }} · {{ $schedule->bus->busClass->name }}</div></div>
        <div><div class="lbl">Berangkat</div><div class="val">{{ time_short($schedule->departure_time) }} WIB · {{ $schedule->service_date->locale('id')->translatedFormat('d M Y') }}</div></div>
        <div><div class="lbl">Terminal Asal</div><div class="val">{{ $schedule->departureTerminal->name }}</div></div>
        <div><div class="lbl">Terminal Tujuan</div><div class="val">{{ $schedule->arrivalTerminal->name }}</div></div>
      </div>
      <div style="margin-top:8px;">
        <a class="detail-link" href="{{ route('admin.schedules.show', $schedule) }}">Lihat detail jadwal →</a>
      </div>
    </div>

    <div class="card table-card">
      <div class="table-head"><div class="th-title">Penumpang ({{ $booking->passengers->count() }})</div></div>
      <table>
        <thead><tr><th>Kursi</th><th>Nama</th><th>NIK</th><th>Tiket</th><th>Status</th></tr></thead>
        <tbody>
          @foreach ($booking->passengers as $passenger)
            <tr>
              <td class="po-name">{{ $passenger->seat_no ?? '—' }}</td>
              <td>{{ $passenger->full_name }}</td>
              <td>{{ $passenger->nik ?? '—' }}</td>
              <td>{{ $passenger->ticket?->code ?? 'Belum terbit' }}</td>
              <td>
                <span class="status-pill {{ $passenger->status === 'active' ? 'pill-paid' : 'pill-completed' }}">
                  {{ $passenger->status === 'active' ? 'Aktif' : 'Lepas' }}
                </span>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div>
    <div class="card table-card" style="margin-bottom:20px;">
      <div class="table-head"><div class="th-title">Data Pemesan</div></div>
      <div class="sum-row"><span>Nama</span><span class="v">{{ $booking->user?->name ?? $booking->contact_name }}</span></div>
      <div class="sum-row"><span>Email</span><span class="v">{{ $booking->contact_email }}</span></div>
      <div class="sum-row"><span>No. HP</span><span class="v">{{ $booking->contact_phone }}</span></div>
      @if ($booking->user)
        <div class="sum-row"><span>Akun</span><span class="v">{{ $booking->user->email }}</span></div>
      @endif
    </div>

    <div class="card table-card" style="margin-bottom:20px;">
      <div class="table-head"><div class="th-title">Pembayaran</div></div>
      <div class="sum-row"><span>Metode</span><span class="v">{{ $payMethodLabels[$booking->payment?->method] ?? '—' }}</span></div>
      <div class="sum-row"><span>Ref. Pembayaran</span><span class="v">{{ $booking->payment?->reference ?? '—' }}</span></div>
      <div class="sum-row"><span>Status</span><span class="v">{{ $booking->payment?->status ?? '—' }}</span></div>
      <div class="sum-row"><span>Tarif Tiket</span><span class="v">{{ rp($booking->subtotal) }}</span></div>
      <div class="sum-row"><span>Asuransi Jasa Raharja</span><span class="v">{{ rp($booking->insurance_fee) }}</span></div>
      <div class="sum-row"><span>Proteksi</span><span class="v">{{ $booking->protection_fee > 0 ? rp($booking->protection_fee) : '—' }}</span></div>
      <div class="sum-row"><span>Biaya Layanan</span><span class="v">{{ $booking->service_fee > 0 ? rp($booking->service_fee) : 'Gratis' }}</span></div>
      <div class="sum-total"><span style="font-weight:700;color:var(--ink);">Total</span><span class="val">{{ rp($booking->total) }}</span></div>

      @if ($booking->status === 'pending')
        <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}" style="margin-top:16px;"
              onsubmit="return confirm('Batalkan booking {{ $booking->code }}? Kursi akan dilepas kembali.')">
          @csrf
          <button type="submit" class="btn btn-primary btn-block" style="background:var(--red);">✕ Batalkan Booking</button>
        </form>
      @endif
    </div>

    @if ($booking->status === 'paid')
      <div class="card table-card">
        <div class="table-head"><div class="th-title">Tiket Terbit</div></div>
        <div class="sum-row"><span>Kode Tiket</span><span class="v">{{ $booking->passengers->first()?->ticket?->code ?? '—' }}</span></div>
        <a href="{{ route('tickets.index', $booking) }}" class="detail-link">Buka halaman e-tiket →</a>
      </div>
    @endif
  </div>
</div>
@endsection

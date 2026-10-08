@extends("layouts.admin")

@section("title", "Pemesanan — Admin TiketBus")

@section("content")
@php
  $statusLabels = ['pending' => 'Menunggu Bayar', 'paid' => 'Lunas', 'expired' => 'Kedaluwarsa', 'cancelled' => 'Dibatalkan'];
  $pillMap = ['pending' => 'pill-pending', 'paid' => 'pill-paid', 'expired' => 'pill-expired', 'cancelled' => 'pill-cancelled'];
@endphp

<div class="admin-top">
  <form class="search-box" method="GET" action="{{ route('admin.bookings.index') }}">
    <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari kode booking / nama / no. HP...">
  </form>
</div>

<h1 class="page-h">Data Pemesanan</h1>
<div class="page-sub">Semua transaksi pemesanan tiket beserta status pembayaran dan penumpang.</div>

<div class="card table-card">
  <div class="table-head">
    <div class="th-title">Menampilkan {{ $bookings->count() }} dari {{ $bookings->total() }} pemesanan</div>
    <form class="table-filters" method="GET" action="{{ route('admin.bookings.index') }}">
      @if ($filters['q'])<input type="hidden" name="q" value="{{ $filters['q'] }}">@endif
      <select name="status" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        @foreach ($statusLabels as $value => $label)
          <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
        @endforeach
      </select>
      <a href="{{ route('admin.bookings.index') }}" class="btn-outline">⟳ Reset</a>
    </form>
  </div>

  <table>
    <thead>
      <tr><th>Kode Booking</th><th>Pemesan</th><th>Rute</th><th>Tanggal</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @forelse ($bookings as $booking)
        <tr>
          <td class="po-name">{{ $booking->code }}</td>
          <td>
            <div class="po-name">{{ $booking->user?->name ?? $booking->contact_name }}</div>
            <div class="po-code">{{ $booking->contact_phone }}</div>
          </td>
          <td>
            <div class="po-name">{{ $booking->schedule->route->originCity->name }} → {{ $booking->schedule->route->destinationCity->name }}</div>
            <div class="po-code">{{ $booking->schedule->bus->operator->name }}</div>
          </td>
          <td>{{ $booking->schedule->service_date->locale('id')->translatedFormat('d M Y') }}</td>
          <td>{{ rp($booking->total) }}</td>
          <td><span class="status-pill {{ $pillMap[$booking->status] ?? 'pill-completed' }}">{{ $statusLabels[$booking->status] ?? $booking->status }}</span></td>
          <td><a class="detail-link" href="{{ route('admin.bookings.show', $booking) }}">Detail</a></td>
        </tr>
      @empty
        <tr><td colspan="7" style="text-align:center;color:var(--ink-soft);padding:32px;">Tidak ada pemesanan yang cocok dengan filter.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="table-foot">
    <div>Halaman {{ $bookings->currentPage() }} dari {{ $bookings->lastPage() }}</div>
    {{ $bookings->links('partials.pagination') }}
  </div>
</div>
@endsection

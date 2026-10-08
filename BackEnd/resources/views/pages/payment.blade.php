@extends("layouts.app")

@section("title", "Pembayaran — TiketBus")

@section("content")
<style>
  .pay-wrap{min-height:calc(100vh - 72px);display:flex;align-items:center;justify-content:center;padding:48px 24px;}
  .pay-card{width:100%;max-width:420px;padding:28px;text-align:center;}
  .pay-card .amount{font-size:26px;font-weight:800;color:var(--green-700);margin:6px 0 18px;}
  .qr-poster{
    position:relative;border:1.5px solid var(--line);border-radius:14px;padding:22px 20px 18px;margin-bottom:18px;
    overflow:hidden;background:#fff;
  }
  .qr-poster::before{
    content:"";position:absolute;top:-40px;left:-40px;width:110px;height:110px;
    background:#D14343;transform:rotate(45deg);
  }
  .qr-poster-head{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;position:relative;z-index:1;}
  .qr-poster-head .qlabel{font-size:11px;font-weight:800;color:var(--ink);line-height:1.2;text-align:left;}
  .qr-poster-head .qlabel span{display:block;font-size:9px;font-weight:600;color:var(--ink-soft);}
  .qr-poster-head .gpn{
    background:var(--red);color:#fff;font-size:11px;font-weight:800;padding:4px 10px;border-radius:5px;
  }
  .qr-brand-row{text-align:center;font-size:13px;font-weight:800;color:var(--ink);margin:10px 0 2px;}
  .qr-nmid{text-align:center;font-size:10.5px;color:var(--ink-soft);margin-bottom:4px;}
  .qr-img{width:190px;height:190px;margin:10px auto 12px;display:block;}
  .qr-tag{text-align:center;font-size:10px;font-weight:700;color:var(--ink-soft);margin-top:10px;letter-spacing:.03em;}
  .method-box{border:1.5px solid var(--line);border-radius:14px;padding:20px;margin-bottom:18px;background:#fff;text-align:left;}
  .method-box .lbl{font-size:11.5px;color:var(--ink-soft);font-weight:700;text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px;}
  .method-box .big{font-size:20px;font-weight:800;color:var(--green-700);letter-spacing:.02em;word-break:break-all;}
  .method-box .banks{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px;}
  .method-box .banks span{border:1px solid var(--line);border-radius:8px;padding:6px 12px;font-size:12px;font-weight:700;color:var(--ink-soft);}
  .timer{font-size:13px;color:var(--red);font-weight:700;margin-bottom:14px;}
  .status-badge{display:inline-flex;align-items:center;gap:8px;padding:12px 22px;border-radius:10px;font-size:15px;font-weight:800;margin-top:6px;}
  .status-waiting{background:#FDF3E3;color:#966016;}
  .status-success{background:var(--green-100);color:var(--green-700);}
  .status-expired{background:#FBE8E8;color:var(--red);}
  .booking-meta{font-size:12.5px;color:var(--ink-soft);margin-bottom:16px;line-height:1.6;}
  .booking-meta b{color:var(--ink);}
  .mock-note{font-size:11px;color:var(--ink-soft);margin-top:10px;line-height:1.5;}
  @media (max-width:640px){
    .pay-wrap{padding:24px 16px;}
    .pay-card{padding:20px;}
    .qr-img{width:170px;height:170px;}
  }
</style>

<div class="pay-wrap">
  <div class="card pay-card" id="payCard"
       data-status-url="{{ route('payment.status', $booking) }}"
       data-tickets-url="{{ route('tickets.index', $booking) }}"
       data-seconds="{{ $secondsLeft }}">

    <div class="booking-meta">
      Kode Booking: <b>{{ $booking->code }}</b><br>
      {{ $booking->schedule->operator->name }} ·
      {{ $booking->schedule->route->originCity->name }} → {{ $booking->schedule->route->destinationCity->name }} ·
      {{ $booking->schedule->service_date->locale('id')->translatedFormat('d M Y') }}
    </div>

    <div class="muted" style="font-size:13px;">Total Pembayaran</div>
    <div class="amount">{{ rp($booking->total) }}</div>

    @if ($payment && $payment->method === 'qris')
      <div class="qr-poster">
        <div class="qr-poster-head">
          <div class="qlabel">QRIS<span>QR Code Standar<br>Pembayaran Nasional</span></div>
          <div class="gpn">GPN</div>
        </div>
        <div class="qr-brand-row">TIKET BUS</div>
        <div class="qr-nmid">REF : {{ $payment->reference }}</div>
        @if ($qrImage)
          <img class="qr-img" src="{{ $qrImage }}" alt="QRIS">
        @endif
        <div class="qr-tag">SATU QRIS UNTUK SEMUA</div>
        <div class="mock-note" style="text-align:center;">Scan dengan aplikasi bank/e-wallet apa pun (simulasi).</div>
      </div>
    @elseif ($payment && $payment->method === 'va')
      <div class="method-box">
        <div class="lbl">Virtual Account</div>
        <div class="big">{{ $payment->reference }}</div>
        <div class="banks">
          @foreach ($banks as $bank)
            <span>{{ $bank }}</span>
          @endforeach
        </div>
        <div class="mock-note">Transfer tepat sejumlah total pembayaran. Nomor VA otomatis terverifikasi oleh sistem (simulasi).</div>
      </div>
    @elseif ($payment)
      <div class="method-box">
        <div class="lbl">E-Wallet</div>
        <div class="big">{{ $payment->reference }}</div>
        <div class="banks">
          @foreach ($ewallets as $wallet)
            <span>{{ $wallet }}</span>
          @endforeach
        </div>
        <div class="mock-note">Buka aplikasi e-wallet Anda dan bayar dengan kode di atas (simulasi).</div>
      </div>
    @endif

    @if ($booking->status === 'pending')
      <div class="timer" id="timer">⏱ Selesaikan dalam --:--</div>
      <span class="status-badge status-waiting">⏳ Menunggu Pembayaran</span>

      <form method="POST" action="{{ route('payment.simulate', $booking) }}" style="margin-top:20px;">
        @csrf
        <button type="submit" class="btn btn-primary btn-block">✓ Bayar Sekarang (Simulasi Gateway)</button>
      </form>
      <div class="mock-note">Pada produksi, tombol ini digantikan redirect ke payment gateway (Midtrans/Xendit) dan webhook konfirmasi otomatis.</div>
    @elseif ($booking->status === 'expired')
      <span class="status-badge status-expired">✕ Pembayaran Kedaluwarsa</span>
      <div class="mock-note" style="margin-top:12px;">Waktu pembayaran habis. Kursi Anda telah dilepas kembali.</div>
      <a href="{{ route('schedules.seat', $booking->schedule) }}" class="btn btn-primary btn-block" style="margin-top:18px;">Pesan Ulang Kursi</a>
    @else
      <span class="status-badge status-success">✓ Pembayaran Sukses</span>
      <a href="{{ route('tickets.index', $booking) }}" class="btn btn-primary btn-block" style="margin-top:22px;">Lihat E-Tiket Saya</a>
    @endif
  </div>
</div>

<script>
  (function () {
    const card = document.getElementById('payCard');
    const statusUrl = card.dataset.statusUrl;
    const ticketsUrl = card.dataset.ticketsUrl;
    let seconds = parseInt(card.dataset.seconds, 10) || 0;
    const timerEl = document.getElementById('timer');

    function pad(n) { return (n < 10 ? '0' : '') + n; }

    function renderTimer() {
      if (!timerEl) return;
      if (seconds <= 0) {
        timerEl.textContent = '⏱ Waktu pembayaran habis';
        return;
      }
      const m = Math.floor(seconds / 60);
      const s = seconds % 60;
      timerEl.textContent = '⏱ Selesaikan dalam ' + pad(m) + ':' + pad(s);
    }

    renderTimer();

    setInterval(function () {
      if (seconds > 0) {
        seconds--;
        renderTimer();
        if (seconds === 0) { location.reload(); }
      }
    }, 1000);

    if (timerEl) {
      setInterval(function () {
        fetch(statusUrl, { headers: { 'Accept': 'application/json' } })
          .then(function (r) { return r.json(); })
          .then(function (data) {
            if (data.booking_status === 'paid') {
              window.location = ticketsUrl;
            } else if (data.booking_status === 'expired') {
              location.reload();
            }
          })
          .catch(function () {});
      }, 4000);
    }
  })();
</script>
@endsection

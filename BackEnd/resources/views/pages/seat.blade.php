@extends("layouts.app")

@section("title", "Pilih Kursi & Bayar — TiketBus")

@section("content")
@php
  $parts = array_map('intval', explode('-', $schedule->bus->layout));
  $leftCount = $parts[0];
  $rightCount = max(1, array_sum($parts) - $leftCount);
  $rows = $seats->groupBy('seat_row');
  $bookedSet = array_flip($booked);
  $defaultName = old('full_name', $user->name);
  $defaultNik = old('nik', $user->nik);
@endphp

<style>
  .page{padding:28px 0 60px;}
  .layout{display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start;}
  .block{padding:22px 24px;margin-bottom:18px;}
  .checkbox-row{display:flex;align-items:center;gap:8px;font-size:13.5px;color:var(--ink-soft);}

  .bus-frame{
    max-width:320px;margin:0 auto;border:1.5px solid var(--line);border-radius:16px;padding:20px 22px 26px;background:var(--green-50);
  }
  .bus-frame-top{display:flex;justify-content:space-between;font-size:11.5px;color:var(--ink-soft);font-weight:700;margin-bottom:14px;}
  .seat-grid{display:grid;gap:10px;justify-content:center;}
  .aisle{visibility:hidden;}
  .legend{display:flex;gap:18px;justify-content:center;margin-top:18px;font-size:12px;color:var(--ink-soft);}
  .legend span{display:inline-flex;align-items:center;gap:6px;}
  .dot{width:12px;height:12px;border-radius:4px;display:inline-block;}
  .dot.av{background:#fff;border:1.5px solid var(--line);}
  .dot.sel{background:var(--green-600);}
  .dot.tk{background:#EDEFED;}
  .seat-picked{text-align:center;margin-top:16px;font-size:13.5px;}
  .seat-picked b{color:var(--green-700);}

  .pay-methods{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;}
  .pay-opt{border:1.5px solid var(--line);border-radius:10px;padding:14px;cursor:pointer;font-size:13px;display:block;position:relative;background:#fff;}
  .pay-opt.selected{border-color:var(--green-600);background:var(--green-50);}
  .pay-opt input{position:absolute;opacity:0;pointer-events:none;}
  .pay-opt .top{display:flex;justify-content:space-between;font-weight:800;margin-bottom:6px;}
  .pay-opt .sub{color:var(--ink-soft);font-size:11.5px;}

  .toggle{position:relative;width:42px;height:24px;flex-shrink:0;}
  .toggle input{opacity:0;width:0;height:0;}
  .toggle .slider{position:absolute;inset:0;background:var(--line);border-radius:999px;transition:.15s;}
  .toggle .slider::before{content:"";position:absolute;width:18px;height:18px;left:3px;top:3px;background:#fff;border-radius:50%;transition:.15s;}
  .toggle input:checked + .slider{background:var(--green-600);}
  .toggle input:checked + .slider::before{transform:translateX(18px);}
  .protect-row{display:flex;justify-content:space-between;align-items:center;padding:16px;border:1px solid var(--line-soft);border-radius:10px;}
  .protect-row .h{font-weight:800;font-size:13.5px;}
  .protect-row .d{font-size:12px;color:var(--ink-soft);margin-top:4px;line-height:1.5;max-width:420px;}

  .summary{position:sticky;top:88px;padding:22px;}
  .sum-row{display:flex;justify-content:space-between;font-size:13.5px;margin-bottom:10px;color:var(--ink-soft);gap:8px;}
  .sum-row span.v{color:var(--ink);font-weight:700;text-align:right;}
  .sum-total{display:flex;justify-content:space-between;align-items:baseline;border-top:1px solid var(--line);margin-top:14px;padding-top:14px;}
  .sum-total .val{font-size:22px;font-weight:800;color:var(--green-700);}
  @media (max-width:900px){.layout{grid-template-columns:1fr;} .pay-methods{grid-template-columns:1fr;}}
  @media (max-width:640px){
    .block{padding:16px;}
    .field-row{flex-direction:column;gap:0;}
    .bus-frame{max-width:100%;padding:16px 14px 20px;}
    .seat{width:38px;height:38px;}
    .summary{position:static;}
  }
</style>

<div class="container page">
  <form method="POST" action="{{ route('bookings.store', $schedule) }}" id="bookingForm">
    @csrf
    <input type="hidden" name="seat_no" id="seatNoInput" value="{{ old('seat_no') }}">

    <div class="layout">
      <div>
        <div class="card block">
          <h3 class="section-title">Data Kontak Pemesan</h3>
          <p class="muted" style="font-size:12.5px;margin-top:-8px;">E-tiket dan kode booking akan dikirimkan ke nomor WhatsApp dan email di bawah ini.</p>
          <div class="field">
            <label>Nama Lengkap Pemesan</label>
            <input type="text" name="contact_name" value="{{ old('contact_name', $user->name) }}" required>
          </div>
          <div class="field-row">
            <div class="field">
              <label>Nomor HP / WhatsApp</label>
              <input type="tel" name="contact_phone" value="{{ old('contact_phone', $user->phone) }}" placeholder="+62 812 3456 7890" required>
            </div>
            <div class="field">
              <label>Alamat Email</label>
              <input type="email" name="contact_email" value="{{ old('contact_email', $user->email) }}" required>
            </div>
          </div>
          <label class="checkbox-row">
            <input type="checkbox" name="is_self" value="1" {{ old('is_self', 1) ? 'checked' : '' }} id="isSelf"> Saya juga merupakan salah satu penumpang
          </label>
        </div>

        <div class="card block">
          <h3 class="section-title">Pilih Kursi Bus <span style="float:right;font-weight:600;color:var(--ink-soft);">Format {{ $schedule->bus->layout }}</span></h3>
          <div class="bus-frame">
            <div class="bus-frame-top"><span>🚪 Pintu Depan</span><span>🧑‍✈️ Supir</span></div>
            <div class="seat-grid" id="seatGrid" style="grid-template-columns:repeat({{ $leftCount }},44px) 26px repeat({{ $rightCount }},44px);">
              @foreach ($rows as $row => $rowSeats)
                @php
                  $leftSeats = $rowSeats->take($leftCount)->values();
                  $rightSeats = $rowSeats->skip($leftCount)->values();
                @endphp
                @for ($i = $leftSeats->count(); $i < $leftCount; $i++)
                  <div></div>
                @endfor
                @foreach ($leftSeats as $seat)
                  @php $taken = isset($bookedSet[$seat->seat_no]); @endphp
                  <div class="seat {{ $taken ? 'taken' : 'available' }}"
                       data-seat="{{ $seat->seat_no }}"
                       @if(!$taken) data-window="{{ $seat->is_window ? 1 : 0 }}" @endif>{{ $seat->seat_no }}</div>
                @endforeach
                <div class="seat aisle">·</div>
                @for ($i = $rightSeats->count(); $i < $rightCount; $i++)
                  <div></div>
                @endfor
                @foreach ($rightSeats as $seat)
                  @php $taken = isset($bookedSet[$seat->seat_no]); @endphp
                  <div class="seat {{ $taken ? 'taken' : 'available' }}"
                       data-seat="{{ $seat->seat_no }}"
                       @if(!$taken) data-window="{{ $seat->is_window ? 1 : 0 }}" @endif>{{ $seat->seat_no }}</div>
                @endforeach
              @endforeach
            </div>
            <div class="legend">
              <span><i class="dot av"></i>Tersedia</span>
              <span><i class="dot sel"></i>Terpilih</span>
              <span><i class="dot tk"></i>Terisi</span>
            </div>
            <div style="text-align:center;font-size:11px;color:var(--ink-soft);margin-top:14px;">🚻 Toilet · Belakang Bus</div>
          </div>
          <div class="seat-picked">Kursi yang dipilih: <b id="seatLabel">—</b></div>
        </div>

        <div class="card block">
          <h3 class="section-title">Data Penumpang 1 <span style="float:right;font-weight:600;color:var(--green-700);" id="seatBadge">Kursi —</span></h3>
          <div class="field-row">
            <div class="field">
              <label>Nama Lengkap Penumpang</label>
              <input type="text" name="full_name" id="passengerName" value="{{ $defaultName }}" required>
            </div>
            <div class="field">
              <label>Nomor Induk Kependudukan (NIK)</label>
              <input type="text" name="nik" id="passengerNik" value="{{ $defaultNik }}" maxlength="16" pattern="[0-9]{16}" title="NIK harus 16 digit angka" placeholder="3273152004890002" required>
            </div>
          </div>
          <p class="muted" style="font-size:12px;margin:0;">Pastikan nama dan NIK sesuai dokumen identitas resmi untuk proses boarding.</p>
        </div>

        @if ($protectionEnabled)
          <div class="card block">
            <div class="protect-row">
              <div>
                <div class="h">Perlindungan Perjalanan Aman <span class="muted" style="font-weight:600;">+{{ rp($protectionFee) }}</span></div>
                <div class="d">Santunan keterlambatan armada lebih dari 90 menit (Rp 50.000), serta kompensasi bagasi hilang atau rusak hingga Rp 1.500.000, dan proteksi kecelakaan perjalanan.</div>
              </div>
              <label class="toggle">
                <input type="checkbox" name="protection" value="1" id="protectionToggle" {{ old('protection', 1) ? 'checked' : '' }}>
                <span class="slider"></span>
              </label>
            </div>
          </div>
        @endif

        <div class="card block">
          <h3 class="section-title">Metode Pembayaran Instan</h3>
          <div class="pay-methods">
            <label class="pay-opt selected">
              <input type="radio" name="payment_method" value="qris" {{ old('payment_method', 'qris') === 'qris' ? 'checked' : '' }}>
              <div class="top">QRIS <span>●</span></div>
              <div class="sub">BCA, Mandiri, GoPay, OVO</div>
            </label>
            <label class="pay-opt">
              <input type="radio" name="payment_method" value="va" {{ old('payment_method') === 'va' ? 'checked' : '' }}>
              <div class="top">Transfer VA <span></span></div>
              <div class="sub">BCA, BRI, Mandiri, BNI</div>
            </label>
            <label class="pay-opt">
              <input type="radio" name="payment_method" value="ewallet" {{ old('payment_method') === 'ewallet' ? 'checked' : '' }}>
              <div class="top">E-Wallet <span></span></div>
              <div class="sub">ShopeePay, DANA</div>
            </label>
          </div>
        </div>
      </div>

      <div>
        <div class="card summary">
          <div style="margin-bottom:16px;">
            <span class="tag tag-{{ $schedule->bus->busClass->badge }}">{{ $schedule->bus->busClass->name }}</span>
            <div style="font-weight:800;font-size:16px;margin-top:8px;">{{ $schedule->operator->name }}</div>
          </div>
          <div class="sum-row">
            <span>{{ time_short($schedule->departure_time) }} WIB</span>
            <span class="v">{{ $schedule->service_date->locale('id')->translatedFormat('D, d M Y') }}</span>
          </div>
          <div class="sum-row" style="margin-bottom:14px;"><span>{{ $schedule->departureTerminal->name }}</span></div>
          <div class="sum-row">
            <span>{{ time_short($schedule->arrival_time) }} WIB{{ $schedule->arrival_day_offset > 0 ? ' (+1 hari)' : '' }}</span>
            <span class="v">{{ $schedule->arrivalTerminal->name }}</span>
          </div>
          <div style="border-top:1px solid var(--line);margin:14px 0;"></div>
          <div class="sum-row"><span>Nomor Kursi</span><span class="v" id="seatSummary">Belum dipilih</span></div>
          <div class="sum-row"><span>Tarif Tiket Bus (1 Penumpang)</span><span class="v">{{ rp($schedule->price) }}</span></div>
          <div class="sum-row"><span>Asuransi Jasa Raharja</span><span class="v">{{ rp($insuranceFee) }}</span></div>
          @if ($protectionEnabled)
            <div class="sum-row" id="protectionRow" style="{{ old('protection', 1) ? '' : 'display:none;' }}">
              <span>Perlindungan Perjalanan</span><span class="v">{{ rp($protectionFee) }}</span>
            </div>
          @endif
          <div class="sum-row"><span>Biaya Layanan Sistem</span><span class="v">{{ $serviceFee > 0 ? rp($serviceFee) : 'Gratis' }}</span></div>
          <div class="sum-total">
            <div>
              <div class="muted" style="font-size:13px;">Total Pembayaran</div>
              <div class="val" id="totalValue">{{ rp($baseTotal + ($protectionEnabled && old('protection', 1) ? $protectionFee : 0)) }}</div>
            </div>
            <span class="tag tag-green">Termasuk Pajak</span>
          </div>
          <button type="submit" class="btn btn-primary btn-block" style="margin-top:16px;">Lanjut Pembayaran →</button>
          <div class="muted" style="font-size:12px;text-align:center;margin-top:10px;">🔒 Transaksi terenkripsi dan aman 100%</div>
        </div>

        <div class="card" style="padding:16px 18px;margin-top:16px;display:flex;align-items:center;gap:12px;">
          <span style="font-size:20px;">📞</span>
          <div style="font-size:12.5px;">
            <div style="font-weight:700;">Butuh bantuan formulir?</div>
            <div class="muted">Layanan CS TiketBus siap 24 jam melalui WhatsApp.</div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
  (function () {
    const seatInput = document.getElementById('seatNoInput');
    const seatLabel = document.getElementById('seatLabel');
    const seatBadge = document.getElementById('seatBadge');
    const seatSummary = document.getElementById('seatSummary');
    const form = document.getElementById('bookingForm');
    const baseTotal = {{ $baseTotal }};
    const protectionFee = {{ $protectionFee }};

    function selectSeat(el) {
      document.querySelectorAll('.seat.selected').forEach(function (s) {
        s.classList.remove('selected');
        s.classList.add('available');
      });
      el.classList.add('selected');
      el.classList.remove('available');
      seatInput.value = el.dataset.seat;
      const suffix = el.dataset.window === '1' ? ' (Jendela)' : ' (Lorong)';
      seatLabel.textContent = el.dataset.seat + suffix;
      seatBadge.textContent = 'Kursi ' + el.dataset.seat;
      seatSummary.textContent = el.dataset.seat + suffix;
    }

    document.querySelectorAll('.seat.available').forEach(function (el) {
      el.addEventListener('click', function () { selectSeat(el); });
    });

    if (seatInput.value) {
      const preset = document.querySelector('.seat[data-seat="' + seatInput.value + '"].available');
      if (preset) selectSeat(preset);
    }

    // Payment method styling
    document.querySelectorAll('.pay-opt').forEach(function (opt) {
      const radio = opt.querySelector('input[type=radio]');
      if (radio && radio.checked) opt.classList.add('selected');
      opt.addEventListener('click', function () {
        document.querySelectorAll('.pay-opt').forEach(function (o) { o.classList.remove('selected'); });
        opt.classList.add('selected');
        if (radio) radio.checked = true;
      });
    });

    // Protection toggle → total
    const toggle = document.getElementById('protectionToggle');
    const protectRow = document.getElementById('protectionRow');
    const totalValue = document.getElementById('totalValue');

    function fmt(n) {
      return 'Rp ' + n.toLocaleString('id-ID');
    }

    function recalc() {
      const on = toggle && toggle.checked;
      if (protectRow) protectRow.style.display = on ? '' : 'none';
      totalValue.textContent = fmt(baseTotal + (on ? protectionFee : 0));
    }

    if (toggle) {
      toggle.addEventListener('change', recalc);
      recalc();
    }

    // "Saya juga penumpang" → isi otomatis data penumpang
    const isSelf = document.getElementById('isSelf');
    const contactName = form.querySelector('[name=contact_name]');
    const passengerName = document.getElementById('passengerName');
    const contactEmail = form.querySelector('[name=contact_email]');

    if (isSelf) {
      isSelf.addEventListener('change', function () {
        if (isSelf.checked) passengerName.value = contactName.value;
      });
      contactName.addEventListener('input', function () {
        if (isSelf.checked) passengerName.value = contactName.value;
      });
    }

    form.addEventListener('submit', function (e) {
      if (!seatInput.value) {
        e.preventDefault();
        alert('Silakan pilih kursi terlebih dahulu.');
        return false;
      }
      const nik = document.getElementById('passengerNik');
      if (nik && !/^[0-9]{16}$/.test(nik.value)) {
        e.preventDefault();
        alert('NIK harus terdiri dari 16 digit angka.');
        return false;
      }
      return true;
    });
  })();
</script>
@endsection

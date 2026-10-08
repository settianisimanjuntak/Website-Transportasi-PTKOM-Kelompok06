@extends("layouts.app")

@section("title", "Profil Saya — TiketBus")

@section("content")
@php
  $initials = $user->initials();
  $joined = $user->created_at->locale('id')->translatedFormat('F Y');
@endphp

<style>
  .page{padding:28px 0 60px;}
  .profile-hero{
    background:linear-gradient(120deg, var(--green-700), var(--green-900));
    border-radius:16px;color:#fff;padding:26px 28px;display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:16px;
  }
  .p-left{display:flex;align-items:center;gap:16px;}
  .avatar{width:64px;height:64px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;color:var(--green-700);font-size:20px;}
  .p-name{font-size:18px;font-weight:800;}
  .p-name .tag{background:rgba(255,255,255,.18);color:#fff;margin-left:8px;}
  .p-sub{font-size:12.5px;opacity:.85;margin-top:3px;}
  .p-stats{display:flex;gap:32px;text-align:center;}
  .p-stats .num{font-size:20px;font-weight:800;}
  .p-stats .lbl{font-size:11px;opacity:.8;}

  .profile-layout{display:grid;grid-template-columns:220px 1fr;gap:24px;align-items:start;}
  .side-menu{padding:10px;}
  .side-menu a,.side-menu button{display:block;width:100%;text-align:left;padding:12px 14px;border-radius:8px;font-size:13.5px;font-weight:700;color:var(--ink-soft);margin-bottom:2px;background:none;border:none;cursor:pointer;font-family:inherit;}
  .side-menu a:hover{background:var(--line-soft);}
  .side-menu a.active{background:var(--green-100);color:var(--green-700);}
  .side-menu .danger{color:var(--red);margin-top:10px;}

  .block{padding:24px;margin-bottom:20px;}
  .form-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:10px;}
  .btn-outline{background:#fff;border:1.5px solid var(--line);color:var(--ink-soft);padding:12px 20px;border-radius:10px;font-weight:700;font-size:14px;cursor:pointer;font-family:inherit;}

  .trip-row{display:flex;justify-content:space-between;align-items:center;padding:16px;border:1px solid var(--line-soft);border-radius:10px;flex-wrap:wrap;gap:12px;margin-bottom:10px;}
  .trip-info{display:flex;align-items:center;gap:18px;}
  .trip-info .t{font-weight:800;font-size:14px;}
  .trip-info .p{font-size:11.5px;color:var(--ink-soft);}
  .empty-state{text-align:center;padding:32px 16px;color:var(--ink-soft);font-size:13.5px;}
  .checkbox-row:has(input:checked){border-color:var(--green-500) !important;background:var(--green-50);}
  .pay-info{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;}
  .pay-item{border:1px solid var(--line-soft);border-radius:10px;padding:16px;font-size:13px;}
  .pay-item b{display:block;margin-bottom:6px;font-size:13.5px;}
  .pay-item span{color:var(--ink-soft);font-size:12px;line-height:1.5;}
  @media (max-width:640px){
    .profile-hero{flex-direction:column;align-items:flex-start;padding:20px;}
    .p-stats{width:100%;justify-content:space-between;gap:10px;}
    .profile-layout{grid-template-columns:1fr;}
    .side-menu{display:flex;overflow-x:auto;gap:6px;padding:8px;}
    .side-menu a,.side-menu button{white-space:nowrap;margin-bottom:0;width:auto;}
    .block{padding:16px;}
    .field-row{flex-direction:column;gap:0;}
    .trip-row{flex-direction:column;align-items:stretch;}
    .pay-info{grid-template-columns:1fr;}
  }
</style>

<div class="container page">

  <div class="profile-hero">
    <div class="p-left">
      <div class="avatar">{{ $initials }}</div>
      <div>
        <div class="p-name">{{ $user->name }}
          @if ($user->points >= 1000)
            <span class="tag">TiketBus Priority Member</span>
          @endif
        </div>
        <div class="p-sub">{{ $user->email }} · Bergabung sejak {{ $joined }}</div>
      </div>
    </div>
    <div class="p-stats">
      <div><div class="num">{{ $stats['trips'] }}</div><div class="lbl">Perjalanan</div></div>
      <div><div class="num">{{ $stats['active'] }}</div><div class="lbl">Tiket Aktif</div></div>
      <div><div class="num">{{ number_format($stats['points'], 0, ',', '.') }}</div><div class="lbl">Poin TiketBus</div></div>
    </div>
  </div>

  <div class="profile-layout">
    <div class="card side-menu">
      <a class="active" href="#data-diri">👤 Data Diri &amp; Akun</a>
      <a href="#riwayat">🎫 Riwayat Tiket &amp; Perjalanan</a>
      <a href="#metode-pembayaran">💳 Metode Pembayaran</a>
      <a href="#keamanan">🛡 Keamanan Akun</a>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="danger">⎋ Keluar</button>
      </form>
    </div>

    <div>
      <div class="card block" id="data-diri">
        <h3 class="section-title">Informasi Pribadi</h3>
        <p class="muted" style="font-size:12.5px;margin-top:-10px;">Perbarui data diri Anda untuk kemudahan verifikasi dan pemesanan tiket bus.</p>
        <form method="POST" action="{{ route('profile.update') }}">
          @csrf
          @method('PUT')
          <div class="field-row">
            <div class="field">
              <label>Nama Lengkap Sesuai KTP</label>
              <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="field">
              <label>Nomor Induk Kependudukan (NIK)</label>
              <input type="text" name="nik" value="{{ old('nik', $user->nik) }}" maxlength="16" pattern="[0-9]{16}" placeholder="16 digit angka">
            </div>
          </div>
          <div class="field-row">
            <div class="field">
              <label>Alamat Email @if($user->email_verified_at)<span class="tag tag-green" style="margin-left:6px;">Terverifikasi</span>@endif</label>
              <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="field">
              <label>Nomor Ponsel (WhatsApp Aktif)</label>
              <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required>
            </div>
          </div>
          <div class="field-row">
            <div class="field">
              <label>Tanggal Lahir</label>
              <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}">
            </div>
            <div class="field">
              <label>Jenis Kelamin</label>
              <div style="display:flex;gap:10px;">
                <label class="checkbox-row" style="border:1.5px solid var(--line);padding:12px 16px;border-radius:9px;flex:1;font-weight:700;color:var(--ink);">
                  <input type="radio" name="gender" value="male" @checked(old('gender', $user->gender) === 'male')"> Pria
                </label>
                <label class="checkbox-row" style="border:1.5px solid var(--line);padding:12px 16px;border-radius:9px;flex:1;font-weight:700;color:var(--ink);">
                  <input type="radio" name="gender" value="female" @checked(old('gender', $user->gender) === 'female')"> Wanita
                </label>
              </div>
            </div>
          </div>
          <div class="form-actions">
            <a href="{{ route('profile.show') }}" class="btn-outline" style="text-decoration:none;display:inline-flex;align-items:center;">Batal</a>
            <button type="submit" class="btn btn-primary">✓ Simpan Perubahan</button>
          </div>
        </form>
      </div>

      <div class="card block" id="riwayat">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
          <h3 class="section-title" style="margin:0;">Perjalanan Mendatang</h3>
          <span style="font-size:13px;font-weight:800;color:var(--green-700);">{{ $upcoming->count() }} Tiket</span>
        </div>

        @forelse ($upcoming as $booking)
          @php
            $passenger = $booking->passengers->first();
            $firstStop = $booking->schedule->stops->first();
            $lastStop = $booking->schedule->stops->last();
          @endphp
          <div class="trip-row">
            <div class="trip-info">
              <span class="tag tag-{{ $booking->schedule->bus->busClass->badge }}">{{ $booking->schedule->bus->busClass->name }}</span>
              <div>
                <div class="t">{{ $booking->schedule->operator->name }} · Kode Armada: {{ $booking->schedule->bus->code }}</div>
                <div class="p">
                  {{ time_short($booking->schedule->departure_time) }} {{ $firstStop?->name }}
                  · {{ $booking->schedule->durationText() }} · Langsung
                  · {{ time_short($booking->schedule->arrival_time) }} {{ $lastStop?->name }}
                </div>
              </div>
            </div>
            <div style="text-align:right;">
              <div class="muted" style="font-size:11.5px;">
                {{ $booking->schedule->service_date->locale('id')->translatedFormat('D, d M Y') }} · Kursi: {{ $passenger?->seat_no ?? '—' }}
              </div>
              <a href="{{ route('tickets.index', $booking) }}" class="btn btn-primary" style="padding:8px 16px;font-size:12.5px;margin-top:6px;display:inline-flex;">⬇ E-Tiket</a>
            </div>
          </div>
        @empty
          <div class="empty-state">Belum ada perjalanan mendatang. <a href="{{ route('search.index') }}" style="color:var(--green-700);font-weight:700;">Cari tiket sekarang →</a></div>
        @endforelse

        <div style="display:flex;justify-content:space-between;align-items:center;margin:22px 0 16px;">
          <h3 class="section-title" style="margin:0;">Riwayat Perjalanan</h3>
        </div>

        @forelse ($history as $booking)
          @php $passenger = $booking->passengers->first(); @endphp
          <div class="trip-row">
            <div class="trip-info">
              <span class="tag tag-amber">Selesai</span>
              <div>
                <div class="t">{{ $booking->schedule->operator->name }} · {{ $booking->code }}</div>
                <div class="p">
                  {{ $booking->schedule->route->originCity->name }} → {{ $booking->schedule->route->destinationCity->name }}
                  · {{ $booking->schedule->service_date->locale('id')->translatedFormat('d M Y') }}
                  · Kursi {{ $passenger?->seat_no ?? '—' }}
                </div>
              </div>
            </div>
            <div style="text-align:right;">
              <div class="muted" style="font-size:11.5px;">{{ rp($booking->total) }}</div>
              <span class="tag tag-green" style="margin-top:6px;display:inline-block;">Lunas</span>
            </div>
          </div>
        @empty
          <div class="empty-state">Belum ada riwayat perjalanan.</div>
        @endforelse
      </div>

      <div class="card block" id="metode-pembayaran">
        <h3 class="section-title">Metode Pembayaran</h3>
        <p class="muted" style="font-size:12.5px;margin-top:-10px;margin-bottom:16px;">Metode yang tersedia saat pemesanan. Kelola kartu/e-wallet tersimpan melalui payment gateway.</p>
        <div class="pay-info">
          <div class="pay-item"><b>QRIS</b><span>BCA, Mandiri, GoPay, OVO — scan satu QR untuk semua aplikasi.</span></div>
          <div class="pay-item"><b>Transfer Virtual Account</b><span>BCA, BRI, Mandiri, BNI — verifikasi otomatis.</span></div>
          <div class="pay-item"><b>E-Wallet</b><span>ShopeePay, DANA — bayar langsung dari aplikasi.</span></div>
        </div>
      </div>

      <div class="card block" id="keamanan">
        <h3 class="section-title">Keamanan Akun</h3>
        <p class="muted" style="font-size:12.5px;margin-top:-10px;">Perbarui kata sandi Anda secara berkala untuk menjaga keamanan akun.</p>
        <form method="POST" action="{{ route('profile.password') }}">
          @csrf
          @method('PUT')
          <div class="field-row">
            <div class="field">
              <label>Kata Sandi Saat Ini</label>
              <input type="password" name="current_password" required>
            </div>
            <div class="field">
              <label>Kata Sandi Baru</label>
              <input type="password" name="password" required>
            </div>
            <div class="field">
              <label>Ulangi Kata Sandi Baru</label>
              <input type="password" name="password_confirmation" required>
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">🛡 Perbarui Kata Sandi</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

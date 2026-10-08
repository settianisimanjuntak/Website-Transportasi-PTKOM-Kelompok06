@extends("layouts.admin")

@section("title", "Pengaturan — Admin TiketBus")

@section("content")
<div class="admin-top">
  <div></div>
</div>

<h1 class="page-h">Pengaturan Sistem</h1>
<div class="page-sub">Parameter biaya, perlindungan, dan timing pemesanan yang berlaku untuk seluruh transaksi.</div>

<div class="card admin-form">
  <form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    @method('PUT')

    <div class="field-row">
      <div class="field">
        <label>Biaya Layanan Sistem (Rp)</label>
        <input type="number" name="service_fee" value="{{ $settings['service_fee'] }}" min="0" step="500" required>
        <div style="font-size:11.5px;color:var(--ink-soft);margin-top:6px;">Ditambahkan per pemesanan. 0 = gratis.</div>
      </div>
      <div class="field">
        <label>Asuransi Jasa Raharja (Rp)</label>
        <input type="number" name="insurance_fee" value="{{ $settings['insurance_fee'] }}" min="0" step="500" required>
        <div style="font-size:11.5px;color:var(--ink-soft);margin-top:6px;">Wajib, mengikuti tarif regulasi.</div>
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label>Biaya Proteksi Perjalanan (Rp)</label>
        <input type="number" name="protection_fee" value="{{ $settings['protection_fee'] }}" min="0" step="500" required>
      </div>
      <div class="field">
        <label>Status Proteksi</label>
        <select name="protection_enabled" required>
          <option value="1" @selected($settings['protection_enabled'] == 1)>Aktif (bisa ditambahkan penumpang)</option>
          <option value="0" @selected($settings['protection_enabled'] == 0)>Nonaktif</option>
        </select>
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label>Batas Waktu Pembayaran (menit)</label>
        <input type="number" name="booking_expiry_minutes" value="{{ $settings['booking_expiry_minutes'] }}" min="1" max="1440" required>
        <div style="font-size:11.5px;color:var(--ink-soft);margin-top:6px;">Kursi dilepas otomatis jika melewati batas ini.</div>
      </div>
      <div class="field">
        <label>Waktu Check-in Sebelum Berangkat (menit)</label>
        <input type="number" name="boarding_lead_minutes" value="{{ $settings['boarding_lead_minutes'] }}" min="0" max="240" required>
        <div style="font-size:11.5px;color:var(--ink-soft);margin-top:6px;">Tampil di e-tiket sebagai pintu boarding.</div>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">✓ Simpan Pengaturan</button>
    </div>
  </form>
</div>
@endsection

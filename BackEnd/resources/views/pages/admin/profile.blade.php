@extends("layouts.admin")

@section("title", "Profil Admin — TiketBus")

@section("content")
<div class="admin-top">
  <div></div>
</div>

<h1 class="page-h">Profil Admin</h1>
<div class="page-sub">Perbarui data akun dan kata sandi administrator.</div>

<div class="card admin-form" style="margin-bottom:20px;">
  <div style="display:flex;align-items:center;gap:16px;margin-bottom:22px;">
    <div style="width:64px;height:64px;border-radius:50%;background:var(--green-100);display:flex;align-items:center;justify-content:center;font-weight:800;color:var(--green-700);font-size:20px;">
      {{ $user->initials() }}
    </div>
    <div>
      <div style="font-weight:800;font-size:17px;">{{ $user->name }}</div>
      <div style="font-size:12.5px;color:var(--ink-soft);">Administrator · Bergabung {{ $user->created_at->locale('id')->translatedFormat('F Y') }}</div>
    </div>
  </div>

  <h3 class="section-title">Informasi Akun</h3>
  <form method="POST" action="{{ route('admin.profile.update') }}">
    @csrf
    @method('PUT')
    <div class="field-row">
      <div class="field">
        <label>Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
      </div>
      <div class="field">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
      </div>
    </div>
    <div class="field">
      <label>Nomor Ponsel</label>
      <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}">
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">✓ Simpan Perubahan</button>
    </div>
  </form>
</div>

<div class="card admin-form">
  <h3 class="section-title">Keamanan</h3>
  <p class="muted" style="font-size:12.5px;margin-top:-8px;">Perbarui kata sandi Anda secara berkala.</p>
  <form method="POST" action="{{ route('admin.profile.password') }}">
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
@endsection

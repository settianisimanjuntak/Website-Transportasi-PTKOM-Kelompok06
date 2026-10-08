@extends("layouts.app")

@section("title", "Atur Ulang Kata Sandi — TiketBus")

@section("content")
<style>
  .auth-wrap{min-height:calc(100vh - 72px);display:flex;align-items:center;justify-content:center;padding:48px 24px;background:var(--cream);}
  .auth-card{width:100%;max-width:440px;padding:36px 32px;}
  .auth-card h1{font-size:22px;margin:0 0 8px;letter-spacing:-0.01em;}
  .auth-card p.sub{margin:0 0 24px;font-size:14px;color:var(--ink-soft);line-height:1.55;}
  .auth-foot{text-align:center;margin-top:20px;font-size:13.5px;color:var(--ink-soft);}
  .auth-foot a{color:var(--green-700);font-weight:800;}
</style>

<div class="auth-wrap">
  <div class="card auth-card">
    <div style="display:flex;align-items:center;gap:9px;font-weight:800;margin-bottom:24px;">
      <span class="brand-mark">🚌</span>TiketBus
    </div>
    <h1>Atur ulang kata sandi</h1>
    <p class="sub">Masukkan kata sandi baru untuk akun Anda.</p>

    <form method="POST" action="{{ route('password.update') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">
      <div class="field">
        <label for="email">Alamat Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autofocus>
      </div>
      <div class="field">
        <label for="password">Kata Sandi Baru</label>
        <input id="password" name="password" type="password" placeholder="Minimal 8 karakter" required>
      </div>
      <div class="field" style="margin-bottom:22px;">
        <label for="password_confirmation">Ulangi Kata Sandi Baru</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Simpan Kata Sandi Baru</button>
    </form>

    <div class="auth-foot"><a href="{{ route('login') }}">Kembali ke halaman masuk</a></div>
  </div>
</div>
@endsection

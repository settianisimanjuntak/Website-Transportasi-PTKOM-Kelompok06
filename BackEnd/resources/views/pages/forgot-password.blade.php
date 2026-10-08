@extends("layouts.app")

@section("title", "Lupa Kata Sandi — TiketBus")

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
    <h1>Lupa kata sandi?</h1>
    <p class="sub">Masukkan email akun Anda. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi.</p>

    <form method="POST" action="{{ route('password.email') }}">
      @csrf
      <div class="field">
        <label for="email">Alamat Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Kirim Tautan Reset</button>
    </form>

    <div class="auth-foot">Ingat kata sandi Anda? <a href="{{ route('login') }}">Kembali masuk</a></div>
  </div>
</div>
@endsection

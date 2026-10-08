@extends("layouts.app")

@section("title", "Cari Tiket Bus — TiketBus")

@section("content")
<style>
  .hero{
    background:linear-gradient(180deg, var(--green-900) 0%, var(--green-700) 100%);
    color:#fff;padding:56px 0 130px;position:relative;overflow:hidden;
  }
  .hero::after{
    content:"";position:absolute;right:-60px;top:-60px;width:340px;height:340px;
    background:radial-gradient(circle, rgba(255,255,255,.08) 0%, transparent 70%);
  }
  .hero h1{font-size:34px;margin:0 0 10px;letter-spacing:-0.01em;text-align:center;}
  .hero p{text-align:center;color:#CFE4D8;margin:0;font-size:15px;}
  .search-card{
    max-width:960px;margin:-72px auto 0;background:#fff;border-radius:16px;
    box-shadow:var(--shadow);padding:26px 28px;position:relative;z-index:5;
    display:flex;gap:18px;align-items:flex-end;flex-wrap:wrap;
  }
  .search-card .field{flex:1;min-width:180px;margin-bottom:0;}
  .search-card .btn{white-space:nowrap;padding:13px 26px;}
  .results-section{max-width:960px;margin:48px auto 0;padding:0 32px 60px;}
  .results-head{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:18px;}
  .results-head h2{font-size:18px;margin:0 0 4px;font-weight:800;}
  .results-head .muted{font-size:13px;}
  .results-list{display:flex;flex-direction:column;gap:14px;}
  .bus-row{padding:20px 22px;display:flex;justify-content:space-between;align-items:center;gap:18px;flex-wrap:wrap;transition:box-shadow .15s;}
  .bus-row:hover{box-shadow:var(--shadow);}
  .bus-main{display:flex;align-items:center;gap:30px;flex-wrap:wrap;}
  .bus-id .name{font-weight:800;font-size:15.5px;margin-bottom:6px;}
  .bus-id .sub{font-size:12px;color:var(--ink-soft);}
  .bus-time{display:flex;align-items:center;gap:12px;}
  .bus-time .t{text-align:center;}
  .bus-time .t .clock{font-weight:800;font-size:17px;}
  .bus-time .t .place{font-size:11.5px;color:var(--ink-soft);margin-top:2px;}
  .bus-time .mid{text-align:center;color:var(--ink-soft);font-size:11px;}
  .bus-time .mid .line{width:60px;height:1px;background:var(--line);margin:4px 0;}
  .bus-seats{font-size:12.5px;font-weight:700;}
  .bus-seats.low{color:var(--red);}
  .bus-seats.ok{color:var(--green-700);}
  .bus-price{text-align:right;}
  .bus-price .lbl{font-size:11px;color:var(--ink-soft);}
  .empty-state{text-align:center;padding:48px 20px;color:var(--ink-soft);font-size:14px;}
  @media (max-width:640px){
    .hero{padding:32px 0 100px;}
    .hero h1{font-size:24px;padding:0 20px;}
    .search-card{margin:-56px 16px 0;padding:18px;flex-direction:column;align-items:stretch;}
    .search-card .field{min-width:0;}
    .search-card .btn{width:100%;}
    .results-section{margin-top:36px;padding:0 18px 40px;}
    .bus-row{flex-direction:column;align-items:stretch;padding:16px;}
    .bus-row > div:last-child{justify-content:space-between;display:flex;align-items:center;}
  }
</style>

<section class="hero">
  <h1>Pesan Tiket Bus Antarkota</h1>
  <p>Pencarian rute cepat, jadwal resmi langsung dari operator bus ternama.</p>
</section>

<form class="search-card" method="GET" action="{{ route('results') }}">
  <div class="field">
    <label for="from">Asal</label>
    <select id="from" name="from">
      <option value="">Semua Kota Asal</option>
      @foreach ($cities as $city)
        <option value="{{ $city->id }}" @selected(old('from', $filters['from']) == $city->id)>{{ $city->name }} (Semua Terminal)</option>
      @endforeach
    </select>
  </div>
  <div class="field">
    <label for="to">Tujuan</label>
    <select id="to" name="to">
      <option value="">Semua Kota Tujuan</option>
      @foreach ($cities as $city)
        <option value="{{ $city->id }}" @selected(old('to', $filters['to']) == $city->id)>{{ $city->name }} (Semua Terminal)</option>
      @endforeach
    </select>
  </div>
  <div class="field">
    <label for="date">Tanggal</label>
    <input id="date" name="date" type="date" value="{{ old('date', $filters['date'] ?? today()->toDateString()) }}" min="{{ today()->toDateString() }}">
  </div>
  <button type="submit" class="btn btn-primary">Cari Bus</button>
</form>

<section class="results-section">
  <div class="results-head">
    <div>
      <h2>{{ $heading }}</h2>
      <div class="muted">{{ $dateLabel }}</div>
    </div>
    <span class="tag tag-green">{{ $resultCount }} JADWAL TERSEDIA</span>
  </div>

  <div class="results-list">
    @forelse ($schedules as $schedule)
      @php $remaining = $schedule->bus->capacity - $schedule->booked_count; @endphp
      <a href="{{ route('schedules.show', $schedule) }}" class="card bus-row">
        <div class="bus-main">
          <div class="bus-id">
            <div class="name">
              {{ $schedule->operator->name }}
              <span class="tag tag-{{ $schedule->bus->busClass->badge }}">{{ $schedule->bus->busClass->name }}</span>
            </div>
            <div class="sub">{{ $schedule->bus->busClass->description }}</div>
          </div>
          <div class="bus-time">
            <div class="t">
              <div class="clock">{{ time_short($schedule->departure_time) }}</div>
              <div class="place">{{ terminal_short($schedule->departureTerminal->name) }}</div>
            </div>
            <div class="mid">{{ $schedule->durationText() }}<div class="line"></div></div>
            <div class="t">
              <div class="clock">{{ time_short($schedule->arrival_time) }}{{ $schedule->arrival_day_offset > 0 ? ' (+1)' : '' }}</div>
              <div class="place">{{ terminal_short($schedule->arrivalTerminal->name) }}</div>
            </div>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:24px;">
          <div class="bus-seats {{ $remaining <= 3 ? 'low' : 'ok' }}">
            @if ($remaining <= 0)
              Penuh
            @elseif ($remaining <= 3)
              Sisa {{ $remaining }} kursi
            @else
              Sisa {{ $remaining }} kursi
            @endif
          </div>
          <div class="bus-price">
            <div class="lbl">per kursi</div>
            <div class="price">{{ rp($schedule->price) }}</div>
          </div>
        </div>
      </a>
    @empty
      <div class="card empty-state">
        Belum ada jadwal untuk pilihan ini.<br>Coba ubah kota asal, tujuan, atau tanggal perjalanan.
      </div>
    @endforelse
  </div>
</section>
@endsection

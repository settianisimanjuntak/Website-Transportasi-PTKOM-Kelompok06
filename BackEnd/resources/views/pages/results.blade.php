@extends("layouts.app")

@section("title", "Hasil Pencarian — TiketBus")

@section("content")
<style>
  .route-bar{background:#fff;border-bottom:1px solid var(--line);padding:20px 0;}
  .route-bar-inner{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;}
  .route-bar h2{font-size:19px;margin:0 0 4px;font-weight:800;}
  .route-bar .muted{font-size:13.5px;}
  .route-bar a{font-weight:800;color:var(--green-700);font-size:14px;}
  .filters{padding:20px 0 8px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;}
  .chip-row{display:flex;gap:10px;flex-wrap:wrap;}
  .chip{
    border:1.5px solid var(--line);background:#fff;padding:9px 16px;border-radius:999px;
    font-size:13.5px;font-weight:700;color:var(--ink-soft);
  }
  .chip.active{background:var(--green-600);border-color:var(--green-600);color:#fff;}
  .sort{font-size:13.5px;color:var(--ink-soft);display:flex;align-items:center;gap:6px;}
  .sort select{border:none;font-weight:800;color:var(--ink);background:transparent;}
  .results-list{display:flex;flex-direction:column;gap:14px;padding-bottom:60px;}
  .bus-row{padding:22px 24px;display:flex;justify-content:space-between;align-items:center;gap:20px;flex-wrap:wrap;transition:box-shadow .15s;}
  .bus-row:hover{box-shadow:var(--shadow);}
  .bus-main{display:flex;align-items:center;gap:36px;flex-wrap:wrap;}
  .bus-id .name{font-weight:800;font-size:16px;margin-bottom:6px;}
  .bus-id .sub{font-size:12.5px;color:var(--ink-soft);}
  .bus-time{display:flex;align-items:center;gap:14px;}
  .bus-time .t{text-align:center;}
  .bus-time .t .clock{font-weight:800;font-size:18px;}
  .bus-time .t .place{font-size:12px;color:var(--ink-soft);margin-top:2px;}
  .bus-time .mid{text-align:center;color:var(--ink-soft);font-size:11.5px;}
  .bus-time .mid .line{width:70px;height:1px;background:var(--line);margin:4px 0;position:relative;}
  .bus-seats{font-size:13px;font-weight:700;}
  .bus-seats.low{color:var(--red);}
  .bus-seats.ok{color:var(--green-700);}
  .bus-price{text-align:right;}
  .bus-price .lbl{font-size:11.5px;color:var(--ink-soft);}
  .empty-state{text-align:center;padding:48px 20px;color:var(--ink-soft);font-size:14px;}
  @media (max-width:640px){
    .route-bar-inner{flex-direction:column;align-items:flex-start;}
    .filters{flex-direction:column;align-items:flex-start;}
    .chip-row{overflow-x:auto;flex-wrap:nowrap;width:100%;padding-bottom:4px;}
    .chip{white-space:nowrap;}
    .bus-row{flex-direction:column;align-items:stretch;padding:18px;}
    .bus-main{gap:16px;}
    .bus-time{gap:10px;}
    .bus-row > div:last-child{justify-content:space-between;}
  }
</style>

@php
  $baseQuery = request()->query();
  $chip = fn (string $value) => route('results', array_merge($baseQuery, ['filter' => $value]));
@endphp

<div class="route-bar">
  <div class="container route-bar-inner">
    <div>
      <h2>{{ $heading }}</h2>
      <div class="muted">{{ $dateLabel }} · 1 Penumpang</div>
    </div>
    <a href="{{ route('search.index') }}">Ganti pencarian ✎</a>
  </div>
</div>

<div class="container">
  <div class="filters">
    <div class="chip-row">
      <a class="chip {{ $filters['filter'] === 'all' ? 'active' : '' }}" href="{{ $chip('all') }}">Semua Bus</a>
      <a class="chip {{ $filters['filter'] === 'pagi' ? 'active' : '' }}" href="{{ $chip('pagi') }}">Pagi (06:00–12:00)</a>
      <a class="chip {{ $filters['filter'] === 'malam' ? 'active' : '' }}" href="{{ $chip('malam') }}">Malam (18:00–24:00)</a>
      <a class="chip {{ $filters['filter'] === 'exec' ? 'active' : '' }}" href="{{ $chip('exec') }}">Executive &amp; Sleeper</a>
    </div>
    <form method="GET" action="{{ route('results') }}" class="sort">
      Urutkan:
      <select name="sort" onchange="this.form.submit()">
        <option value="murah" @selected($filters['sort'] === 'murah')>Termurah</option>
        <option value="cepat" @selected($filters['sort'] === 'cepat')>Tercepat</option>
        <option value="awal" @selected($filters['sort'] === 'awal')>Keberangkatan terawal</option>
      </select>
      <input type="hidden" name="from" value="{{ request('from') }}">
      <input type="hidden" name="to" value="{{ request('to') }}">
      <input type="hidden" name="date" value="{{ request('date') }}">
      <input type="hidden" name="filter" value="{{ $filters['filter'] }}">
    </form>
  </div>

  <div class="results-list">
    @forelse ($schedules as $schedule)
      @php
        $remaining = $schedule->bus->capacity - $schedule->booked_count;
        $direct = $schedule->stops->count() <= 2;
      @endphp
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
            <div class="mid">
              {{ $schedule->durationText() }}
              <div class="line"></div>
              {{ $direct ? 'Langsung' : ($schedule->stops->count() - 2).' Titik Berhenti' }}
            </div>
            <div class="t">
              <div class="clock">{{ time_short($schedule->arrival_time) }}{{ $schedule->arrival_day_offset > 0 ? ' (+1)' : '' }}</div>
              <div class="place">{{ terminal_short($schedule->arrivalTerminal->name) }}</div>
            </div>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:28px;">
          <div class="bus-seats {{ $remaining <= 3 ? 'low' : 'ok' }}">
            {{ $remaining <= 0 ? 'Penuh' : $remaining.' kursi tersisa' }}
          </div>
          <div class="bus-price">
            <div class="lbl">per kursi</div>
            <div class="price">{{ rp($schedule->price) }}</div>
          </div>
        </div>
      </a>
    @empty
      <div class="card empty-state">
        Tidak ada bus yang cocok dengan pencarian/filt Anda.<br>
        Coba ubah filter, tanggal, atau kota asal &amp; tujuan.
      </div>
    @endforelse
  </div>
</div>
@endsection

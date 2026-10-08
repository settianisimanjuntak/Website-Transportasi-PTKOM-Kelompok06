@extends("layouts.app")

@section("title", $schedule->operator->name.' — TiketBus')

@section("content")
<style>
  .page{padding:28px 0 60px;}
  .back-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:10px;}
  .back-row a.back{font-weight:700;font-size:14px;color:var(--ink-soft);}
  .title-row{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:22px;flex-wrap:wrap;gap:16px;}
  .title-row h1{font-size:26px;margin:0 0 6px;}
  .title-row .muted{font-size:14px;}
  .facts{display:flex;gap:22px;flex-wrap:wrap;}
  .fact{font-size:12.5px;color:var(--ink-soft);text-align:right;}
  .fact strong{display:block;color:var(--ink);font-size:13.5px;}
  .layout{display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start;}
  .photos{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:22px;}
  .photo-item{position:relative;border-radius:12px;overflow:hidden;height:210px;}
  .photo-item img{height:100%;width:100%;object-fit:cover;display:block;}
  .photo-item .cap{
    position:absolute;left:12px;bottom:12px;background:rgba(15,42,33,.72);color:#fff;
    font-size:11.5px;font-weight:700;padding:5px 11px;border-radius:999px;
  }
  .block{padding:22px 24px;margin-bottom:18px;}
  .block-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;}
  .route-timeline{position:relative;padding-left:26px;}
  .route-timeline::before{content:"";position:absolute;left:6px;top:8px;bottom:8px;width:2px;background:var(--line);border-style:dashed;border-color:var(--line);}
  .stop{position:relative;padding-bottom:26px;}
  .stop:last-child{padding-bottom:0;}
  .stop::before{content:"";position:absolute;left:-26px;top:3px;width:12px;height:12px;border-radius:50%;background:var(--green-600);border:3px solid var(--green-100);}
  .stop.end::before{background:var(--ink);}
  .stop-top{display:flex;justify-content:space-between;font-weight:800;font-size:15px;gap:12px;}
  .stop-sub{font-size:12.5px;color:var(--ink-soft);margin-top:3px;}
  .fac-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;}
  .fac{border:1px solid var(--line-soft);border-radius:10px;padding:12px 10px;text-align:center;font-size:12px;color:var(--ink-soft);}
  .fac .ic{font-size:19px;margin-bottom:6px;}
  .policy-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
  .policy{border:1px solid var(--line-soft);border-radius:10px;padding:14px;}
  .policy .h{font-weight:800;font-size:13.5px;margin-bottom:6px;}
  .policy .d{font-size:12.5px;color:var(--ink-soft);line-height:1.5;}
  .summary{position:sticky;top:88px;padding:22px;}
  .summary h3{font-size:14px;margin:0 0 14px;}
  .sum-row{display:flex;justify-content:space-between;font-size:13.5px;margin-bottom:8px;color:var(--ink-soft);gap:10px;}
  .sum-row span.v{color:var(--ink);font-weight:700;text-align:right;}
  .sum-total{display:flex;justify-content:space-between;align-items:baseline;border-top:1px solid var(--line);margin-top:14px;padding-top:14px;}
  .sum-total .lbl{font-size:13px;color:var(--ink-soft);}
  .sum-total .val{font-size:22px;font-weight:800;color:var(--green-700);}
  @media (max-width:900px){.layout{grid-template-columns:1fr;} .photos{grid-template-columns:1fr;} .fac-grid{grid-template-columns:repeat(2,1fr);} .policy-grid{grid-template-columns:1fr;}}
  @media (max-width:640px){
    .title-row{flex-direction:column;}
    .facts{gap:14px;width:100%;}
    .fact{text-align:left;flex:1;}
    .block{padding:16px;}
    .summary{position:static;}
  }
</style>

@php
  $dateLabel = $schedule->service_date->locale('id')->translatedFormat('l, d F Y');
  $hasToilet = $schedule->bus->facilities->contains('name', 'Toilet Bersih');
  $photo = $schedule->bus->photo ?: 'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?q=80&w=700&auto=format&fit=crop';
  $photo2 = 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=700&auto=format&fit=crop';
  $backQuery = [
    'from' => $schedule->route->origin_city_id,
    'to' => $schedule->route->destination_city_id,
    'date' => $schedule->service_date->toDateString(),
  ];
@endphp

<div class="container page">
  <div class="back-row">
    <a href="{{ route('results', $backQuery) }}" class="back">← Ganti Bus &amp; Jadwal Lain</a>
    <span class="tag tag-{{ $schedule->bus->busClass->badge }}">{{ $schedule->bus->busClass->name }}</span>
  </div>

  <div class="title-row">
    <div>
      <h1>{{ $schedule->operator->name }}</h1>
      <div class="muted">
        {{ terminal_short($schedule->departureTerminal->name) }} ({{ $schedule->departureTerminal->city->name }})
        →
        {{ terminal_short($schedule->arrivalTerminal->name) }} ({{ $schedule->arrivalTerminal->city->name }})
        · {{ $dateLabel }}
      </div>
    </div>
    <div class="facts">
      <div class="fact"><strong>{{ $schedule->bus->capacity }} Kursi ({{ $schedule->bus->layout }})</strong>Kapasitas</div>
      <div class="fact"><strong>{{ $schedule->bus->model ?: 'Standar' }}</strong>Armada &amp; Sasis</div>
      <div class="fact"><strong>{{ $hasToilet ? 'Tersedia di Bus' : 'Tidak Tersedia' }}</strong>Toilet Kabin</div>
    </div>
  </div>

  <div class="layout">
    <div>
      <div class="photos">
        <div class="photo-item">
          <img src="{{ $photo }}" alt="Interior kabin">
          <span class="cap">Interior Kabin</span>
        </div>
        <div class="photo-item">
          <img src="{{ $photo2 }}" alt="Tampak luar bus">
          <span class="cap">Tampak Luar Bus</span>
        </div>
      </div>

      <div class="card block">
        <div class="block-head">
          <h3 class="section-title" style="margin:0;">Rute &amp; Jadwal Perjalanan</h3>
          <span class="tag tag-green">{{ $schedule->durationText() }}</span>
        </div>
        <div class="route-timeline">
          @foreach ($schedule->stops as $index => $stop)
            <div class="stop {{ $loop->last ? 'end' : '' }}">
              <div class="stop-top">
                <span @if(!$stop->is_terminal) style="font-weight:600;color:var(--ink-soft);" @endif>{{ $stop->name }}</span>
                <span>{{ $stop->stop_time ? time_short($stop->stop_time).' WIB' : '—' }}</span>
              </div>
              @if ($stop->note)
                <div class="stop-sub">{{ $stop->note }}</div>
              @endif
            </div>
          @endforeach
        </div>
      </div>

      <div class="card block">
        <div class="block-head">
          <h3 class="section-title" style="margin:0;">Fasilitas Unggulan Armada</h3>
          <span class="tag tag-green">{{ $schedule->bus->busClass->name }} Class</span>
        </div>
        <div class="fac-grid">
          @forelse ($schedule->bus->facilities as $facility)
            <div class="fac"><div class="ic">{{ $facility->icon }}</div>{{ $facility->name }}</div>
          @empty
            <div class="fac" style="grid-column:1/-1;">Belum ada data fasilitas.</div>
          @endforelse
        </div>
      </div>

      @if ($schedule->operator->policy)
        <div class="card block">
          <h3 class="section-title">Ketentuan Pembatalan &amp; Perubahan Jadwal</h3>
          <div class="policy-grid">
            <div class="policy">
              <div class="h">🔁 Bisa Reschedule (−{{ $schedule->operator->policy->reschedule_fee_percent }}%)</div>
              <div class="d">{{ $schedule->operator->policy->reschedule_text }}</div>
            </div>
            <div class="policy">
              <div class="h">⊘ Pembatalan &amp; Pengembalian Dana</div>
              <div class="d">{{ $schedule->operator->policy->cancel_text }}</div>
            </div>
          </div>
        </div>
      @endif
    </div>

    <div class="card summary">
      <h3>Ringkasan Tiket <span style="float:right;color:var(--ink-soft);font-weight:600;">1 Kursi</span></h3>
      <div class="sum-row"><span>{{ $schedule->operator->name }}</span></div>
      <div class="sum-row"><span>{{ $schedule->bus->busClass->name }} · {{ $schedule->route->originCity->name }} → {{ $schedule->route->destinationCity->name }}</span></div>
      <div class="sum-row" style="margin-bottom:16px;"><span>{{ $dateLabel }} · {{ time_short($schedule->departure_time) }} WIB</span></div>
      <div class="sum-row"><span>Tarif Bus</span><span class="v">{{ rp($schedule->price) }}</span></div>
      <div class="sum-row"><span>Asuransi Jasa Raharja</span><span class="v">{{ rp($insuranceFee) }}</span></div>
      <div class="sum-row"><span>Biaya Layanan</span><span class="v">{{ $serviceFee > 0 ? rp($serviceFee) : 'Gratis' }}</span></div>
      <div class="sum-total">
        <div><div class="lbl">Total Bayar</div><div class="val">{{ rp($total) }}</div></div>
        <span class="tag tag-green">Termasuk PPN</span>
      </div>

      @if ($bookable)
        <a href="{{ route('schedules.seat', $schedule) }}" class="btn btn-primary btn-block" style="margin-top:16px;">Lanjut Pilih Kursi →</a>
      @else
        <button class="btn btn-block" style="margin-top:16px;background:var(--line);color:var(--ink-soft);cursor:not-allowed;" disabled>
          {{ $schedule->isFullyBooked() ? 'Kursi Penuh' : ($schedule->status !== 'scheduled' ? 'Tidak Tersedia' : 'Sudah Berangkat') }}
        </button>
      @endif
      <div class="muted" style="font-size:12px;text-align:center;margin-top:10px;">Tiket resmi diterbitkan langsung oleh PO bus</div>
    </div>
  </div>
</div>
@endsection

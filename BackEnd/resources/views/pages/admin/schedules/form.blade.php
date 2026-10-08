@extends("layouts.admin")

@section("title", ($schedule ? 'Edit Jadwal' : 'Tambah Jadwal') . ' — Admin TiketBus')

@section("content")
@php
  $stops = old('stops', $schedule?->stops?->map(fn ($stop) => [
      'name' => $stop->name,
      'time' => $stop->stop_time ? substr($stop->stop_time, 0, 5) : '',
      'note' => $stop->note,
  ])->values()->all() ?: [['name' => '', 'time' => '', 'note' => '']]);
  $statusLabels = ['scheduled' => 'On Schedule', 'boarding' => 'Boarding', 'departed' => 'Berangkat', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];
@endphp

<div class="admin-top">
  <div>
    <a href="{{ route('admin.schedules.index') }}" class="detail-link">← Kembali ke Jadwal</a>
  </div>
</div>

<h1 class="page-h">{{ $schedule ? 'Edit Jadwal Keberangkatan' : 'Tambah Jadwal Keberangkatan' }}</h1>
<div class="page-sub">Tentukan rute, armada, terminal, waktu, dan tarif. Titik perjalanan (stops) dapat diisi manual atau dikosongkan untuk memakai terminal awal/akhir.</div>

<div class="card admin-form">
  <form method="POST" action="{{ $schedule ? route('admin.schedules.update', $schedule) : route('admin.schedules.store') }}">
    @csrf
    @if ($schedule) @method('PUT') @endif

    <div class="field-row">
      <div class="field">
        <label>Rute *</label>
        <select name="route_id" required>
          <option value="">— Pilih Rute —</option>
          @foreach ($routes as $route)
            <option value="{{ $route->id }}" @selected((string) old('route_id', $schedule?->route_id) === (string) $route->id)>
              {{ $route->originCity->name }} → {{ $route->destinationCity->name }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="field">
        <label>Armada Bus *</label>
        <select name="bus_id" required>
          <option value="">— Pilih Bus —</option>
          @foreach ($buses as $bus)
            <option value="{{ $bus->id }}" @selected((string) old('bus_id', $schedule?->bus_id) === (string) $bus->id)>
              {{ $bus->code }} — {{ $bus->operator->name }} ({{ $bus->busClass->name }}, {{ $bus->capacity }} kursi)
            </option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label>Terminal Keberangkatan *</label>
        <select name="departure_terminal_id" required>
          <option value="">— Pilih Terminal —</option>
          @foreach ($terminals as $terminal)
            <option value="{{ $terminal->id }}" @selected((string) old('departure_terminal_id', $schedule?->departure_terminal_id) === (string) $terminal->id)>
              {{ $terminal->name }} ({{ $terminal->city->name }})
            </option>
          @endforeach
        </select>
      </div>
      <div class="field">
        <label>Terminal Tujuan *</label>
        <select name="arrival_terminal_id" required>
          <option value="">— Pilih Terminal —</option>
          @foreach ($terminals as $terminal)
            <option value="{{ $terminal->id }}" @selected((string) old('arrival_terminal_id', $schedule?->arrival_terminal_id) === (string) $terminal->id)>
              {{ $terminal->name }} ({{ $terminal->city->name }})
            </option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label>Tanggal Layanan *</label>
        <input type="date" name="service_date" value="{{ old('service_date', $schedule?->service_date?->format('Y-m-d')) }}" required>
      </div>
      <div class="field">
        <label>Status Jadwal</label>
        <select name="status">
          @foreach ($statusLabels as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $schedule?->status ?? 'scheduled') === $value)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label>Jam Keberangkatan *</label>
        <input type="time" name="departure_time" value="{{ old('departure_time', $schedule ? substr($schedule->departure_time, 0, 5) : '08:00') }}" required>
      </div>
      <div class="field">
        <label>Jam Kedatangan *</label>
        <input type="time" name="arrival_time" value="{{ old('arrival_time', $schedule ? substr($schedule->arrival_time, 0, 5) : '14:00') }}" required>
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label>Offset Hari Tiba (+1, dst.)</label>
        <input type="number" name="arrival_day_offset" value="{{ old('arrival_day_offset', $schedule?->arrival_day_offset ?? 0) }}" min="0" max="3">
        <div style="font-size:11.5px;color:var(--ink-soft);margin-top:6px;">Isi 1 jika tiba keesokan hari.</div>
      </div>
      <div class="field">
        <label>Tarif per Kursi (Rp) *</label>
        <input type="number" name="price" value="{{ old('price', $schedule?->price) }}" min="0" step="1000" required>
      </div>
    </div>

    <div class="field">
      <label>Catatan Internal</label>
      <textarea name="notes" maxlength="1000" placeholder="Catatan operasional untuk jadwal ini...">{{ old('notes', $schedule?->notes) }}</textarea>
    </div>

    <div class="field">
      <label>Titik Perjalanan (Stops) <span style="font-weight:600;color:var(--ink-soft);">— opsional, minimal isi terminal awal &amp; akhir jika diisi</span></label>
      <div id="stopsWrap">
        @foreach ($stops as $index => $stop)
          <div class="field-row stop-row" style="grid-template-columns:1fr 110px 1fr auto;align-items:end;gap:10px;">
            <div class="field" style="margin-bottom:0;">
              <label style="font-size:11.5px;">Nama Titik</label>
              <input type="text" name="stops[{{ $index }}][name]" value="{{ $stop['name'] }}" placeholder="Terminal Pulogebang">
            </div>
            <div class="field" style="margin-bottom:0;">
              <label style="font-size:11.5px;">Jam</label>
              <input type="time" name="stops[{{ $index }}][time]" value="{{ $stop['time'] }}">
            </div>
            <div class="field" style="margin-bottom:0;">
              <label style="font-size:11.5px;">Keterangan</label>
              <input type="text" name="stops[{{ $index }}][note]" value="{{ $stop['note'] }}" placeholder="Titik awal keberangkatan">
            </div>
            <button type="button" class="btn-outline remove-stop" style="margin-bottom:2px;">✕</button>
          </div>
        @endforeach
      </div>
      <button type="button" class="btn-outline" id="addStop" style="margin-top:10px;">+ Tambah Titik</button>
    </div>

    <div class="form-actions">
      <a href="{{ route('admin.schedules.index') }}" class="btn-outline" style="padding:12px 20px;font-size:14px;">Batal</a>
      <button type="submit" class="btn btn-primary">{{ $schedule ? '✓ Simpan Perubahan' : '+ Tambah Jadwal' }}</button>
    </div>
  </form>
</div>

<script>
  (function () {
    var wrap = document.getElementById('stopsWrap');
    var addBtn = document.getElementById('addStop');
    var index = wrap.querySelectorAll('.stop-row').length;

    addBtn.addEventListener('click', function () {
      var first = wrap.querySelector('.stop-row');
      if (!first) return;
      var row = first.cloneNode(true);
      row.querySelectorAll('input').forEach(function (input) {
        input.value = '';
        input.name = input.name.replace(/stops\[\d+\]/, 'stops[' + index + ']');
      });
      index++;
      wrap.appendChild(row);
      bindRemove();
    });

    function bindRemove() {
      wrap.querySelectorAll('.remove-stop').forEach(function (btn) {
        btn.onclick = function () {
          if (wrap.querySelectorAll('.stop-row').length <= 1) {
            wrap.querySelectorAll('input').forEach(function (input) { input.value = ''; });
            return;
          }
          btn.closest('.stop-row').remove();
        };
      });
    }

    bindRemove();
  })();
</script>
@endsection

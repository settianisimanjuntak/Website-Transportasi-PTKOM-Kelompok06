@extends("layouts.admin")

@section("title", ($bus ? 'Edit Bus '.$bus->code : 'Tambah Bus') . ' — Admin TiketBus')

@section("content")
@php
  $selectedFacilities = old('facilities', $bus ? $bus->facilities->pluck('id')->all() : []);
  $statusLabels = ['operational' => 'Operasional', 'maintenance' => 'Perawatan', 'retired' => 'Pensiun'];
  $layouts = ['2-1', '2-2', '1-1', '3-2'];
@endphp

<div class="admin-top">
  <div>
    <a href="{{ route('admin.buses.index') }}" class="detail-link">← Kembali ke Data Bus</a>
  </div>
</div>

<h1 class="page-h">{{ $bus ? 'Edit Data Bus' : 'Tambah Bus Baru' }}</h1>
<div class="page-sub">{{ $bus ? 'Perbarui detail armada. Perubahan layout/kapasitas akan membuat ulang denah kursi.' : 'Masukkan detail armada baru. Denah kursi akan digenerate otomatis berdasarkan layout.' }}</div>

<div class="card admin-form">
  <form method="POST" action="{{ $bus ? route('admin.buses.update', $bus) : route('admin.buses.store') }}">
    @csrf
    @if ($bus) @method('PUT') @endif

    <div class="field-row">
      <div class="field">
        <label>Operator / PO *</label>
        <select name="operator_id" required>
          <option value="">— Pilih Operator —</option>
          @foreach ($operators as $operator)
            <option value="{{ $operator->id }}" @selected((string) old('operator_id', $bus?->operator_id) === (string) $operator->id)>{{ $operator->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="field">
        <label>Kelas Bus *</label>
        <select name="bus_class_id" required>
          <option value="">— Pilih Kelas —</option>
          @foreach ($classes as $class)
            <option value="{{ $class->id }}" @selected((string) old('bus_class_id', $bus?->bus_class_id) === (string) $class->id)>{{ $class->name }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label>Kode Bus *</label>
        <input type="text" name="code" value="{{ old('code', $bus?->code) }}" maxlength="20" placeholder="BUS-42-329" required>
      </div>
      <div class="field">
        <label>Model / Tipe Armada</label>
        <input type="text" name="model" value="{{ old('model', $bus?->model) }}" placeholder="Mercedes-Benz OH 1626">
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label>Kapasitas Kursi *</label>
        <input type="number" name="capacity" value="{{ old('capacity', $bus?->capacity) }}" min="1" max="60" required>
      </div>
      <div class="field">
        <label>Layout Kursi *</label>
        <select name="layout" required>
          @foreach ($layouts as $layout)
            <option value="{{ $layout }}" @selected(old('layout', $bus?->layout) === $layout)>{{ $layout }} {{ $layout === '2-2' ? '(Standar, 4/baris)' : ($layout === '2-1' ? '(Semi 3/baris)' : ($layout === '3-2' ? '(Super Executive 5/baris)' : '(Sleeper 2/baris)')) }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="field-row">
      <div class="field">
        <label>URL Foto Bus</label>
        <input type="url" name="photo" value="{{ old('photo', $bus?->photo) }}" placeholder="https://...">
      </div>
      <div class="field">
        <label>Status *</label>
        <select name="status" required>
          @foreach ($statusLabels as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $bus?->status ?? 'operational') === $value)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="field">
      <label>Fasilitas Bus</label>
      <div class="check-grid">
        @foreach ($facilities as $facility)
          <label class="check-item">
            <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" @checked(in_array($facility->id, $selectedFacilities))>
            {{ $facility->icon }} {{ $facility->name }}
          </label>
        @endforeach
      </div>
    </div>

    <div class="form-actions">
      <a href="{{ route('admin.buses.index') }}" class="btn-outline" style="padding:12px 20px;font-size:14px;">Batal</a>
      <button type="submit" class="btn btn-primary">{{ $bus ? '✓ Simpan Perubahan' : '+ Tambah Bus' }}</button>
    </div>
  </form>
</div>
@endsection

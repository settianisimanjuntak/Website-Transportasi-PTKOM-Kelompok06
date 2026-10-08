@extends("layouts.admin")

@section("title", "Data Bus — Admin TiketBus")

@section("content")
@php
  $statusLabels = ['operational' => 'Operasional', 'maintenance' => 'Perawatan', 'retired' => 'Pensiun'];
@endphp

<div class="admin-top">
  <form class="search-box" method="GET" action="{{ route('admin.buses.index') }}">
    <input type="text" name="q" value="{{ $q }}" placeholder="Cari kode bus, model, operator...">
  </form>
  <a href="{{ route('admin.buses.create') }}" class="btn-add">+ Tambah Bus Baru</a>
</div>

<h1 class="page-h">Data Bus &amp; Armada</h1>
<div class="page-sub">Kelola armada, kapasitas kursi, layout, dan fasilitas bus.</div>

<div class="card table-card">
  <div class="table-head">
    <div class="th-title">Menampilkan {{ $buses->count() }} dari {{ $buses->total() }} bus</div>
    <a href="{{ route('admin.buses.index') }}" class="btn-outline">⟳ Reset</a>
  </div>

  <table>
    <thead>
      <tr><th>Kode</th><th>Operator / Kelas</th><th>Model</th><th>Kapasitas</th><th>Fasilitas</th><th>Status</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @forelse ($buses as $bus)
        <tr>
          <td class="po-name">{{ $bus->code }}</td>
          <td>
            <div class="po-name">{{ $bus->operator->name }}</div>
            <div class="po-code"><span class="tag tag-{{ $bus->busClass->badge }}">{{ $bus->busClass->name }}</span></div>
          </td>
          <td>{{ $bus->model ?: '—' }}</td>
          <td>{{ $bus->capacity }} kursi <span class="po-code">(Layout {{ $bus->layout }})</span></td>
          <td>{{ $bus->facilities_count ?? $bus->facilities->count() }} item</td>
          <td><span class="status-pill {{ $bus->status === 'operational' ? 'pill-paid' : ($bus->status === 'maintenance' ? 'pill-pending' : 'pill-completed') }}">{{ $statusLabels[$bus->status] ?? $bus->status }}</span></td>
          <td style="white-space:nowrap;">
            <a class="detail-link" href="{{ route('admin.buses.edit', $bus) }}">Edit</a>
            <form method="POST" action="{{ route('admin.buses.destroy', $bus) }}" style="display:inline;margin-left:10px;" onsubmit="return confirm('Hapus bus {{ $bus->code }}? Pastikan tidak terkait jadwal.')">
              @csrf
              @method('DELETE')
              <button type="submit" class="detail-link" style="background:none;border:none;color:var(--red);cursor:pointer;font-family:inherit;padding:0;">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="7" style="text-align:center;color:var(--ink-soft);padding:32px;">Belum ada data bus.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="table-foot">
    <div>Halaman {{ $buses->currentPage() }} dari {{ $buses->lastPage() }}</div>
    {{ $buses->links('partials.pagination') }}
  </div>
</div>
@endsection

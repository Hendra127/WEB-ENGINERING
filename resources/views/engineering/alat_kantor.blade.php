@extends('layouts.app')
@section('title','Daftar Alat Kantor - Engineering')
@section('page-title','Daftar Alat Kantor')
@section('content')

@if(session('success'))
<div class="alert-success" id="alertBox"><i class="fas fa-check-circle"></i> {{ session('success') }}
  <button onclick="document.getElementById('alertBox').remove()" style="float:right;background:none;border:none;cursor:pointer;color:inherit"><i class="fas fa-times"></i></button>
</div>
@endif

<div class="card" style="margin-bottom:20px">
  <form method="GET" action="{{ route('engineering.alat') }}" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
    <div class="search-bar" style="flex:1;min-width:200px"><i class="fas fa-search"></i>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama alat...">
    </div>
    <select name="kondisi" style="width:160px">
      <option value="">Semua Kondisi</option>
      @foreach(['BAIK','RUSAK RINGAN','RUSAK BERAT'] as $k)
        <option value="{{ $k }}" {{ request('kondisi')==$k?'selected':'' }}>{{ $k }}</option>
      @endforeach
    </select>
    <input type="text" name="tempat" value="{{ request('tempat') }}" placeholder="Filter tempat..." style="width:140px">
    <button type="submit" class="btn btn-outline"><i class="fas fa-filter"></i> Filter</button>
    <a href="{{ route('engineering.alat') }}" class="btn btn-outline"><i class="fas fa-redo"></i></a>
    <button type="button" class="btn btn-primary" onclick="openModal('addAlatModal')"><i class="fas fa-plus"></i> Tambah</button>
  </form>
</div>

<div class="stats-grid" style="margin-bottom:20px">
  <div class="stat-card"><div class="stat-icon" style="background:rgba(16,185,129,.12);color:#10b981"><i class="fas fa-check-circle"></i></div><div><div class="stat-value">{{ $stats['baik'] }}</div><div class="stat-label">Kondisi Baik</div></div></div>
  <div class="stat-card"><div class="stat-icon" style="background:rgba(245,158,11,.12);color:#f59e0b"><i class="fas fa-exclamation-circle"></i></div><div><div class="stat-value">{{ $stats['ringan'] }}</div><div class="stat-label">Rusak Ringan</div></div></div>
  <div class="stat-card"><div class="stat-icon" style="background:rgba(239,68,68,.12);color:#ef4444"><i class="fas fa-times-circle"></i></div><div><div class="stat-value">{{ $stats['berat'] }}</div><div class="stat-label">Rusak Berat</div></div></div>
  <div class="stat-card"><div class="stat-icon" style="background:rgba(59,130,246,.12);color:#3b82f6"><i class="fas fa-boxes"></i></div><div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total Alat</div></div></div>
</div>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead><tr><th>#</th><th>Nama Tool</th><th>QTY</th><th>Satuan</th><th>Kondisi</th><th>Tempat</th><th>Keterangan</th><th class="sticky-col-head">Aksi</th></tr></thead>
      <tbody>
        @forelse($data as $i=>$row)
        <tr>
          <td>{{ $data->firstItem()+$i }}</td>
          <td style="font-weight:600">{{ $row->nama_tool }}</td>
          <td style="text-align:center;font-weight:700;color:var(--primary)">{{ $row->qty }}</td>
          <td>{{ $row->satuan }}</td>
          <td><span class="badge badge-{{ $row->kondisi==='BAIK'?'success':($row->kondisi==='RUSAK RINGAN'?'warning':'danger') }}"><i class="fas fa-circle" style="font-size:7px"></i> {{ $row->kondisi }}</span></td>
          <td>{{ $row->tempat ?: '-' }}</td>
          <td style="font-size:12px;color:var(--text2)">{{ $row->keterangan ?: '-' }}</td>
          <td class="sticky-col">
            <div style="display:flex;gap:4px;justify-content:center">
              <button class="btn btn-sm" style="background:rgba(59,130,246,.1);color:var(--primary);border:none" onclick="viewAlat({{ json_encode($row) }})" title="Detail"><i class="fas fa-info-circle"></i></button>
              <button class="btn btn-sm" style="background:rgba(245,158,11,.1);color:var(--warning);border:none" onclick="editAlat({{ json_encode($row) }})" title="Edit"><i class="fas fa-edit"></i></button>
              <form method="POST" action="{{ route('engineering.alat.destroy',$row) }}" onsubmit="return confirm('Hapus alat ini?')" style="margin:0">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,.1);color:var(--danger);border:none" title="Hapus"><i class="fas fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:32px;color:var(--text2)"><i class="fas fa-toolbox" style="font-size:32px;display:block;margin-bottom:8px"></i>Belum ada data alat kantor.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="margin-top:16px">{{ $data->links() }}</div>
</div>

{{-- Modal Tambah --}}
<div class="modal-overlay" id="addAlatModal">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title"><i class="fas fa-toolbox" style="color:var(--primary)"></i> Tambah Alat Kantor</span>
      <button class="btn-icon" onclick="closeModal('addAlatModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" action="{{ route('engineering.alat.store') }}">
      @csrf
      <div class="form-group"><label>Nama Tool *</label><input type="text" name="nama_tool" required placeholder="Nama alat..."></div>
      <div class="grid-2">
        <div class="form-group"><label>QTY *</label><input type="number" name="qty" value="1" min="1" required></div>
        <div class="form-group"><label>Satuan</label><select name="satuan"><option>UNIT</option><option>SET</option><option>PCS</option><option>BUAH</option></select></div>
      </div>
      <div class="grid-2">
        <div class="form-group"><label>Kondisi</label><select name="kondisi"><option>BAIK</option><option>RUSAK RINGAN</option><option>RUSAK BERAT</option></select></div>
        <div class="form-group"><label>Tempat</label><input type="text" name="tempat" placeholder="Lokasi penyimpanan..."></div>
      </div>
      <div class="form-group"><label>Keterangan</label><textarea name="keterangan" rows="2" placeholder="Keterangan tambahan..."></textarea></div>
      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
        <button type="button" class="btn btn-outline" onclick="closeModal('addAlatModal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit --}}
<div class="modal-overlay" id="editAlatModal">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title"><i class="fas fa-edit" style="color:var(--warning)"></i> Edit Alat Kantor</span>
      <button class="btn-icon" onclick="closeModal('editAlatModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" id="editAlatForm">
      @csrf @method('PUT')
      <div class="form-group"><label>Nama Tool *</label><input type="text" name="nama_tool" id="ea_nama" required></div>
      <div class="grid-2">
        <div class="form-group"><label>QTY *</label><input type="number" name="qty" id="ea_qty" min="1" required></div>
        <div class="form-group"><label>Satuan</label><select name="satuan" id="ea_satuan"><option>UNIT</option><option>SET</option><option>PCS</option><option>BUAH</option></select></div>
      </div>
      <div class="grid-2">
        <div class="form-group"><label>Kondisi</label><select name="kondisi" id="ea_kondisi"><option>BAIK</option><option>RUSAK RINGAN</option><option>RUSAK BERAT</option></select></div>
        <div class="form-group"><label>Tempat</label><input type="text" name="tempat" id="ea_tempat"></div>
      </div>
      <div class="form-group"><label>Keterangan</label><textarea name="keterangan" id="ea_ket" rows="2"></textarea></div>
      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
        <button type="button" class="btn btn-outline" onclick="closeModal('editAlatModal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal View Detail --}}
<div class="modal-overlay" id="viewAlatModal">
  <div class="modal" style="max-width:500px">
    <div class="modal-header">
      <span class="modal-title"><i class="fas fa-info-circle" style="color:var(--primary)"></i> Detail Alat Kantor</span>
      <button class="btn-icon" onclick="closeModal('viewAlatModal')"><i class="fas fa-times"></i></button>
    </div>
    <div id="viewAlatContent" style="font-size:14px">
      <!-- Content populated by JS -->
    </div>
    <div style="display:flex;justify-content:flex-end;margin-top:20px">
      <button type="button" class="btn btn-primary" onclick="closeModal('viewAlatModal')">Tutup</button>
    </div>
  </div>
</div>
@endsection
@section('extra-styles')
<style>
.alert-success{background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.3);color:#10b981;padding:12px 16px;border-radius:var(--radius-sm);margin-bottom:16px;font-weight:600;font-size:14px;}
.table-wrap { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; border-radius: var(--radius-sm); }
table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 800px; }
table th { 
  background: #f8fafc !important; 
  color: #475569 !important; 
  font-size: 11px !important; 
  font-weight: 700 !important; 
  text-transform: uppercase !important; 
  letter-spacing: 0.05em; 
  padding: 12px 16px !important;
  border-bottom: 1px solid #e2e8f0 !important;
  text-align: center !important;
}
table td { padding: 12px 16px !important; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
table tr:last-child td { border-bottom: none; }

/* Sticky Column Style */
.sticky-col {
  position: sticky;
  right: 0;
  background: white !important;
  z-index: 5;
  box-shadow: -4px 0 8px rgba(0,0,0,0.05);
}
.sticky-col-head {
  position: sticky;
  right: 0;
  background: #f8fafc !important;
  z-index: 6;
  box-shadow: -4px 0 8px rgba(0,0,0,0.05);
}
html.dark .sticky-col { background: var(--surface) !important; }
html.dark .sticky-col-head { background: var(--surface2) !important; }
</style>
@endsection
@section('scripts')
<script>
function openModal(id){document.getElementById(id).classList.add('open');}
function closeModal(id){document.getElementById(id).classList.remove('open');}
document.querySelectorAll('.modal-overlay').forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.classList.remove('open');}));
function editAlat(row){
  document.getElementById('editAlatForm').action='{{ url("engineering/alat-kantor") }}/'+row.id;
  document.getElementById('ea_nama').value=row.nama_tool||'';
  document.getElementById('ea_qty').value=row.qty||1;
  document.getElementById('ea_satuan').value=row.satuan||'UNIT';
  document.getElementById('ea_kondisi').value=row.kondisi||'BAIK';
  document.getElementById('ea_tempat').value=row.tempat||'';
  document.getElementById('ea_ket').value=row.keterangan||'';
  openModal('editAlatModal');
}
function viewAlat(row) {
  const content = `
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;padding:10px;background:var(--surface2);border-radius:var(--radius-sm)">
      <div><strong>Nama Tool:</strong><br>${row.nama_tool}</div>
      <div><strong>Qty:</strong><br>${row.qty} ${row.satuan}</div>
      <div><strong>Kondisi:</strong><br><span class="badge badge-${row.kondisi==='BAIK'?'success':(row.kondisi==='RUSAK RINGAN'?'warning':'danger')}">${row.kondisi}</span></div>
      <div><strong>Tempat:</strong><br>${row.tempat||'-'}</div>
    </div>
    <div><strong>Keterangan:</strong><br>${row.keterangan||'-'}</div>
  `;
  document.getElementById('viewAlatContent').innerHTML = content;
  openModal('viewAlatModal');
}
setTimeout(()=>{const a=document.getElementById('alertBox');if(a)a.remove();},4000);
</script>
@endsection

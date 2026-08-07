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
  <form id="filterForm" method="GET" action="{{ route('engineering.alat') }}" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
    <div class="search-bar" style="flex:1;min-width:200px"><i class="fas fa-search"></i>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama alat...">
    </div>
    <select name="kondisi" style="width:160px" onchange="this.form.submit()">
      <option value="">Semua Kondisi</option>
      @foreach(['BAIK','RUSAK RINGAN','RUSAK BERAT'] as $k)
        <option value="{{ $k }}" {{ request('kondisi')==$k?'selected':'' }}>{{ $k }}</option>
      @endforeach
    </select>
    <select name="tempat" style="width:160px" onchange="this.form.submit()">
      <option value="">Semua Tempat</option>
      @if(isset($tempatList))
        @foreach($tempatList as $tp)
          <option value="{{ $tp }}" {{ request('tempat')==$tp?'selected':'' }}>{{ $tp }}</option>
        @endforeach
      @endif
    </select>
    <a href="{{ route('engineering.alat') }}" class="btn btn-outline" title="Refresh / Reset Filter"><i class="fas fa-redo"></i></a>
    <button type="button" class="btn btn-primary" onclick="openModal('addAlatModal')"><i class="fas fa-plus"></i> Tambah</button>
  </form>
</div>

<div class="stats-grid" style="margin-bottom:20px">
  @foreach([
    ['Kondisi Baik', 'fas fa-check-circle', '#10b981', 'rgba(16,185,129,.12)', $stats['baik'], 'BAIK'],
    ['Rusak Ringan', 'fas fa-exclamation-circle', '#f59e0b', 'rgba(245,158,11,.12)', $stats['ringan'], 'RUSAK RINGAN'],
    ['Rusak Berat', 'fas fa-times-circle', '#ef4444', 'rgba(239,68,68,.12)', $stats['berat'], 'RUSAK BERAT'],
    ['Total Alat', 'fas fa-boxes', '#3b82f6', 'rgba(59,130,246,.12)', $stats['total'], '']
  ] as $st)
  <div class="stat-card" 
       onclick="const url = new URL(window.location.href); if('{{ $st[5] }}') url.searchParams.set('kondisi', '{{ $st[5] }}'); else url.searchParams.delete('kondisi'); window.location.href=url.href;"
       style="cursor:pointer; transition: transform 0.2s, box-shadow 0.2s; {{ (request('kondisi') == $st[5] || (request('kondisi') == '' && $st[5] == '')) ? 'border: 1.5px solid '.$st[2].'; box-shadow: var(--shadow-lg);' : '' }}"
       onmouseover="this.style.transform='translateY(-3px)'"
       onmouseout="this.style.transform='translateY(0)'"
       title="Klik untuk filter {{ $st[0] }}">
    <div class="stat-icon" style="background:{{ $st[3] }};color:{{ $st[2] }}"><i class="{{ $st[1] }}"></i></div>
    <div>
      <div class="stat-value">{{ $st[4] }}</div>
      <div class="stat-label">{{ $st[0] }}</div>
    </div>
  </div>
  @endforeach
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
          <td class="sticky-col" style="vertical-align: middle;">
            <div style="display:flex;gap:16px;justify-content:center;align-items:center;height:100%;">
              <button style="background:none;border:none;color:var(--text);font-size:20px;cursor:pointer;padding:0;transition:transform 0.2s;" onclick="viewAlat({{ json_encode($row) }})" title="Detail" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'"><i class="far fa-eye" style="-webkit-text-stroke: 0.5px var(--surface);"></i></button>
              <button style="background:none;border:none;color:var(--text);font-size:20px;cursor:pointer;padding:0;transition:transform 0.2s;" onclick="editAlat({{ json_encode($row) }})" title="Edit" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'"><i class="far fa-edit" style="-webkit-text-stroke: 0.5px var(--surface);"></i></button>
              <form method="POST" action="{{ route('engineering.alat.destroy',$row) }}" onsubmit="return confirm('Hapus alat ini?')" style="margin:0;display:flex;align-items:center;">
                @csrf @method('DELETE')
                <button type="submit" style="background:none;border:none;color:var(--text);font-size:20px;cursor:pointer;padding:0;transition:transform 0.2s;" title="Hapus" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'"><i class="far fa-trash-alt" style="-webkit-text-stroke: 0.5px var(--surface);"></i></button>
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
        <div class="form-group"><label>Tempat</label>
          <input type="text" name="tempat" list="tempatListOptions" placeholder="Lokasi penyimpanan...">
          <datalist id="tempatListOptions">
            @if(isset($tempatList))
              @foreach($tempatList as $tp)
                <option value="{{ $tp }}">
              @endforeach
            @endif
          </datalist>
        </div>
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
        <div class="form-group"><label>Tempat</label>
          <input type="text" name="tempat" id="ea_tempat" list="tempatListOptions" placeholder="Lokasi penyimpanan...">
        </div>
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
  background: var(--surface2) !important; 
  color: var(--text) !important; 
  font-size: 11px !important; 
  font-weight: 700 !important; 
  text-transform: uppercase !important; 
  letter-spacing: 0.05em; 
  padding: 12px 16px !important;
  border-bottom: 1px solid var(--border) !important;
  text-align: center !important;
}
table td { padding: 12px 16px !important; border-bottom: 1px solid var(--border); vertical-align: middle; }
table tr:last-child td { border-bottom: none; }

/* Sticky Column Style */
.sticky-col {
  position: sticky;
  right: 0;
  background: var(--surface) !important;
  z-index: 5;
  box-shadow: -4px 0 8px rgba(0,0,0,0.05);
}
.sticky-col-head {
  position: sticky;
  right: 0;
  background: var(--surface2) !important;
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

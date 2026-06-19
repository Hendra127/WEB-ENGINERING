@extends('layouts.app')
@section('title','Log Barang Masuk dan Keluar - Engineering')
@section('page-title','Log Barang Masuk dan Keluar')
@section('content')

@if(session('success'))
<div class="alert-success" id="alertBox"><i class="fas fa-check-circle"></i> {{ session('success') }}
  <button onclick="document.getElementById('alertBox').remove()" style="float:right;background:none;border:none;cursor:pointer;color:inherit"><i class="fas fa-times"></i></button>
</div>
@endif

<div class="card" style="margin-bottom:20px">
  <form method="GET" action="{{ route('engineering.klasifikasi') }}" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
    <div class="search-bar" style="flex:1;min-width:200px"><i class="fas fa-search"></i>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang...">
    </div>
    <select name="status" style="width:160px">
      <option value="">Semua Status</option>
      <option value="MASUK" {{ request('status')=='MASUK'?'selected':'' }}>MASUK</option>
      <option value="KELUAR" {{ request('status')=='KELUAR'?'selected':'' }}>KELUAR</option>
    </select>
    <button type="submit" class="btn btn-outline"><i class="fas fa-filter"></i> Filter</button>
    <a href="{{ route('engineering.klasifikasi') }}" class="btn btn-outline"><i class="fas fa-redo"></i></a>
    <button type="button" class="btn btn-primary" onclick="openModal('addKlasModal')"><i class="fas fa-plus"></i> Tambah</button>
  </form>
</div>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th><th>Tgl Masuk</th><th>Tgl Keluar</th><th>Nama Barang</th>
          <th>QTY</th><th>Satuan</th><th>Nama Penerima</th><th>Lokasi</th><th>Status</th><th class="sticky-col-head">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($data as $i=>$row)
        <tr>
          <td>{{ $data->firstItem()+$i }}</td>
          <td style="white-space:nowrap">{{ $row->tgl_masuk?->format('d/m/Y') ?: '-' }}</td>
          <td style="white-space:nowrap">{{ $row->tgl_keluar?->format('d/m/Y') ?: '-' }}</td>
          <td style="font-weight:600">{{ $row->nama_barang }}</td>
          <td style="text-align:center;font-weight:700;color:var(--primary)">{{ $row->qty }}</td>
          <td>{{ $row->satuan }}</td>
          <td>{{ $row->nama_penerima ?: '-' }}</td>
          <td>{{ $row->lokasi ?: '-' }}</td>
          <td>
            <span class="badge badge-{{ $row->status==='MASUK'?'success':'warning' }}">
              <i class="fas fa-{{ $row->status==='MASUK'?'arrow-down':'arrow-up' }}"></i> {{ $row->status }}
            </span>
          </td>
          <td class="sticky-col">
            <div style="display:flex;gap:4px;justify-content:center">
              <button class="btn btn-sm" style="background:rgba(59,130,246,.1);color:var(--primary);border:none" onclick="viewKlas({{ json_encode($row) }})" title="Detail"><i class="fas fa-info-circle"></i></button>
              <button class="btn btn-sm" style="background:rgba(245,158,11,.1);color:var(--warning);border:none" onclick="editKlas({{ json_encode($row) }})" title="Edit"><i class="fas fa-edit"></i></button>
              <form method="POST" action="{{ route('engineering.klasifikasi.destroy',$row) }}" onsubmit="return confirm('Hapus data ini?')" style="margin:0">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,.1);color:var(--danger);border:none" title="Hapus"><i class="fas fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="10" style="text-align:center;padding:32px;color:var(--text2)"><i class="fas fa-boxes" style="font-size:32px;display:block;margin-bottom:8px"></i>Belum ada data log barang masuk dan keluar.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="margin-top:16px">{{ $data->links() }}</div>
</div>

{{-- Modal Tambah --}}
<div class="modal-overlay" id="addKlasModal">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title"><i class="fas fa-boxes" style="color:var(--primary)"></i> Tambah Log Barang Masuk dan Keluar</span>
      <button class="btn-icon" onclick="closeModal('addKlasModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" action="{{ route('engineering.klasifikasi.store') }}">
      @csrf
      <div class="grid-2">
        <div class="form-group"><label>Tgl Barang Masuk</label><input type="date" name="tgl_masuk"></div>
        <div class="form-group"><label>Tgl Barang Keluar</label><input type="date" name="tgl_keluar"></div>
      </div>
      <div class="form-group"><label>Nama Barang *</label><input type="text" name="nama_barang" required placeholder="Nama barang..."></div>
      <div class="grid-2">
        <div class="form-group"><label>QTY *</label><input type="number" name="qty" value="1" min="1" required></div>
        <div class="form-group"><label>Satuan</label><select name="satuan"><option>Unit</option><option>Pcs</option><option>Set</option><option>Roll</option><option>Meter</option><option>Liter</option><option>Kg</option></select></div>
      </div>
      <div class="grid-2">
        <div class="form-group"><label>Nama Penerima</label><input type="text" name="nama_penerima" placeholder="Nama penerima..."></div>
        <div class="form-group"><label>Lokasi</label><input type="text" name="lokasi" placeholder="Lokasi..."></div>
      </div>
      <div class="form-group"><label>Status</label><select name="status"><option>MASUK</option><option>KELUAR</option></select></div>
      <div class="form-group"><label>Keterangan</label><textarea name="keterangan" rows="2"></textarea></div>
      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
        <button type="button" class="btn btn-outline" onclick="closeModal('addKlasModal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit --}}
<div class="modal-overlay" id="editKlasModal">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title"><i class="fas fa-edit" style="color:var(--warning)"></i> Edit Log Barang Masuk dan Keluar</span>
      <button class="btn-icon" onclick="closeModal('editKlasModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" id="editKlasForm">
      @csrf @method('PUT')
      <div class="grid-2">
        <div class="form-group"><label>Tgl Barang Masuk</label><input type="date" name="tgl_masuk" id="ek_masuk"></div>
        <div class="form-group"><label>Tgl Barang Keluar</label><input type="date" name="tgl_keluar" id="ek_keluar"></div>
      </div>
      <div class="form-group"><label>Nama Barang *</label><input type="text" name="nama_barang" id="ek_nama" required></div>
      <div class="grid-2">
        <div class="form-group"><label>QTY *</label><input type="number" name="qty" id="ek_qty" min="1" required></div>
        <div class="form-group"><label>Satuan</label><select name="satuan" id="ek_satuan"><option>Unit</option><option>Pcs</option><option>Set</option><option>Roll</option><option>Meter</option><option>Liter</option><option>Kg</option></select></div>
      </div>
      <div class="grid-2">
        <div class="form-group"><label>Nama Penerima</label><input type="text" name="nama_penerima" id="ek_penerima"></div>
        <div class="form-group"><label>Lokasi</label><input type="text" name="lokasi" id="ek_lokasi"></div>
      </div>
      <div class="form-group"><label>Status</label><select name="status" id="ek_status"><option>MASUK</option><option>KELUAR</option></select></div>
      <div class="form-group"><label>Keterangan</label><textarea name="keterangan" id="ek_ket" rows="2"></textarea></div>
      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
        <button type="button" class="btn btn-outline" onclick="closeModal('editKlasModal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal View Detail --}}
<div class="modal-overlay" id="viewKlasModal">
  <div class="modal" style="max-width:500px">
    <div class="modal-header">
      <span class="modal-title"><i class="fas fa-info-circle" style="color:var(--primary)"></i> Detail Log Barang Masuk dan Keluar</span>
      <button class="btn-icon" onclick="closeModal('viewKlasModal')"><i class="fas fa-times"></i></button>
    </div>
    <div id="viewKlasContent" style="font-size:14px">
      <!-- Content populated by JS -->
    </div>
    <div style="display:flex;justify-content:flex-end;margin-top:20px">
      <button type="button" class="btn btn-primary" onclick="closeModal('viewKlasModal')">Tutup</button>
    </div>
  </div>
</div>
@endsection
@section('extra-styles')
<style>
.alert-success{background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.3);color:#10b981;padding:12px 16px;border-radius:var(--radius-sm);margin-bottom:16px;font-weight:600;font-size:14px;}
.table-wrap { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; border-radius: var(--radius-sm); }
table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 1000px; }
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
function editKlas(row){
  document.getElementById('editKlasForm').action='{{ url("engineering/klasifikasi") }}/'+row.id;
  document.getElementById('ek_masuk').value=row.tgl_masuk?row.tgl_masuk.substring(0,10):'';
  document.getElementById('ek_keluar').value=row.tgl_keluar?row.tgl_keluar.substring(0,10):'';
  document.getElementById('ek_nama').value=row.nama_barang||'';
  document.getElementById('ek_qty').value=row.qty||1;
  document.getElementById('ek_satuan').value=row.satuan||'Unit';
  document.getElementById('ek_penerima').value=row.nama_penerima||'';
  document.getElementById('ek_lokasi').value=row.lokasi||'';
  document.getElementById('ek_status').value=row.status||'MASUK';
  document.getElementById('ek_ket').value=row.keterangan||'';
  openModal('editKlasModal');
}
function viewKlas(row) {
  const content = `
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;padding:10px;background:var(--surface2);border-radius:var(--radius-sm)">
      <div><strong>Nama Barang:</strong><br>${row.nama_barang}</div>
      <div><strong>Qty:</strong><br>${row.qty} ${row.satuan}</div>
      <div><strong>Tgl Masuk:</strong><br>${row.tgl_masuk ? new Date(row.tgl_masuk).toLocaleDateString('id-ID') : '-'}</div>
      <div><strong>Tgl Keluar:</strong><br>${row.tgl_keluar ? new Date(row.tgl_keluar).toLocaleDateString('id-ID') : '-'}</div>
      <div><strong>Nama Penerima:</strong><br>${row.nama_penerima||'-'}</div>
      <div><strong>Lokasi:</strong><br>${row.lokasi||'-'}</div>
      <div style="grid-column: span 2"><strong>Status:</strong><br><span class="badge badge-${row.status==='MASUK'?'success':'warning'}">${row.status}</span></div>
    </div>
    <div><strong>Keterangan:</strong><br>${row.keterangan||'-'}</div>
  `;
  document.getElementById('viewKlasContent').innerHTML = content;
  openModal('viewKlasModal');
}
setTimeout(()=>{const a=document.getElementById('alertBox');if(a)a.remove();},4000);
</script>
@endsection

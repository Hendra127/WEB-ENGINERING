@extends('layouts.app')
@section('title','Peminjaman Alat - Engineering')
@section('page-title','Peminjaman Alat')
@section('content')

@if(session('success'))
<div class="alert-success" id="alertBox"><i class="fas fa-check-circle"></i> {{ session('success') }}
  <button onclick="document.getElementById('alertBox').remove()" style="float:right;background:none;border:none;cursor:pointer;color:inherit"><i class="fas fa-times"></i></button>
</div>
@endif

@if($errors->any())
<div class="alert-danger" id="errorBox" style="background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.3);color:#ef4444;padding:12px 16px;border-radius:var(--radius-sm);margin-bottom:16px;font-weight:600;font-size:14px;">
  <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
  <button onclick="document.getElementById('errorBox').remove()" style="float:right;background:none;border:none;cursor:pointer;color:inherit"><i class="fas fa-times"></i></button>
</div>
@endif

<div class="card" style="margin-bottom:20px">
  <form method="GET" action="{{ route('engineering.peminjaman') }}" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
    <div class="search-bar" style="flex:1;min-width:200px"><i class="fas fa-search"></i>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam atau alat...">
    </div>
    <select name="status" style="width:160px" onchange="this.form.submit()">
      <option value="">Semua Status</option>
      <option value="DIPINJAM" {{ request('status')=='DIPINJAM'?'selected':'' }}>DIPINJAM</option>
      <option value="DIKEMBALI" {{ request('status')=='DIKEMBALI'?'selected':'' }}>DIKEMBALI</option>
    </select>
    <div style="display:flex;align-items:center;gap:6px;font-size:13px">
      <input type="date" name="start_date" value="{{ request('start_date') }}" title="Tanggal Pinjam Mulai" style="padding:7px 10px;border-radius:var(--radius-sm);border:1px solid var(--border);background:var(--surface);color:var(--text)" onchange="this.form.submit()">
      <span>s/d</span>
      <input type="date" name="end_date" value="{{ request('end_date') }}" title="Tanggal Pinjam Selesai" style="padding:7px 10px;border-radius:var(--radius-sm);border:1px solid var(--border);background:var(--surface);color:var(--text)" onchange="this.form.submit()">
    </div>
    <a href="{{ route('engineering.peminjaman') }}" class="btn btn-outline" title="Refresh / Reset Filter"><i class="fas fa-redo"></i></a>
    <button type="button" class="btn btn-primary" onclick="openModal('addPemModal')"><i class="fas fa-plus"></i> Tambah</button>
  </form>
</div>

<div class="stats-grid" style="margin-bottom:20px">
  <div class="stat-card"><div class="stat-icon" style="background:rgba(239,68,68,.12);color:#ef4444"><i class="fas fa-hand-holding"></i></div><div><div class="stat-value">{{ $stats['dipinjam'] }}</div><div class="stat-label">Sedang Dipinjam</div></div></div>
  <div class="stat-card"><div class="stat-icon" style="background:rgba(16,185,129,.12);color:#10b981"><i class="fas fa-undo"></i></div><div><div class="stat-value">{{ $stats['dikembali'] }}</div><div class="stat-label">Sudah Kembali</div></div></div>
  <div class="stat-card"><div class="stat-icon" style="background:rgba(59,130,246,.12);color:#3b82f6"><i class="fas fa-exchange-alt"></i></div><div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total Transaksi</div></div></div>
</div>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th><th>Nama Alat</th><th>QTY</th><th>Nama Peminjam</th>
          <th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th><th class="sticky-col-head">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($data as $i=>$row)
        <tr>
          <td>{{ $data->firstItem()+$i }}</td>
          <td style="font-weight:600">
            @if($row->alatKantor)
              <a href="{{ route('engineering.alat') }}?search={{ urlencode($row->alatKantor->nama_tool) }}" style="color:var(--primary);text-decoration:none" title="Lihat detail alat kantor">
                <i class="fas fa-toolbox"></i> {{ $row->nama_alat }}
              </a>
            @else
              <i class="fas fa-tools" style="color:var(--text2)"></i> {{ $row->nama_alat }}
            @endif
          </td>
          <td style="text-align:center;font-weight:700;color:var(--primary)">{{ $row->qty }}</td>
          <td>{{ $row->nama_peminjam }}</td>
          <td>{{ $row->tgl_pinjam?->format('d/m/Y') ?: '-' }}</td>
          <td>{{ $row->tgl_kembali?->format('d/m/Y') ?: '-' }}</td>
          <td>
            <span class="badge badge-{{ $row->status==='DIPINJAM'?'danger':'success' }}">
              <i class="fas fa-{{ $row->status==='DIPINJAM'?'exclamation-circle':'check-circle' }}"></i> {{ $row->status }}
            </span>
          </td>
          <td class="sticky-col">
            <div style="display:flex;gap:4px;justify-content:center">
              <button class="btn btn-sm" style="background:rgba(59,130,246,.1);color:var(--primary);border:none" onclick="viewPem({{ json_encode($row) }})" title="Detail"><i class="fas fa-info-circle"></i></button>
              <button class="btn btn-sm" style="background:rgba(245,158,11,.1);color:var(--warning);border:none" onclick="editPem({{ json_encode($row) }})" title="Edit"><i class="fas fa-edit"></i></button>
              <form method="POST" action="{{ route('engineering.peminjaman.destroy',$row) }}" onsubmit="return confirm('Hapus data peminjaman ini?')" style="margin:0">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,.1);color:var(--danger);border:none" title="Hapus"><i class="fas fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:32px;color:var(--text2)"><i class="fas fa-dolly-flatbed" style="font-size:32px;display:block;margin-bottom:8px"></i>Belum ada data peminjaman alat.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="margin-top:16px">{{ $data->links() }}</div>
</div>

{{-- Modal Tambah --}}
<div class="modal-overlay" id="addPemModal">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title"><i class="fas fa-hand-holding" style="color:var(--primary)"></i> Catat Peminjaman Alat</span>
      <button class="btn-icon" onclick="closeModal('addPemModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" action="{{ route('engineering.peminjaman.store') }}">
      @csrf
      <div class="form-group">
        <label>Pilih Alat Kantor *</label>
        <div style="display:flex;gap:6px;margin-bottom:6px">
          <input type="text" id="add_alat_search" placeholder="Cari nama alat di database..." style="flex:1" onkeyup="filterAlatSelect('add')">
          <button type="button" class="btn btn-outline" onclick="filterAlatSelect('add')"><i class="fas fa-search"></i> Cari</button>
        </div>
        <select name="alat_kantor_id" id="add_alat_kantor_id" onchange="toggleCustomAlat('add')">
          <option value="">-- Ketik Nama Alat Manual --</option>
          @foreach($alatList as $alat)
            <option value="{{ $alat->id }}" data-search="{{ strtolower($alat->nama_tool.' '.$alat->kondisi.' '.$alat->tempat) }}">{{ $alat->nama_tool }} (Kondisi: {{ $alat->kondisi }} - Tempat: {{ $alat->tempat ?: '-' }})</option>
          @endforeach
        </select>
      </div>
      
      <div class="form-group" id="add_custom_alat_wrapper">
        <label>Nama Alat (Ketik Manual) *</label>
        <input type="text" name="nama_alat" id="add_nama_alat" placeholder="Ketik nama alat lainnya...">
      </div>

      <div class="form-group">
        <label>Nama Peminjam / Teknisi *</label>
        <input type="text" name="nama_peminjam" required placeholder="Nama orang atau teknisi...">
      </div>

      <div class="grid-2">
        <div class="form-group">
          <label>QTY *</label>
          <input type="number" name="qty" value="1" min="1" required>
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="status">
            <option value="DIPINJAM">DIPINJAM</option>
            <option value="DIKEMBALI">DIKEMBALI</option>
          </select>
        </div>
      </div>

      <div class="grid-2">
        <div class="form-group">
          <label>Tanggal Pinjam *</label>
          <input type="date" name="tgl_pinjam" id="add_tgl_pinjam" value="{{ date('Y-m-d') }}" required onchange="updateMaxReturnDate('add')">
        </div>
        <div class="form-group">
          <label>Tanggal Kembali <small style="color:var(--primary)">(Max 7 Hari)</small></label>
          <input type="date" name="tgl_kembali" id="add_tgl_kembali" onchange="validateMaxDuration('add')">
        </div>
      </div>

      <div class="form-group">
        <label>Keterangan</label>
        <textarea name="keterangan" rows="2" placeholder="Tujuan peminjaman, lokasi pekerjaan, dll..."></textarea>
      </div>

      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
        <button type="button" class="btn btn-outline" onclick="closeModal('addPemModal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit --}}
<div class="modal-overlay" id="editPemModal">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title"><i class="fas fa-edit" style="color:var(--warning)"></i> Edit Peminjaman Alat</span>
      <button class="btn-icon" onclick="closeModal('editPemModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" id="editPemForm">
      @csrf @method('PUT')
      <div class="form-group">
        <label>Pilih Alat Kantor *</label>
        <div style="display:flex;gap:6px;margin-bottom:6px">
          <input type="text" id="edit_alat_search" placeholder="Cari nama alat di database..." style="flex:1" onkeyup="filterAlatSelect('edit')">
          <button type="button" class="btn btn-outline" onclick="filterAlatSelect('edit')"><i class="fas fa-search"></i> Cari</button>
        </div>
        <select name="alat_kantor_id" id="edit_alat_kantor_id" onchange="toggleCustomAlat('edit')">
          <option value="">-- Ketik Nama Alat Manual --</option>
          @foreach($alatList as $alat)
            <option value="{{ $alat->id }}" data-search="{{ strtolower($alat->nama_tool.' '.$alat->kondisi.' '.$alat->tempat) }}">{{ $alat->nama_tool }}</option>
          @endforeach
        </select>
      </div>
      
      <div class="form-group" id="edit_custom_alat_wrapper">
        <label>Nama Alat (Ketik Manual) *</label>
        <input type="text" name="nama_alat" id="edit_nama_alat" placeholder="Ketik nama alat lainnya...">
      </div>

      <div class="form-group">
        <label>Nama Peminjam / Teknisi *</label>
        <input type="text" name="nama_peminjam" id="ep_peminjam" required>
      </div>

      <div class="grid-2">
        <div class="form-group">
          <label>QTY *</label>
          <input type="number" name="qty" id="ep_qty" min="1" required>
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="status" id="ep_status">
            <option value="DIPINJAM">DIPINJAM</option>
            <option value="DIKEMBALI">DIKEMBALI</option>
          </select>
        </div>
      </div>

      <div class="grid-2">
        <div class="form-group">
          <label>Tanggal Pinjam *</label>
          <input type="date" name="tgl_pinjam" id="ep_pinjam" required onchange="updateMaxReturnDate('edit')">
        </div>
        <div class="form-group">
          <label>Tanggal Kembali <small style="color:var(--primary)">(Max 7 Hari)</small></label>
          <input type="date" name="tgl_kembali" id="ep_kembali" onchange="validateMaxDuration('edit')">
        </div>
      </div>

      <div class="form-group">
        <label>Keterangan</label>
        <textarea name="keterangan" id="ep_ket" rows="2"></textarea>
      </div>

      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
        <button type="button" class="btn btn-outline" onclick="closeModal('editPemModal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Detail --}}
<div class="modal-overlay" id="viewPemModal">
  <div class="modal" style="max-width:500px">
    <div class="modal-header">
      <span class="modal-title"><i class="fas fa-info-circle" style="color:var(--primary)"></i> Detail Peminjaman Alat</span>
      <button class="btn-icon" onclick="closeModal('viewPemModal')"><i class="fas fa-times"></i></button>
    </div>
    <div id="viewPemContent" style="font-size:14px">
      <!-- Content populated by JS -->
    </div>
    <div style="display:flex;justify-content:flex-end;margin-top:20px">
      <button type="button" class="btn btn-primary" onclick="closeModal('viewPemModal')">Tutup</button>
    </div>
  </div>
</div>

@endsection

@section('extra-styles')
<style>
.alert-success{background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.3);color:#10b981;padding:12px 16px;border-radius:var(--radius-sm);margin-bottom:16px;font-weight:600;font-size:14px;}
.table-wrap { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; border-radius: var(--radius-sm); }
table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 900px; }
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

function toggleCustomAlat(mode) {
  const select = document.getElementById(mode + '_alat_kantor_id');
  const wrapper = document.getElementById(mode + '_custom_alat_wrapper');
  const input = document.getElementById(mode + '_nama_alat');
  if (select.value === '') {
    wrapper.style.display = 'block';
    input.required = true;
  } else {
    wrapper.style.display = 'none';
    input.required = false;
    input.value = '';
  }
}

function filterAlatSelect(mode) {
  const query = document.getElementById(mode + '_alat_search').value.toLowerCase().trim();
  const select = document.getElementById(mode + '_alat_kantor_id');
  const options = select.options;
  
  for (let i = 0; i < options.length; i++) {
    const opt = options[i];
    if (opt.value === '') continue; // Skip default manual option
    const searchData = opt.getAttribute('data-search') || opt.text.toLowerCase();
    if (searchData.includes(query)) {
      opt.style.display = '';
    } else {
      opt.style.display = 'none';
    }
  }
}

function updateMaxReturnDate(mode) {
  const pinjamInput = mode === 'add' ? document.getElementById('add_tgl_pinjam') : document.getElementById('ep_pinjam');
  const kembaliInput = mode === 'add' ? document.getElementById('add_tgl_kembali') : document.getElementById('ep_kembali');
  
  if (!pinjamInput.value) return;

  const pinjamDate = new Date(pinjamInput.value);
  const minDateStr = pinjamInput.value;
  
  // Max date is 7 days after pinjamDate
  const maxDate = new Date(pinjamDate);
  maxDate.setDate(maxDate.getDate() + 7);
  const maxDateStr = maxDate.toISOString().split('T')[0];
  
  kembaliInput.setAttribute('min', minDateStr);
  kembaliInput.setAttribute('max', maxDateStr);

  if (kembaliInput.value) {
    validateMaxDuration(mode);
  }
}

function validateMaxDuration(mode) {
  const pinjamInput = mode === 'add' ? document.getElementById('add_tgl_pinjam') : document.getElementById('ep_pinjam');
  const kembaliInput = mode === 'add' ? document.getElementById('add_tgl_kembali') : document.getElementById('ep_kembali');

  if (pinjamInput.value && kembaliInput.value) {
    const pinjamDate = new Date(pinjamInput.value);
    const kembaliDate = new Date(kembaliInput.value);
    const diffTime = kembaliDate - pinjamDate;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays < 0) {
      alert('Tanggal kembali tidak boleh sebelum tanggal pinjam!');
      kembaliInput.value = '';
    } else if (diffDays > 7) {
      alert('Durasi peminjaman tidak boleh lebih dari 7 hari dari tanggal peminjaman!');
      kembaliInput.value = '';
    }
  }
}

// Trigger initial toggle state on load
document.addEventListener('DOMContentLoaded', () => {
  toggleCustomAlat('add');
  updateMaxReturnDate('add');
});

function editPem(row){
  document.getElementById('editPemForm').action='{{ url("engineering/peminjaman-alat") }}/'+row.id;
  document.getElementById('edit_alat_kantor_id').value=row.alat_kantor_id||'';
  document.getElementById('edit_nama_alat').value=row.alat_kantor_id?'':(row.nama_alat||'');
  document.getElementById('ep_peminjam').value=row.nama_peminjam||'';
  document.getElementById('ep_qty').value=row.qty||1;
  document.getElementById('ep_status').value=row.status||'DIPINJAM';
  document.getElementById('ep_pinjam').value=row.tgl_pinjam?row.tgl_pinjam.substring(0,10):'';
  document.getElementById('ep_kembali').value=row.tgl_kembali?row.tgl_kembali.substring(0,10):'';
  document.getElementById('ep_ket').value=row.keterangan||'';
  
  toggleCustomAlat('edit');
  openModal('editPemModal');
}

function viewPem(row) {
  const tglPinjamFormatted = row.tgl_pinjam ? new Date(row.tgl_pinjam).toLocaleDateString('id-ID') : '-';
  const tglKembaliFormatted = row.tgl_kembali ? new Date(row.tgl_kembali).toLocaleDateString('id-ID') : '-';
  
  const content = `
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;padding:10px;background:var(--surface2);border-radius:var(--radius-sm)">
      <div style="grid-column: span 2"><strong>Nama Alat:</strong><br>${row.nama_alat} ${row.alat_kantor_id ? '<span style="font-size:11px;color:var(--primary);">(Inventaris Kantor)</span>' : '<span style="font-size:11px;color:var(--text2);">(Ketik Manual)</span>'}</div>
      <div><strong>Qty:</strong><br>${row.qty} UNIT</div>
      <div><strong>Nama Peminjam / Teknisi:</strong><br>${row.nama_peminjam}</div>
      <div><strong>Tanggal Pinjam:</strong><br>${tglPinjamFormatted}</div>
      <div><strong>Tanggal Kembali:</strong><br>${tglKembaliFormatted}</div>
      <div style="grid-column: span 2"><strong>Status:</strong><br><span class="badge badge-${row.status==='DIPINJAM'?'danger':'success'}">${row.status}</span></div>
    </div>
    <div><strong>Keterangan:</strong><br>${row.keterangan||'-'}</div>
  `;
  document.getElementById('viewPemContent').innerHTML = content;
  openModal('viewPemModal');
}

setTimeout(()=>{const a=document.getElementById('alertBox');if(a)a.remove();},4000);
</script>
@endsection

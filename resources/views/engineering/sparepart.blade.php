@extends('layouts.app')
@section('title','Sparepart Needed - Engineering')
@section('page-title','Sparepart Needed')
@section('content')

{{-- Alert --}}
@if(session('success'))
<div class="alert-success" id="alertBox">
  <i class="fas fa-check-circle"></i> {{ session('success') }}
  <button onclick="document.getElementById('alertBox').remove()" style="float:right;background:none;border:none;cursor:pointer;color:inherit"><i class="fas fa-times"></i></button>
</div>
@endif

{{-- Filter Bar --}}
<div class="card" style="margin-bottom:20px">
  <form method="GET" action="{{ route('engineering.sparepart') }}" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
    <div class="search-bar" style="flex:1;min-width:200px">
      <i class="fas fa-search"></i>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari lokasi, jenis, teknisi...">
    </div>
    <select name="status" style="width:160px">
      <option value="">Semua Status</option>
      @foreach(['DONE','PROSES','PENDING'] as $s)
        <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ $s }}</option>
      @endforeach
    </select>
    <button type="submit" class="btn btn-outline"><i class="fas fa-filter"></i> Filter</button>
    <a href="{{ route('engineering.sparepart') }}" class="btn btn-outline"><i class="fas fa-redo"></i></a>
    <button type="button" class="btn btn-primary" onclick="openModal('addModal')"><i class="fas fa-plus"></i> Tambah</button>
  </form>
</div>

{{-- Stats --}}
<div class="stats-grid" style="margin-bottom:20px">
  @foreach([
    ['Total', 'fas fa-tools', 'primary', $counts['total'], ''],
    ['DONE', 'fas fa-check-circle', 'success', $counts['DONE'], 'DONE'],
    ['PROSES', 'fas fa-spinner', 'warning', $counts['PROSES'], 'PROSES'],
    ['PENDING', 'fas fa-clock', 'danger', $counts['PENDING'], 'PENDING']
  ] as $st)
  <div class="stat-card" 
       onclick="const url = new URL(window.location.href); if('{{ $st[4] }}') url.searchParams.set('status', '{{ $st[4] }}'); else url.searchParams.delete('status'); window.location.href=url.href;"
       style="cursor:pointer; transition: transform 0.2s; {{ (request('status') == $st[4] || (request('status') == '' && $st[4] == '')) ? 'border: 1.5px solid var(--'.$st[2].'); box-shadow: var(--shadow-lg);' : '' }}"
       onmouseover="this.style.transform='translateY(-3px)'"
       onmouseout="this.style.transform='translateY(0)'">
    <div class="stat-icon" style="background:rgba(var(--c-{{ $st[2] }}),0.12);color:var(--{{ $st[2] }})"><i class="{{ $st[1] }}"></i></div>
    <div>
      <div class="stat-value">{{ $st[3] }}</div>
      <div class="stat-label">{{ $st[0] }}</div>
    </div>
  </div>
  @endforeach
</div>

{{-- Table --}}
<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Lokasi Pekerjaan</th>
          <th>Ruang</th>
          <th>Jenis Pekerjaan</th>
          <th>Type</th>
          <th>Qty</th>
          <th>Satuan</th>
          <th>Teknisi</th>
          <th>Tgl Mulai</th>
          <th>Tgl Selesai</th>
          <th>Kerusakan</th>
          <th>Action</th>
          <th>Status</th>
          <th>Pergantian Perangkat</th>
          <th>Keterangan Tambahan</th>
          <th>HARGA BARANG</th>
          <th>TOTAL BIAYA</th>
          <th>PENGANTARAN PERANGKAT</th>
          <th class="sticky-col-head">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($data as $i=>$row)
        <tr>
          <td>{{ $data->firstItem()+$i }}</td>
          <td style="font-weight:600;white-space:nowrap">{{ $row->lokasi_pekerjaan }}</td>
          <td>{{ $row->ruang }}</td>
          <td><span class="badge badge-info">{{ $row->jenis_pekerjaan }}</span></td>
          <td>{{ $row->type }}</td>
          <td style="text-align:center;font-weight:700;color:var(--primary)">{{ $row->qty }}</td>
          <td>{{ $row->satuan }}</td>
          <td style="white-space:nowrap">
            @if(is_array($row->teknisi))
              @foreach($row->teknisi as $t)
                <span class="badge badge-outline" style="margin-bottom:2px">{{ $t }}</span>
              @endforeach
            @else
              {{ $row->teknisi }}
            @endif
          </td>
          <td style="white-space:nowrap">{{ $row->tgl_masuk?->format('d/m/Y') }}</td>
          <td style="white-space:nowrap">{{ $row->tgl_selesai?->format('d/m/Y') }}</td>
          <td style="min-width:150px;font-size:12px">{{ $row->kerusakan }}</td>
          <td style="min-width:200px;font-size:12px">{{ $row->action }}</td>
          <td>
            <span class="badge badge-{{ $row->status==='DONE'?'success':($row->status==='PROSES'?'warning':'danger') }}">
              {{ $row->status }}
            </span>
          </td>
          <td style="min-width:150px;font-size:12px">{{ $row->pergantian_perangkat }}</td>
          <td style="min-width:150px;font-size:12px">{{ $row->keterangan_tambahan }}</td>
          <td style="white-space:nowrap">Rp {{ number_format($row->harga, 0, ',', '.') }}</td>
          <td style="white-space:nowrap;font-weight:700">Rp {{ number_format($row->total_biaya, 0, ',', '.') }}</td>
          <td>{{ $row->pengantaran_perangkat }}</td>
          <td class="sticky-col">
            <div style="display:flex;gap:4px;justify-content:center">
              <button class="btn btn-sm" style="background:rgba(59,130,246,.1);color:var(--primary);border:none" onclick="viewSparepart({{ json_encode($row) }})" title="Detail"><i class="fas fa-info-circle"></i></button>
              <button class="btn btn-sm" style="background:rgba(245,158,11,.1);color:var(--warning);border:none" onclick="editSparepart({{ json_encode($row) }})" title="Edit"><i class="fas fa-edit"></i></button>
              <form method="POST" action="{{ route('engineering.sparepart.destroy',$row) }}" onsubmit="return confirm('Hapus data ini?')" style="margin:0">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm" style="background:rgba(239,68,68,.1);color:var(--danger);border:none" title="Hapus"><i class="fas fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="13" style="text-align:center;padding:32px;color:var(--text2)"><i class="fas fa-inbox" style="font-size:32px;display:block;margin-bottom:8px"></i>Belum ada data. Klik <strong>Tambah</strong> untuk menambahkan.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="margin-top:16px">{{ $data->links() }}</div>
</div>

{{-- Modal Tambah --}}
<div class="modal-overlay" id="addModal">
  <div class="modal" style="max-width:700px">
    <div class="modal-header">
      <span class="modal-title"><i class="fas fa-plus-circle" style="color:var(--primary)"></i> Tambah Sparepart</span>
      <button class="btn-icon" onclick="closeModal('addModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" action="{{ route('engineering.sparepart.store') }}">
      @csrf
      <div class="grid-2">
        <div class="form-group"><label>Lokasi Pekerjaan *</label><input type="text" name="lokasi_pekerjaan" required placeholder="Masukkan lokasi..."></div>
        <div class="form-group"><label>Ruang</label><input type="text" name="ruang" placeholder="Nama ruang..."></div>
      </div>
      <div class="grid-2">
        <div class="form-group"><label>Jenis Pekerjaan *</label>
          <select name="jenis_pekerjaan" required>
            @foreach(['PRINTER','KOMPUTER/LAPTOP','AC','CCTV','JARINGAN','GENSET','LISTRIK','LAINNYA'] as $j)
            <option>{{ $j }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group"><label>Type/Merk</label><input type="text" name="type" placeholder="Contoh: EPSON L3110..."></div>
      </div>
      <div class="grid-2">
        <div class="form-group"><label>Qty *</label><input type="number" name="qty" value="1" min="1" required></div>
        <div class="form-group"><label>Satuan</label>
          <select name="satuan">@foreach(['Unit','Set','Pcs','Meter','Liter'] as $s)<option>{{ $s }}</option>@endforeach</select>
        </div>
      </div>
      <div class="grid-2">
        <div class="form-group">
          <label>Teknisi (Pilih satu atau lebih)</label>
          <div class="multi-select-container">
            <div class="multi-select-display" onclick="toggleMultiSelect('addTeknisiList')">
              <span id="addTeknisiLabel">Pilih Teknisi...</span>
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="multi-select-list" id="addTeknisiList">
              @foreach(['MUHAMMAD AZLUL','MISDAN','L TAUFIQ WIJAYA','DIMAS FARID AWALUDIN','HENDRA HADI PRATAMA','ADITIA MARANDIKA RACHMAN','IWAN VANI','ANDRI PRATAMA','RADEN KUKUH RIDHO A'] as $t)
              <label class="multi-select-item">
                <input type="checkbox" name="teknisi[]" value="{{ $t }}" onchange="updateMultiSelectLabel('addTeknisiList', 'addTeknisiLabel')">
                <span>{{ $t }}</span>
              </label>
              @endforeach
            </div>
          </div>
        </div>
        <div class="form-group"><label>Status</label>
          <select name="status">@foreach(['PENDING','PROSES','DONE'] as $s)<option>{{ $s }}</option>@endforeach</select>
        </div>
      </div>
      <div class="grid-2">
        <div class="form-group"><label>Tgl Mulai</label><input type="date" name="tgl_masuk"></div>
        <div class="form-group"><label>Tgl Selesai</label><input type="date" name="tgl_selesai"></div>
      </div>
      <div class="grid-2">
        <div class="form-group"><label>Kerusakan</label><textarea name="kerusakan" rows="2" placeholder="Kerusakan..."></textarea></div>
        <div class="form-group"><label>Action (Work Done)</label><textarea name="action" rows="2" placeholder="Tindakan yang dilakukan..."></textarea></div>
      </div>
      <div class="grid-2">
        <div class="form-group"><label>Pergantian Perangkat</label><input type="text" name="pergantian_perangkat" placeholder="Perangkat yang diganti..."></div>
        <div class="form-group"><label>Keterangan Tambahan</label><input type="text" name="keterangan_tambahan" placeholder="Keterangan tambahan..."></div>
      </div>
      <div class="grid-2">
        <div class="form-group"><label>Harga Barang</label><input type="number" name="harga" placeholder="0"></div>
        <div class="form-group"><label>Pengantaran Perangkat</label><input type="text" name="pengantaran_perangkat" placeholder="Pengantaran..."></div>
      </div>
      <div class="form-group"><label>Catatan Lainnya</label><textarea name="keterangan" rows="1" placeholder="Catatan..."></textarea></div>
      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
        <button type="button" class="btn btn-outline" onclick="closeModal('addModal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit --}}
<div class="modal-overlay" id="editModal">
  <div class="modal" style="max-width:620px">
    <div class="modal-header">
      <span class="modal-title"><i class="fas fa-edit" style="color:var(--warning)"></i> Edit Sparepart</span>
      <button class="btn-icon" onclick="closeModal('editModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" id="editForm">
      @csrf @method('PUT')
      <div class="grid-2">
        <div class="form-group"><label>Lokasi Pekerjaan *</label><input type="text" name="lokasi_pekerjaan" id="e_lokasi" required></div>
        <div class="form-group"><label>Ruang</label><input type="text" name="ruang" id="e_ruang"></div>
      </div>
      <div class="grid-2">
        <div class="form-group"><label>Jenis Pekerjaan *</label>
          <select name="jenis_pekerjaan" id="e_jenis">
            @foreach(['PRINTER','KOMPUTER/LAPTOP','AC','CCTV','JARINGAN','GENSET','LISTRIK','LAINNYA'] as $j)
            <option>{{ $j }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group"><label>Type/Merk</label><input type="text" name="type" id="e_type"></div>
      </div>
      <div class="grid-2">
        <div class="form-group"><label>Qty *</label><input type="number" name="qty" id="e_qty" min="1" required></div>
        <div class="form-group"><label>Satuan</label>
          <select name="satuan" id="e_satuan">@foreach(['Unit','Set','Pcs','Meter','Liter'] as $s)<option>{{ $s }}</option>@endforeach</select>
        </div>
      </div>
      <div class="grid-2">
        <div class="form-group">
          <label>Teknisi</label>
          <div class="multi-select-container">
            <div class="multi-select-display" onclick="toggleMultiSelect('editTeknisiList')">
              <span id="editTeknisiLabel">Pilih Teknisi...</span>
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="multi-select-list" id="editTeknisiList">
              @foreach(['MUHAMMAD AZLUL','MISDAN','L TAUFIQ WIJAYA','DIMAS FARID AWALUDIN','HENDRA HADI PRATAMA','ADITIA MARANDIKA RACHMAN','IWAN VANI','ANDRI PRATAMA','RADEN KUKUH RIDHO A'] as $t)
              <label class="multi-select-item">
                <input type="checkbox" name="teknisi[]" value="{{ $t }}" class="edit-teknisi-check" onchange="updateMultiSelectLabel('editTeknisiList', 'editTeknisiLabel')">
                <span>{{ $t }}</span>
              </label>
              @endforeach
            </div>
          </div>
        </div>
        <div class="form-group"><label>Status</label>
          <select name="status" id="e_status">@foreach(['PENDING','PROSES','DONE'] as $s)<option>{{ $s }}</option>@endforeach</select>
        </div>
      </div>
      <div class="grid-2">
        <div class="form-group"><label>Tgl Mulai</label><input type="date" name="tgl_masuk" id="e_tgl_masuk"></div>
        <div class="form-group"><label>Tgl Selesai</label><input type="date" name="tgl_selesai" id="e_tgl_selesai"></div>
      </div>
      <div class="grid-2">
        <div class="form-group"><label>Kerusakan</label><textarea name="kerusakan" id="e_kerusakan" rows="2"></textarea></div>
        <div class="form-group"><label>Action (Work Done)</label><textarea name="action" id="e_action" rows="2"></textarea></div>
      </div>
      <div class="grid-2">
        <div class="form-group"><label>Pergantian Perangkat</label><input type="text" name="pergantian_perangkat" id="e_pergantian"></div>
        <div class="form-group"><label>Keterangan Tambahan</label><input type="text" name="keterangan_tambahan" id="e_keterangan_tambahan"></div>
      </div>
      <div class="grid-2">
        <div class="form-group"><label>Harga Barang</label><input type="number" name="harga" id="e_harga"></div>
        <div class="form-group"><label>Pengantaran Perangkat</label><input type="text" name="pengantaran_perangkat" id="e_pengantaran"></div>
      </div>
      <div class="form-group"><label>Catatan Lainnya</label><textarea name="keterangan" id="e_keterangan" rows="1"></textarea></div>
      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
        <button type="button" class="btn btn-outline" onclick="closeModal('editModal')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal View Detail --}}
<div class="modal-overlay" id="viewModal">
  <div class="modal" style="max-width:600px">
    <div class="modal-header">
      <span class="modal-title"><i class="fas fa-info-circle" style="color:var(--primary)"></i> Detail Sparepart</span>
      <button class="btn-icon" onclick="closeModal('viewModal')"><i class="fas fa-times"></i></button>
    </div>
    <div id="viewContent" style="font-size:14px">
      <!-- Content populated by JS -->
    </div>
    <div style="display:flex;justify-content:flex-end;margin-top:20px">
      <button type="button" class="btn btn-primary" onclick="closeModal('viewModal')">Tutup</button>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
function openModal(id){document.getElementById(id).classList.add('open');}
function closeModal(id){document.getElementById(id).classList.remove('open');}
document.querySelectorAll('.modal-overlay').forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.classList.remove('open');}));

function toggleMultiSelect(id) {
  const list = document.getElementById(id);
  const allLists = document.querySelectorAll('.multi-select-list');
  allLists.forEach(l => { if(l.id !== id) l.classList.remove('show'); });
  list.classList.toggle('show');
}

function updateMultiSelectLabel(listId, labelId) {
  const list = document.getElementById(listId);
  const label = document.getElementById(labelId);
  const checked = list.querySelectorAll('input[type="checkbox"]:checked');
  if (checked.length === 0) {
    label.innerText = 'Pilih Teknisi...';
    label.style.color = 'var(--text2)';
  } else {
    label.innerText = Array.from(checked).map(c => c.value).join(', ');
    label.style.color = 'var(--text)';
  }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
  if (!e.target.closest('.multi-select-container')) {
    document.querySelectorAll('.multi-select-list').forEach(l => l.classList.remove('show'));
  }
});

function editSparepart(row){
  const base='{{ url("engineering/sparepart") }}/';
  document.getElementById('editForm').action=base+row.id;
  document.getElementById('e_lokasi').value=row.lokasi_pekerjaan||'';
  document.getElementById('e_ruang').value=row.ruang||'';
  document.getElementById('e_jenis').value=row.jenis_pekerjaan||'';
  document.getElementById('e_type').value=row.type||'';
  document.getElementById('e_qty').value=row.qty||1;
  document.getElementById('e_satuan').value=row.satuan||'Unit';
  
  // Handle Multi-select for Edit
  const checks = document.querySelectorAll('.edit-teknisi-check');
  checks.forEach(c => c.checked = false);
  if (Array.isArray(row.teknisi)) {
    row.teknisi.forEach(t => {
      const c = Array.from(checks).find(i => i.value === t);
      if (c) c.checked = true;
    });
  } else if (row.teknisi) {
    const c = Array.from(checks).find(i => i.value === row.teknisi);
    if (c) c.checked = true;
  }
  updateMultiSelectLabel('editTeknisiList', 'editTeknisiLabel');

  document.getElementById('e_status').value=row.status||'PENDING';
  document.getElementById('e_tgl_masuk').value=row.tgl_masuk?row.tgl_masuk.substring(0,10):'';
  document.getElementById('e_tgl_selesai').value=row.tgl_selesai?row.tgl_selesai.substring(0,10):'';
  document.getElementById('e_kerusakan').value=row.kerusakan||'';
  document.getElementById('e_action').value=row.action||'';
  document.getElementById('e_pergantian').value=row.pergantian_perangkat||'';
  document.getElementById('e_keterangan_tambahan').value=row.keterangan_tambahan||'';
  document.getElementById('e_harga').value=row.harga||'';
  document.getElementById('e_pengantaran').value=row.pengantaran_perangkat||'';
  document.getElementById('e_keterangan').value=row.keterangan||'';
  openModal('editModal');
}

function viewSparepart(row) {
  const content = `
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;padding:10px;background:var(--surface2);border-radius:var(--radius-sm)">
      <div><strong>Lokasi:</strong><br>${row.lokasi_pekerjaan}</div>
      <div><strong>Ruang:</strong><br>${row.ruang||'-'}</div>
      <div><strong>Jenis:</strong><br>${row.jenis_pekerjaan}</div>
      <div><strong>Type:</strong><br>${row.type||'-'}</div>
      <div><strong>Qty:</strong><br>${row.qty} ${row.satuan}</div>
      <div><strong>Teknisi:</strong><br>${Array.isArray(row.teknisi) ? row.teknisi.join(', ') : (row.teknisi||'-')}</div>
      <div><strong>Status:</strong><br><span class="badge badge-${row.status==='DONE'?'success':(row.status==='PROSES'?'warning':'danger')}">${row.status}</span></div>
      <div><strong>Tgl Mulai:</strong><br>${row.tgl_masuk ? new Date(row.tgl_masuk).toLocaleDateString('id-ID') : '-'}</div>
      <div><strong>Tgl Selesai:</strong><br>${row.tgl_selesai ? new Date(row.tgl_selesai).toLocaleDateString('id-ID') : '-'}</div>
      <div><strong>Harga:</strong><br>Rp ${new Intl.NumberFormat('id-ID').format(row.harga||0)}</div>
      <div style="grid-column: span 2"><strong>Total Biaya:</strong><br>Rp ${new Intl.NumberFormat('id-ID').format(row.total_biaya||0)}</div>
    </div>
    <div style="margin-bottom:12px"><strong>Kerusakan:</strong><br>${row.kerusakan||'-'}</div>
    <div style="margin-bottom:12px"><strong>Action (Work Done):</strong><br>${row.action||'-'}</div>
    <div style="margin-bottom:12px"><strong>Pergantian Perangkat:</strong><br>${row.pergantian_perangkat||'-'}</div>
    <div style="margin-bottom:12px"><strong>Keterangan Tambahan:</strong><br>${row.keterangan_tambahan||'-'}</div>
    <div style="margin-bottom:12px"><strong>Pengantaran Perangkat:</strong><br>${row.pengantaran_perangkat||'-'}</div>
    <div><strong>Catatan:</strong><br>${row.keterangan||'-'}</div>
  `;
  document.getElementById('viewContent').innerHTML = content;
  openModal('viewModal');
}

setTimeout(()=>{const a=document.getElementById('alertBox');if(a)a.remove();},4000);
</script>
@endsection
@section('extra-styles')
<style>
.alert-success{background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.3);color:#10b981;padding:12px 16px;border-radius:var(--radius-sm);margin-bottom:16px;font-weight:600;font-size:14px;}
.badge-outline { background:transparent; border:1px solid var(--border); color:var(--text2); font-weight:500; }
.multi-select-container { position:relative; }
.multi-select-display { padding:9px 12px; border:1px solid var(--border); border-radius:var(--radius-sm); background:var(--surface); display:flex; justify-content:space-between; align-items:center; cursor:pointer; font-size:13px; min-height:38px; }
.multi-select-display span { white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:180px; color:var(--text2); }
.multi-select-list { position:absolute; top:105%; left:0; right:0; background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-sm); box-shadow:var(--shadow-lg); z-index:10; max-height:200px; overflow-y:auto; display:none; padding:4px; }
.multi-select-list.show { display:block; }
.multi-select-item { display:flex; align-items:center; gap:10px; padding:8px 10px; cursor:pointer; border-radius:4px; transition:var(--transition); font-size:13px; }
.multi-select-item:hover { background:var(--surface2); }
.multi-select-item input { width:auto; margin:0; }
.multi-select-item span { flex:1; }

/* Table Styling for Wide Data */
.table-wrap { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; border-radius: var(--radius-sm); }
table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 1500px; } /* Min-width adjusted for many columns */
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
  white-space: nowrap;
}
table td { padding: 12px 16px !important; border-bottom: 1px solid #f1f5f9; vertical-align: top; font-size: 12px; }
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

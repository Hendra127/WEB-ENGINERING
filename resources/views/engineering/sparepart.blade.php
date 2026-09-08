@extends('layouts.app')
@section('title', 'Log Book - Engineering')
@section('page-title', 'Log Book')
@section('content')

  {{-- Alert --}}
  @if(session('success'))
    <div class="alert-success" id="alertBox">
      <i class="fas fa-check-circle"></i> {{ session('success') }}
      <button onclick="document.getElementById('alertBox').remove()"
        style="float:right;background:none;border:none;cursor:pointer;color:inherit"><i class="fas fa-times"></i></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert-error" id="alertBoxErr">
      <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
      <button onclick="document.getElementById('alertBoxErr').remove()"
        style="float:right;background:none;border:none;cursor:pointer;color:inherit"><i class="fas fa-times"></i></button>
    </div>
  @endif

  {{-- Filter Bar --}}
  <div class="card" style="margin-bottom:20px">
    <form method="GET" action="{{ route('engineering.sparepart') }}"
      style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
      <div class="search-bar" style="flex:1;min-width:220px">
        <i class="fas fa-search"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kata kunci apapun...">
      </div>
      <select name="status"
        style="width:140px;height:38px;padding:0 12px;border-radius:var(--radius-sm);border:1px solid var(--border);background:var(--surface);color:var(--text);font-size:13px"
        onchange="this.form.submit()">
        <option value="">Semua Status</option>
        @foreach(['DONE', 'PROSES', 'PENDING'] as $s)
          <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
        @endforeach
      </select>
      <select name="lokasi"
        style="width:190px;height:38px;padding:0 12px;border-radius:var(--radius-sm);border:1px solid var(--border);background:var(--surface);color:var(--text);font-size:13px"
        onchange="this.form.submit()">
        <option value="">Semua Lokasi</option>
        @if(isset($lokasiList))
          @foreach($lokasiList as $lok)
            <option value="{{ $lok }}" {{ request('lokasi') == $lok ? 'selected' : '' }}>{{ $lok }}</option>
          @endforeach
        @endif
      </select>
      <div style="display:flex;align-items:center;gap:6px;font-size:13px">
        <input type="date" name="start_date" value="{{ request('start_date') }}" title="Tanggal Mulai"
          style="height:38px;padding:0 10px;border-radius:var(--radius-sm);border:1px solid var(--border);background:var(--surface);color:var(--text)">
        <span style="color:var(--text2)">s/d</span>
        <input type="date" name="end_date" value="{{ request('end_date') }}" title="Tanggal Selesai"
          style="height:38px;padding:0 10px;border-radius:var(--radius-sm);border:1px solid var(--border);background:var(--surface);color:var(--text)">
      </div>
      <button type="submit" class="btn btn-outline" style="height:38px"><i class="fas fa-filter"></i> Filter</button>
      <a href="{{ route('engineering.sparepart') }}" class="btn btn-outline"
        style="height:38px;display:inline-flex;align-items:center" title="Reset Filter"><i class="fas fa-redo"></i></a>
      <button type="button" class="btn btn-outline" style="height:38px" onclick="openModal('importModal')"><i
          class="fas fa-upload"></i> Import</button>
      <button type="button" class="btn btn-primary" style="height:38px" onclick="openAddModal()"><i
          class="fas fa-plus"></i> Tambah</button>
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
        style="cursor:pointer; transition: transform 0.2s; {{ (request('status') == $st[4] || (request('status') == '' && $st[4] == '')) ? 'border: 1.5px solid var(--' . $st[2] . '); box-shadow: var(--shadow-lg);' : '' }}"
        onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
        <div class="stat-icon" style="background:rgba(var(--c-{{ $st[2] }}),0.12);color:var(--{{ $st[2] }})"><i
            class="{{ $st[1] }}"></i></div>
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
            <th style="width:40px;text-align:center">No</th>
            <th>Lokasi Pekerjaan</th>
            <th>Ruang</th>
            <th>Jenis Pekerjaan</th>
            <th>Type / Merk</th>
            <th style="text-align:center">Qty</th>
            <th style="text-align:center">Satuan</th>
            <th>Teknisi</th>
            <th>Tgl Mulai</th>
            <th>Tgl Selesai</th>
            <th>Kerusakan</th>
            <th>Action</th>
            <th>Pergantian Perangkat</th>
            <th>Keterangan Tambahan</th>
            <th>Harga Barang</th>
            <th>Total Biaya</th>
            <th style="text-align:center">Status</th>
            <th class="sticky-col-head" style="text-align:center">Opsi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $i => $row)
            <tr>
              <td style="text-align:center;font-weight:600">{{ $data->firstItem() + $i }}</td>
              <td style="font-weight:600;white-space:nowrap">{{ $row->lokasi_pekerjaan }}</td>
              <td style="white-space:nowrap">{{ $row->ruang ?: '-' }}</td>
              <td style="white-space:nowrap"><span class="badge badge-info">{{ $row->jenis_pekerjaan }}</span></td>
              <td style="white-space:nowrap">{{ $row->type ?: '-' }}</td>
              <td style="text-align:center;font-weight:700;color:var(--primary)">{{ $row->qty }}</td>
              <td style="text-align:center;white-space:nowrap">{{ $row->satuan }}</td>
              <td style="white-space:nowrap">
                @if(is_array($row->teknisi))
                  @foreach($row->teknisi as $t)
                    <span class="badge badge-outline" style="margin-bottom:2px">{{ $t }}</span>
                  @endforeach
                @else
                  {{ $row->teknisi ?: '-' }}
                @endif
              </td>
              <td style="white-space:nowrap">{{ $row->tgl_masuk?->format('d/m/Y') ?: '-' }}</td>
              <td style="white-space:nowrap">{{ $row->tgl_selesai?->format('d/m/Y') ?: '-' }}</td>
              <td style="min-width:200px;font-size:12px">{{ $row->kerusakan ?: '-' }}</td>
              <td style="min-width:280px;font-size:12px">{{ $row->action ?: '-' }}</td>
              <td style="min-width:280px;font-size:12px">{{ $row->pergantian_perangkat ?: '-' }}</td>
              <td style="min-width:200px;font-size:12px">{{ $row->keterangan_tambahan ?: '-' }}</td>
              <td style="white-space:nowrap">{{ ($row->harga && $row->harga > 0) ? 'Rp ' . number_format($row->harga, 0, ',', '.') : '-' }}</td>
              <td style="white-space:nowrap;font-weight:700;color:var(--primary)">{{ ($row->total_biaya && $row->total_biaya > 0) ? 'Rp ' . number_format($row->total_biaya, 0, ',', '.') : '-' }}</td>
              <td style="white-space:nowrap;text-align:center">
                <span
                  class="badge badge-{{ $row->status === 'DONE' ? 'success' : ($row->status === 'PROSES' ? 'warning' : 'danger') }}">
                  {{ $row->status }}
                </span>
              </td>
              <td class="sticky-col" style="vertical-align: middle;">
                <div style="display:flex;gap:16px;justify-content:center;align-items:center;height:100%;">
                  <button style="background:none;border:none;color:var(--text);font-size:20px;cursor:pointer;padding:0;transition:transform 0.2s;" onclick="viewSparepart({{ json_encode($row) }})" title="Detail" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'"><i class="far fa-eye" style="-webkit-text-stroke: 0.5px var(--surface);"></i></button>
                  <button style="background:none;border:none;color:var(--text);font-size:20px;cursor:pointer;padding:0;transition:transform 0.2s;" onclick="editSparepart({{ json_encode($row) }})" title="Edit" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'"><i class="far fa-edit" style="-webkit-text-stroke: 0.5px var(--surface);"></i></button>
                  <form method="POST" action="{{ route('engineering.sparepart.destroy',$row) }}" onsubmit="return confirm('Hapus data ini?')" style="margin:0;display:flex;align-items:center;">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:none;border:none;color:var(--text);font-size:20px;cursor:pointer;padding:0;transition:transform 0.2s;" title="Hapus" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'"><i class="far fa-trash-alt" style="-webkit-text-stroke: 0.5px var(--surface);"></i></button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="18" style="text-align:center;padding:32px;color:var(--text2)"><i class="fas fa-inbox"
                  style="font-size:32px;display:block;margin-bottom:8px"></i>Belum ada data. Klik <strong>Tambah</strong>
                untuk menambahkan.</td>
            </tr>
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
        <span class="modal-title"><i class="fas fa-plus-circle" style="color:var(--primary)"></i> Tambah Log Book</span>
        <button class="btn-icon" onclick="closeModal('addModal')"><i class="fas fa-times"></i></button>
      </div>
      <form method="POST" action="{{ route('engineering.sparepart.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid-2">
          <div class="form-group"><label>Lokasi Pekerjaan *</label>
            <input type="text" name="lokasi_pekerjaan" list="lokasiSparepartList" required
              placeholder="Masukkan lokasi...">
            <datalist id="lokasiSparepartList">
              @if(isset($lokasiList))
                @foreach($lokasiList as $lok)
                  <option value="{{ $lok }}">
                @endforeach
              @endif
            </datalist>
          </div>
          <div class="form-group"><label>Ruang</label><input type="text" name="ruang" placeholder="Nama ruang..."></div>
        </div>
        <div class="grid-2">
          <div class="form-group"><label>Jenis Pekerjaan *</label>
            <select name="jenis_pekerjaan" required>
              @foreach(['PRINTER', 'KOMPUTER/LAPTOP', 'AC', 'CCTV', 'JARINGAN', 'GENSET', 'LISTRIK', 'LAINNYA'] as $j)
                <option>{{ $j }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group"><label>Type/Merk</label><input type="text" name="type"
              placeholder="Contoh: EPSON L3110..."></div>
        </div>
        <div class="grid-2">
          <div class="form-group"><label>Qty *</label><input type="number" name="qty" value="1" min="1" required></div>
          <div class="form-group"><label>Satuan</label>
            <select name="satuan">@foreach(['Unit', 'Set', 'Pcs', 'Meter', 'Liter'] as $s)
            <option>{{ $s }}</option>@endforeach
            </select>
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
                @foreach(['MUHAMMAD AZLUL', 'MISDAN', 'L TAUFIQ WIJAYA', 'DIMAS FARID AWALUDIN', 'HENDRA HADI PRATAMA', 'ADITIA MARANDIKA RACHMAN', 'IWAN VANI', 'ANDRI PRATAMA', 'RADEN KUKUH RIDHO A'] as $t)
                  <label class="multi-select-item">
                    <input type="checkbox" name="teknisi[]" value="{{ $t }}"
                      onchange="updateMultiSelectLabel('addTeknisiList', 'addTeknisiLabel')">
                    <span>{{ $t }}</span>
                  </label>
                @endforeach
              </div>
            </div>
          </div>
          <div class="form-group"><label>Status</label>
            <select name="status">@foreach(['PENDING', 'PROSES', 'DONE'] as $s)
            <option>{{ $s }}</option>@endforeach
            </select>
          </div>
        </div>
        <div class="grid-2">
          <div class="form-group"><label>Tgl Mulai</label><input type="date" name="tgl_masuk"></div>
          <div class="form-group"><label>Tgl Selesai</label><input type="date" name="tgl_selesai"></div>
        </div>
        <div class="grid-2">
          <div class="form-group"><label>Kerusakan</label><textarea name="kerusakan" rows="2"
              placeholder="Kerusakan..."></textarea></div>
          <div class="form-group"><label>Action (Work Done)</label><textarea name="action" rows="2"
              placeholder="Tindakan yang dilakukan..."></textarea></div>
        </div>
        <div class="grid-2">
          <div class="form-group"><label>Pergantian Perangkat</label><input type="text" name="pergantian_perangkat"
              placeholder="Pergantian perangkat..."></div>
          <div class="form-group"><label>Keterangan Tambahan</label><input type="text" name="keterangan_tambahan"
              placeholder="Keterangan tambahan..."></div>
        </div>
        <div class="grid-2">
          <div class="form-group"><label>Harga Barang</label><input type="number" name="harga" placeholder="-"></div>
          <div class="form-group"><label>Pengantaran Perangkat</label><input type="text" name="pengantaran_perangkat"
              placeholder="Pengantaran..."></div>
        </div>
        <div class="form-group"><label>Catatan Lainnya</label><textarea name="keterangan" rows="1"
            placeholder="Catatan..."></textarea></div>

        <div style="border-top: 1px solid var(--border); margin: 15px 0 10px; padding-top: 15px; font-weight: bold; font-size: 13px; color: var(--primary); display: flex; align-items: center; justify-content: space-between;">
          <span><i class="fas fa-camera"></i> DOKUMENTASI & BERITA ACARA</span>
          <span style="font-size: 11px; font-weight: normal; color: var(--text2);"><i class="fab fa-whatsapp" style="color: #25D366; font-size: 13px;"></i> Drag/Drop & Paste dari WA</span>
        </div>
        <div class="grid-2">
          <div class="form-group">
            <label>Foto Masuk (Check-in)</label>
            <div class="upload-dropzone" id="add_dz_masuk" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 125px; padding: 16px 12px; border: 2px dashed var(--border); border-radius: var(--radius-sm); background: var(--surface2); cursor: pointer; text-align: center; position: relative; width: 100%; box-sizing: border-box;">
              <input type="file" name="foto_masuk[]" multiple accept="image/*" style="display:none">
              <div class="upload-placeholder" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 5px; width: 100%; pointer-events: none;">
                <div class="upload-icon-circle"><i class="fas fa-sign-in-alt"></i></div>
                <span class="upload-title">Foto Masuk (Check-in)</span>
                <span class="upload-sub">Drag & Drop foto di sini, atau Klik</span>
                <span class="upload-badge"><i class="fab fa-whatsapp"></i> Paste (Ctrl+V) dari WA</span>
              </div>
              <div class="upload-preview" style="display:none; width: 100%;"></div>
            </div>
          </div>
          <div class="form-group">
            <label>Foto Proses</label>
            <div class="upload-dropzone" id="add_dz_proses" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 125px; padding: 16px 12px; border: 2px dashed var(--border); border-radius: var(--radius-sm); background: var(--surface2); cursor: pointer; text-align: center; position: relative; width: 100%; box-sizing: border-box;">
              <input type="file" name="foto_proses[]" multiple accept="image/*" style="display:none">
              <div class="upload-placeholder" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 5px; width: 100%; pointer-events: none;">
                <div class="upload-icon-circle"><i class="fas fa-tools"></i></div>
                <span class="upload-title">Foto Proses Perbaikan</span>
                <span class="upload-sub">Drag & Drop foto di sini, atau Klik</span>
                <span class="upload-badge"><i class="fab fa-whatsapp"></i> Paste (Ctrl+V) dari WA</span>
              </div>
              <div class="upload-preview" style="display:none; width: 100%;"></div>
            </div>
          </div>
        </div>
        <div class="grid-2">
          <div class="form-group">
            <label>Foto Keluar (Check-out)</label>
            <div class="upload-dropzone" id="add_dz_keluar" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 125px; padding: 16px 12px; border: 2px dashed var(--border); border-radius: var(--radius-sm); background: var(--surface2); cursor: pointer; text-align: center; position: relative; width: 100%; box-sizing: border-box;">
              <input type="file" name="foto_keluar[]" multiple accept="image/*" style="display:none">
              <div class="upload-placeholder" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 5px; width: 100%; pointer-events: none;">
                <div class="upload-icon-circle"><i class="fas fa-sign-out-alt"></i></div>
                <span class="upload-title">Foto Keluar (Check-out)</span>
                <span class="upload-sub">Drag & Drop foto di sini, atau Klik</span>
                <span class="upload-badge"><i class="fab fa-whatsapp"></i> Paste (Ctrl+V) dari WA</span>
              </div>
              <div class="upload-preview" style="display:none; width: 100%;"></div>
            </div>
          </div>
          <div class="form-group">
            <label>File BA (Sudah TTD)</label>
            <div class="upload-dropzone" id="add_dz_ba" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 125px; padding: 16px 12px; border: 2px dashed var(--border); border-radius: var(--radius-sm); background: var(--surface2); cursor: pointer; text-align: center; position: relative; width: 100%; box-sizing: border-box;">
              <input type="file" name="file_ba[]" multiple accept="image/*,application/pdf,.doc,.docx" style="display:none">
              <div class="upload-placeholder" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 5px; width: 100%; pointer-events: none;">
                <div class="upload-icon-circle"><i class="fas fa-file-signature"></i></div>
                <span class="upload-title">File / Foto BA Signed</span>
                <span class="upload-sub">Drag & Drop file di sini, atau Klik</span>
                <span class="upload-badge"><i class="fab fa-whatsapp"></i> Paste (Ctrl+V) dari WA</span>
              </div>
              <div class="upload-preview" style="display:none; width: 100%;"></div>
            </div>
          </div>
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
          <button type="button" class="btn btn-outline" onclick="closeModal('addModal')">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>

  {{-- Modal Edit --}}
  <div class="modal-overlay" id="editModal">
    <div class="modal" style="max-width:700px">
      <div class="modal-header">
        <span class="modal-title"><i class="fas fa-edit" style="color:var(--warning)"></i> Edit Log Book</span>
        <button class="btn-icon" onclick="closeModal('editModal')"><i class="fas fa-times"></i></button>
      </div>
      <form method="POST" id="editForm" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="grid-2">
          <div class="form-group"><label>Lokasi Pekerjaan *</label><input type="text" name="lokasi_pekerjaan"
              id="e_lokasi" required></div>
          <div class="form-group"><label>Ruang</label><input type="text" name="ruang" id="e_ruang"></div>
        </div>
        <div class="grid-2">
          <div class="form-group"><label>Jenis Pekerjaan *</label>
            <select name="jenis_pekerjaan" id="e_jenis">
              @foreach(['PRINTER', 'KOMPUTER/LAPTOP', 'AC', 'CCTV', 'JARINGAN', 'GENSET', 'LISTRIK', 'LAINNYA'] as $j)
                <option>{{ $j }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group"><label>Type/Merk</label><input type="text" name="type" id="e_type"></div>
        </div>
        <div class="grid-2">
          <div class="form-group"><label>Qty *</label><input type="number" name="qty" id="e_qty" min="1" required></div>
          <div class="form-group"><label>Satuan</label>
            <select name="satuan" id="e_satuan">@foreach(['Unit', 'Set', 'Pcs', 'Meter', 'Liter'] as $s)
            <option>{{ $s }}</option>@endforeach
            </select>
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
                @foreach(['MUHAMMAD AZLUL', 'MISDAN', 'L TAUFIQ WIJAYA', 'DIMAS FARID AWALUDIN', 'HENDRA HADI PRATAMA', 'ADITIA MARANDIKA RACHMAN', 'IWAN VANI', 'ANDRI PRATAMA', 'RADEN KUKUH RIDHO A'] as $t)
                  <label class="multi-select-item">
                    <input type="checkbox" name="teknisi[]" value="{{ $t }}" class="edit-teknisi-check"
                      onchange="updateMultiSelectLabel('editTeknisiList', 'editTeknisiLabel')">
                    <span>{{ $t }}</span>
                  </label>
                @endforeach
              </div>
            </div>
          </div>
          <div class="form-group"><label>Status</label>
            <select name="status" id="e_status">@foreach(['PENDING', 'PROSES', 'DONE'] as $s)
            <option>{{ $s }}</option>@endforeach
            </select>
          </div>
        </div>
        <div class="grid-2">
          <div class="form-group"><label>Tgl Mulai</label><input type="date" name="tgl_masuk" id="e_tgl_masuk"></div>
          <div class="form-group"><label>Tgl Selesai</label><input type="date" name="tgl_selesai" id="e_tgl_selesai">
          </div>
        </div>
        <div class="grid-2">
          <div class="form-group"><label>Kerusakan</label><textarea name="kerusakan" id="e_kerusakan" rows="2"></textarea>
          </div>
          <div class="form-group"><label>Action (Work Done)</label><textarea name="action" id="e_action"
              rows="2"></textarea></div>
        </div>
        <div class="grid-2">
          <div class="form-group"><label>Pergantian Perangkat</label><input type="text" name="pergantian_perangkat"
              id="e_pergantian"></div>
          <div class="form-group"><label>Keterangan Tambahan</label><input type="text" name="keterangan_tambahan"
              id="e_keterangan_tambahan"></div>
        </div>
        <div class="grid-2">
          <div class="form-group"><label>Harga Barang</label><input type="number" name="harga" id="e_harga" placeholder="-"></div>
          <div class="form-group"><label>Pengantaran Perangkat</label><input type="text" name="pengantaran_perangkat"
              id="e_pengantaran"></div>
        </div>
        <div class="form-group"><label>Catatan Lainnya</label><textarea name="keterangan" id="e_keterangan"
            rows="1"></textarea></div>

        <div style="border-top: 1px solid var(--border); margin: 15px 0 10px; padding-top: 15px; font-weight: bold; font-size: 13px; color: var(--primary); display: flex; align-items: center; justify-content: space-between;">
          <span><i class="fas fa-camera"></i> DOKUMENTASI & BERITA ACARA</span>
          <span style="font-size: 11px; font-weight: normal; color: var(--text2);"><i class="fab fa-whatsapp" style="color: #25D366; font-size: 13px;"></i> Drag/Drop & Paste dari WA</span>
        </div>
        <div class="grid-2">
          <div class="form-group">
            <label>Foto Masuk (Check-in)</label>
            <div class="upload-dropzone" id="edit_dz_masuk" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 125px; padding: 16px 12px; border: 2px dashed var(--border); border-radius: var(--radius-sm); background: var(--surface2); cursor: pointer; text-align: center; position: relative; width: 100%; box-sizing: border-box;">
              <input type="file" name="foto_masuk[]" multiple accept="image/*" style="display:none">
              <div class="upload-placeholder" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 5px; width: 100%; pointer-events: none;">
                <div class="upload-icon-circle"><i class="fas fa-sign-in-alt"></i></div>
                <span class="upload-title">Upload Foto Masuk Baru</span>
                <span class="upload-sub">Drag & Drop foto di sini, atau Klik</span>
                <span class="upload-badge"><i class="fab fa-whatsapp"></i> Paste (Ctrl+V) dari WA</span>
              </div>
              <div class="upload-preview" style="display:none; width: 100%;"></div>
            </div>
            <div id="e_preview_masuk"></div>
          </div>
          <div class="form-group">
            <label>Foto Proses</label>
            <div class="upload-dropzone" id="edit_dz_proses" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 125px; padding: 16px 12px; border: 2px dashed var(--border); border-radius: var(--radius-sm); background: var(--surface2); cursor: pointer; text-align: center; position: relative; width: 100%; box-sizing: border-box;">
              <input type="file" name="foto_proses[]" multiple accept="image/*" style="display:none">
              <div class="upload-placeholder" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 5px; width: 100%; pointer-events: none;">
                <div class="upload-icon-circle"><i class="fas fa-tools"></i></div>
                <span class="upload-title">Upload Foto Proses Baru</span>
                <span class="upload-sub">Drag & Drop foto di sini, atau Klik</span>
                <span class="upload-badge"><i class="fab fa-whatsapp"></i> Paste (Ctrl+V) dari WA</span>
              </div>
              <div class="upload-preview" style="display:none; width: 100%;"></div>
            </div>
            <div id="e_preview_proses"></div>
          </div>
        </div>
        <div class="grid-2">
          <div class="form-group">
            <label>Foto Keluar (Check-out)</label>
            <div class="upload-dropzone" id="edit_dz_keluar" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 125px; padding: 16px 12px; border: 2px dashed var(--border); border-radius: var(--radius-sm); background: var(--surface2); cursor: pointer; text-align: center; position: relative; width: 100%; box-sizing: border-box;">
              <input type="file" name="foto_keluar[]" multiple accept="image/*" style="display:none">
              <div class="upload-placeholder" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 5px; width: 100%; pointer-events: none;">
                <div class="upload-icon-circle"><i class="fas fa-sign-out-alt"></i></div>
                <span class="upload-title">Upload Foto Keluar Baru</span>
                <span class="upload-sub">Drag & Drop foto di sini, atau Klik</span>
                <span class="upload-badge"><i class="fab fa-whatsapp"></i> Paste (Ctrl+V) dari WA</span>
              </div>
              <div class="upload-preview" style="display:none; width: 100%;"></div>
            </div>
            <div id="e_preview_keluar"></div>
          </div>
          <div class="form-group" id="ba_upload_container">
            <label>File BA (Sudah TTD / Overwrite)</label>
            <div class="upload-dropzone" id="edit_dz_ba" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 125px; padding: 16px 12px; border: 2px dashed var(--border); border-radius: var(--radius-sm); background: var(--surface2); cursor: pointer; text-align: center; position: relative; width: 100%; box-sizing: border-box;">
              <input type="file" name="file_ba[]" multiple accept="image/*,application/pdf,.doc,.docx" style="display:none">
              <div class="upload-placeholder" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 5px; width: 100%; pointer-events: none;">
                <div class="upload-icon-circle"><i class="fas fa-file-signature"></i></div>
                <span class="upload-title">Upload File BA Signed Baru</span>
                <span class="upload-sub">Drag & Drop file di sini, atau Klik</span>
                <span class="upload-badge"><i class="fab fa-whatsapp"></i> Paste (Ctrl+V) dari WA</span>
              </div>
              <div class="upload-preview" style="display:none; width: 100%;"></div>
            </div>
            <div id="e_preview_ba"></div>
          </div>
        </div>

        <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:16px">
          <button type="button" class="btn btn-outline" onclick="closeModal('editModal')">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
        </div>
      </form>
    </div>
  </div>

  {{-- Modal Import --}}
  <div class="modal-overlay" id="importModal">
    <div class="modal" style="max-width:500px">
      <div class="modal-header">
        <span class="modal-title"><i class="fas fa-file-import" style="color:var(--primary)"></i> Import Data</span>
        <button class="btn-icon" onclick="closeModal('importModal')"><i class="fas fa-times"></i></button>
      </div>
      <form method="POST" action="{{ route('engineering.sparepart.import') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group" style="margin-bottom: 20px">
          <label>Pilih File Excel/CSV</label>
          <div
            style="border: 2px dashed var(--border); padding: 30px; text-align: center; border-radius: var(--radius-sm); background: var(--surface2);">
            <i class="fas fa-cloud-upload-alt" style="font-size: 32px; color: var(--primary); margin-bottom: 10px;"></i>
            <div style="margin-bottom: 10px; font-weight: 500;">Pilih file excel/csv untuk diimport</div>
            <input type="file" name="file"
              accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
              required style="max-width: 100%;">
            <div style="margin-top: 10px; font-size: 11px; color: var(--text2);">Format yang didukung: .xlsx, .xls, .csv
            </div>
          </div>
        </div>
        <div style="display:flex; justify-content: space-between; align-items: center;">
          <a href="{{ route('engineering.sparepart.template') }}" class="btn btn-sm btn-outline"><i
              class="fas fa-download"></i> Download Template</a>
          <div style="display:flex;gap:10px;">
            <button type="button" class="btn btn-outline" onclick="closeModal('importModal')">Batal</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Import</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Modal View Detail --}}
  <div class="modal-overlay" id="viewModal">
    <div class="modal" style="max-width:600px">
      <div class="modal-header">
        <span class="modal-title"><i class="fas fa-info-circle" style="color:var(--primary)"></i> Detail Log Book</span>
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

  <!-- Modal Review / Lightbox Foto -->
  <div class="modal-overlay" id="imageReviewModal" style="z-index: 9999;">
    <div class="modal" style="max-width: 900px; width: 95%; background: rgba(15, 23, 42, 0.98); border: 1px solid #334155; padding: 16px; border-radius: 12px; color: #fff;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid #334155; padding-bottom: 8px;">
        <span id="reviewModalTitle" style="font-weight: 700; font-size: 14px; color: #38bdf8; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 80%;"><i class="fas fa-search-plus"></i> Review Foto</span>
        <button type="button" onclick="closeModal('imageReviewModal')" style="background: none; border: none; color: #94a3b8; font-size: 20px; cursor: pointer;"><i class="fas fa-times"></i></button>
      </div>
      <div style="display: flex; justify-content: center; align-items: center; min-height: 350px; max-height: 72vh; overflow: auto; background: #020617; border-radius: 8px; padding: 10px;">
        <img id="reviewModalImage" src="" alt="Review Foto" style="max-width: 100%; max-height: 68vh; object-fit: contain; border-radius: 6px; box-shadow: 0 10px 25px rgba(0,0,0,0.5);">
      </div>
      <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 12px; font-size: 12px;">
        <a id="reviewModalDownload" href="#" target="_blank" class="btn btn-sm btn-outline" style="color: #38bdf8; border-color: #0284c7; text-decoration: none;"><i class="fas fa-external-link-alt"></i> Buka Ukuran Penuh</a>
        <button type="button" class="btn btn-primary" onclick="closeModal('imageReviewModal')">Tutup Review</button>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    function openModal(id) { document.getElementById(id).classList.add('open'); }
    function closeModal(id) { document.getElementById(id).classList.remove('open'); }
    document.querySelectorAll('.modal-overlay').forEach(m => m.addEventListener('click', e => { if (e.target === m) m.classList.remove('open'); }));

    function toggleMultiSelect(id) {
      const list = document.getElementById(id);
      const allLists = document.querySelectorAll('.multi-select-list');
      allLists.forEach(l => { if (l.id !== id) l.classList.remove('show'); });
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
    document.addEventListener('click', function (e) {
      if (!e.target.closest('.multi-select-container')) {
        document.querySelectorAll('.multi-select-list').forEach(l => l.classList.remove('show'));
      }
    });

    function toggleBAUpload() {
      const container = document.getElementById('ba_upload_container');
      if (container) {
        container.style.display = 'block';
      }
    }

    const dropzoneIds = [
      'add_dz_masuk', 'add_dz_proses', 'add_dz_keluar', 'add_dz_ba',
      'edit_dz_masuk', 'edit_dz_proses', 'edit_dz_keluar', 'edit_dz_ba'
    ];

    function escapeHtml(str) {
      if (!str) return '';
      return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function formatBytes(bytes, decimals = 1) {
      if (!bytes || bytes === 0) return '0 B';
      const k = 1024;
      const dm = decimals < 0 ? 0 : decimals;
      const sizes = ['B', 'KB', 'MB', 'GB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    function openReviewModal(src, title) {
      document.getElementById('reviewModalImage').src = src;
      document.getElementById('reviewModalTitle').innerHTML = '<i class="fas fa-search-plus"></i> ' + (title || 'Review Foto');
      document.getElementById('reviewModalDownload').href = src;
      openModal('imageReviewModal');
    }

    function parsePhotos(data) {
      if (!data) return [];
      if (Array.isArray(data)) return data;
      try {
        const parsed = JSON.parse(data);
        if (Array.isArray(parsed)) return parsed;
      } catch(e) {}
      return [data];
    }

    function setupDropzone(containerId) {
      const container = document.getElementById(containerId);
      if (!container) return;

      const fileInput = container.querySelector('input[type="file"]');
      const placeholder = container.querySelector('.upload-placeholder');
      const preview = container.querySelector('.upload-preview');

      if (!fileInput) return;

      let accumulatedFiles = [];

      container.setAttribute('tabindex', '0');

      container.addEventListener('click', (e) => {
        if (e.target.closest('.remove-file-btn') || e.target.closest('.review-file-btn') || e.target.closest('a') || e.target.closest('button')) return;
        fileInput.click();
      });

      container.addEventListener('focus', () => {
        document.querySelectorAll('.upload-dropzone').forEach(el => el.classList.remove('dz-focused'));
        container.classList.add('dz-focused');
      });

      ['dragenter', 'dragover'].forEach(eventName => {
        container.addEventListener(eventName, (e) => {
          e.preventDefault();
          e.stopPropagation();
          container.classList.add('drag-active');
        }, false);
      });

      ['dragleave', 'drop'].forEach(eventName => {
        container.addEventListener(eventName, (e) => {
          e.preventDefault();
          e.stopPropagation();
          container.classList.remove('drag-active');
        }, false);
      });

      container.addEventListener('drop', async (e) => {
        const dt = e.dataTransfer;
        if (!dt) return;

        if (dt.files && dt.files.length > 0) {
          addFiles(Array.from(dt.files));
        } else {
          const html = dt.getData('text/html');
          const match = html && html.match(/src=["'](.*?)["']/);
          const url = match ? match[1] : dt.getData('text/uri-list');

          if (url) {
            try {
              const res = await fetch(url);
              const blob = await res.blob();
              const ext = (blob.type && blob.type.split('/')[1]) || 'png';
              const file = new File([blob], `wa_dropped_${Date.now()}.${ext}`, { type: blob.type || 'image/png' });
              addFiles([file]);
            } catch (err) {
              console.error('Gagal mengambil gambar dari URL drag:', err);
            }
          }
        }
      });

      container.addEventListener('paste', (e) => {
        processPaste(e);
      });

      fileInput.addEventListener('change', () => {
        if (fileInput.files && fileInput.files.length > 0) {
          addFiles(Array.from(fileInput.files));
        }
      });

      function processPaste(e) {
        const cData = e.clipboardData || window.clipboardData;
        if (!cData || !cData.items) return;

        const pastedFiles = [];
        for (let i = 0; i < cData.items.length; i++) {
          const item = cData.items[i];
          if (item.type.indexOf('image') !== -1 || item.kind === 'file') {
            const file = item.getAsFile();
            if (file) {
              e.preventDefault();
              e.stopPropagation();
              const ext = (file.type && file.type.split('/')[1]) || 'png';
              const namedFile = new File([file], `wa_paste_${Date.now()}_${i}.${ext}`, { type: file.type || 'image/png' });
              pastedFiles.push(namedFile);
            }
          }
        }
        if (pastedFiles.length > 0) {
          addFiles(pastedFiles);
        }
      }

      function addFiles(newFiles) {
        accumulatedFiles = accumulatedFiles.concat(newFiles);
        syncFileInput();
        renderPreviews();
      }

      function removeFileByIndex(idx) {
        accumulatedFiles.splice(idx, 1);
        syncFileInput();
        renderPreviews();
      }

      function syncFileInput() {
        const dt = new DataTransfer();
        accumulatedFiles.forEach(f => dt.items.add(f));
        fileInput.files = dt.files;
      }

      function renderPreviews() {
        if (accumulatedFiles.length === 0) {
          if (placeholder) placeholder.style.display = 'flex';
          if (preview) {
            preview.innerHTML = '';
            preview.style.display = 'none';
          }
          return;
        }

        if (placeholder) placeholder.style.display = 'none';
        if (!preview) return;

        preview.style.display = 'block';
        preview.innerHTML = '';

        const grid = document.createElement('div');
        grid.style.cssText = 'display: grid; grid-template-columns: repeat(auto-fill, minmax(95px, 1fr)); gap: 8px; width: 100%; margin-top: 6px;';

        accumulatedFiles.forEach((file, idx) => {
          const card = document.createElement('div');
          card.style.cssText = 'border: 1px solid var(--border); border-radius: 6px; padding: 4px; background: var(--surface); position: relative; display: flex; flex-direction: column; align-items: center; text-align: center; overflow: hidden;';

          const isImg = file.type.startsWith('image/');
          if (isImg) {
            const reader = new FileReader();
            reader.onload = (e) => {
              card.innerHTML = `
                <div style="width: 100%; height: 75px; border-radius: 4px; overflow: hidden; position: relative; background: #0f172a;">
                  <img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" title="Klik untuk Review" onclick="event.stopPropagation(); openReviewModal('${e.target.result}', '${escapeHtml(file.name)}')">
                  <button type="button" class="review-file-btn" onclick="event.stopPropagation(); openReviewModal('${e.target.result}', '${escapeHtml(file.name)}')" title="Review Foto" style="position: absolute; bottom: 4px; left: 4px; background: rgba(0,0,0,0.65); color: #fff; border: none; border-radius: 4px; width: 22px; height: 22px; font-size: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-eye"></i></button>
                  <button type="button" class="remove-file-btn" onclick="event.stopPropagation(); window.removeDzFile('${containerId}', ${idx})" title="Hapus foto" style="position: absolute; top: 4px; right: 4px; background: rgba(239,68,68,0.9); color: #fff; border: none; border-radius: 4px; width: 22px; height: 22px; font-size: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-trash-alt"></i></button>
                </div>
                <span style="font-size: 9.5px; margin-top: 4px; width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--text);" title="${escapeHtml(file.name)}">${escapeHtml(file.name)}</span>
              `;
            };
            reader.readAsDataURL(file);
          } else {
            card.innerHTML = `
              <div style="width: 100%; height: 75px; border-radius: 4px; background: var(--surface2); display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative;">
                <i class="fas fa-file-pdf" style="font-size: 24px; color: var(--primary);"></i>
                <button type="button" class="remove-file-btn" onclick="event.stopPropagation(); window.removeDzFile('${containerId}', ${idx})" title="Hapus file" style="position: absolute; top: 4px; right: 4px; background: rgba(239,68,68,0.9); color: #fff; border: none; border-radius: 4px; width: 22px; height: 22px; font-size: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-trash-alt"></i></button>
              </div>
              <span style="font-size: 9.5px; margin-top: 4px; width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--text);" title="${escapeHtml(file.name)}">${escapeHtml(file.name)}</span>
            `;
          }
          grid.appendChild(card);
        });

        const addMoreBtn = document.createElement('div');
        addMoreBtn.style.cssText = 'margin-top: 8px; width: 100%; text-align: center;';
        addMoreBtn.innerHTML = `<button type="button" class="btn btn-sm btn-outline" style="font-size: 11px; padding: 3px 10px;" onclick="event.stopPropagation(); document.querySelector('#${containerId} input[type=file]').click();"><i class="fas fa-plus"></i> Tambah Foto Lagi</button>`;

        preview.appendChild(grid);
        preview.appendChild(addMoreBtn);
      }

      function resetDropzone() {
        accumulatedFiles = [];
        syncFileInput();
        if (preview) {
          preview.innerHTML = '';
          preview.style.display = 'none';
        }
        if (placeholder) {
          placeholder.style.display = 'flex';
        }
      }

      container.resetDropzone = resetDropzone;
      container.removeFileByIndex = removeFileByIndex;
    }

    window.removeDzFile = function(containerId, idx) {
      const container = document.getElementById(containerId);
      if (container && container.removeFileByIndex) {
        container.removeFileByIndex(idx);
      }
    };

    function openAddModal() {
      ['add_dz_masuk', 'add_dz_proses', 'add_dz_keluar', 'add_dz_ba'].forEach(id => {
        const el = document.getElementById(id);
        if (el && el.resetDropzone) el.resetDropzone();
      });
      openModal('addModal');
    }

    document.addEventListener('DOMContentLoaded', () => {
      const statusSelect = document.getElementById('e_status');
      if (statusSelect) {
        statusSelect.addEventListener('change', toggleBAUpload);
      }
      dropzoneIds.forEach(id => setupDropzone(id));
    });

    function renderSavedGallery(containerId, photos, fieldTitle, fieldKey) {
      const target = document.getElementById(containerId);
      if (!target) return;
      const list = parsePhotos(photos);
      if (list.length > 0) {
        let html = `<div style="margin-top: 8px; font-size: 11px; font-weight: 600; color: var(--primary);"><i class="fas fa-images"></i> ${fieldTitle} Tersimpan (${list.length}):</div>`;
        html += `<div class="saved-gallery-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(75px, 1fr)); gap: 6px; margin-top: 4px;">`;
        list.forEach(p => {
          const url = '{{ asset("storage") }}/' + p;
          const isPdf = p.toLowerCase().endsWith('.pdf') || p.toLowerCase().endsWith('.doc') || p.toLowerCase().endsWith('.docx');
          if (isPdf) {
            html += `
              <div class="saved-photo-card" style="height: 65px; border: 1px solid var(--border); border-radius: 4px; background: var(--surface2); display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative;">
                <input type="hidden" name="existing_${fieldKey}[]" value="${escapeHtml(p)}">
                <i class="fas fa-file-pdf" style="font-size: 22px; color: var(--primary);"></i>
                <a href="${url}" target="_blank" style="font-size: 9px; margin-top: 2px; color: var(--primary); font-weight: 600; text-decoration: none;">Buka File</a>
                <button type="button" onclick="removeSavedPhotoCard(this)" title="Hapus file tersimpan" style="position: absolute; top: 3px; right: 3px; background: rgba(239,68,68,0.9); color: #fff; border: none; border-radius: 3px; width: 20px; height: 20px; font-size: 9px; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-trash-alt"></i></button>
              </div>`;
          } else {
            html += `
              <div class="saved-photo-card" style="height: 65px; border: 1px solid var(--border); border-radius: 4px; overflow: hidden; position: relative; background: #0f172a;">
                <input type="hidden" name="existing_${fieldKey}[]" value="${escapeHtml(p)}">
                <img src="${url}" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" title="Review Foto" onclick="openReviewModal('${url}', '${fieldTitle}')">
                <button type="button" onclick="openReviewModal('${url}', '${fieldTitle}')" title="Review Foto" style="position: absolute; bottom: 3px; left: 3px; background: rgba(0,0,0,0.65); color: #fff; border: none; border-radius: 3px; width: 20px; height: 20px; font-size: 9px; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-eye"></i></button>
                <button type="button" onclick="removeSavedPhotoCard(this)" title="Hapus foto tersimpan" style="position: absolute; top: 3px; right: 3px; background: rgba(239,68,68,0.9); color: #fff; border: none; border-radius: 3px; width: 20px; height: 20px; font-size: 9px; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-trash-alt"></i></button>
              </div>`;
          }
        });
        html += `</div>`;
        target.innerHTML = html;
      } else {
        target.innerHTML = `<div style="margin-top: 4px; font-size: 11px; color: var(--text2);"><i class="fas fa-info-circle"></i> Belum ada file tersimpan</div>`;
      }
    }

    function removeSavedPhotoCard(btn) {
      const card = btn.closest('.saved-photo-card');
      const grid = card ? card.parentElement : null;
      const target = grid ? grid.parentElement : null;
      if (card) card.remove();
      if (grid && grid.querySelectorAll('.saved-photo-card').length === 0 && target) {
        target.innerHTML = '<div style="margin-top: 4px; font-size: 11px; color: var(--text2);"><i class="fas fa-info-circle"></i> Belum ada file tersimpan</div>';
      }
    }

    function editSparepart(row) {
      ['edit_dz_masuk', 'edit_dz_proses', 'edit_dz_keluar', 'edit_dz_ba'].forEach(id => {
        const el = document.getElementById(id);
        if (el && el.resetDropzone) el.resetDropzone();
      });
      const base = '{{ url("engineering/sparepart") }}/';
      document.getElementById('editForm').action = base + row.id;
      document.getElementById('e_lokasi').value = row.lokasi_pekerjaan || '';
      document.getElementById('e_ruang').value = row.ruang || '';
      document.getElementById('e_jenis').value = row.jenis_pekerjaan || '';
      document.getElementById('e_type').value = row.type || '';
      document.getElementById('e_qty').value = row.qty || 1;
      document.getElementById('e_satuan').value = row.satuan || 'Unit';

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

      document.getElementById('e_status').value = row.status || 'PENDING';
      document.getElementById('e_tgl_masuk').value = row.tgl_masuk ? row.tgl_masuk.substring(0, 10) : '';
      document.getElementById('e_tgl_selesai').value = row.tgl_selesai ? row.tgl_selesai.substring(0, 10) : '';
      document.getElementById('e_kerusakan').value = row.kerusakan || '';
      document.getElementById('e_action').value = row.action || '';
      document.getElementById('e_pergantian').value = row.pergantian_perangkat || '';
      document.getElementById('e_harga').value = (row.harga && parseFloat(row.harga) > 0) ? row.harga : '';
      document.getElementById('e_keterangan').value = row.keterangan || '';
      document.getElementById('e_keterangan_tambahan').value = row.keterangan_tambahan || '';

      renderSavedGallery('e_preview_masuk', row.foto_masuk, 'Foto Masuk', 'foto_masuk');
      renderSavedGallery('e_preview_proses', row.foto_proses, 'Foto Proses', 'foto_proses');
      renderSavedGallery('e_preview_keluar', row.foto_keluar, 'Foto Keluar', 'foto_keluar');
      renderSavedGallery('e_preview_ba', row.file_ba, 'File BA', 'file_ba');

      toggleBAUpload();
      openModal('editModal');
    }

    function renderEvidenceCol(photos, label) {
      const list = parsePhotos(photos);
      if (list.length === 0) {
        return `
          <div style="text-align: center; font-size: 11px;">
            <div style="height: 80px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--surface2); display: flex; align-items: center; justify-content: center; color: var(--text2);">Tidak ada</div>
            <span style="font-weight: 600; display: block; margin-top: 4px;">${label}</span>
          </div>`;
      }
      let html = `
        <div style="text-align: center; font-size: 11px;">
          <div style="display: flex; gap: 4px; overflow-x: auto; padding: 2px;">`;
      list.forEach(p => {
        const url = '/storage/' + p;
        html += `
          <div style="width: 80px; height: 80px; flex-shrink: 0; border: 1px solid var(--border); border-radius: var(--radius-sm); overflow: hidden; background: #0f172a; position: relative;">
            <img src="${url}" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" title="Klik untuk Review" onclick="openReviewModal('${url}', '${label}')">
            <button type="button" onclick="openReviewModal('${url}', '${label}')" style="position: absolute; bottom: 3px; right: 3px; background: rgba(0,0,0,0.65); color: #fff; border: none; border-radius: 3px; width: 20px; height: 20px; font-size: 9px; cursor: pointer; display: flex; align-items: center; justify-content: center;"><i class="fas fa-eye"></i></button>
          </div>`;
      });
      html += `
          </div>
          <span style="font-weight: 600; display: block; margin-top: 4px;">${label} (${list.length})</span>
        </div>`;
      return html;
    }

    function viewSparepart(row) {
      const baFiles = parsePhotos(row.file_ba);
      let baButtons = `<a href="/engineering/sparepart/${row.id}/print-ba" target="_blank" class="btn btn-sm btn-primary" style="text-decoration: none;"><i class="fas fa-file-pdf"></i> Cetak BA Otomatis</a>`;
      if (baFiles.length > 0) {
        baFiles.forEach((bf, idx) => {
          baButtons += `<a href="/storage/${bf}" target="_blank" class="btn btn-sm btn-outline" style="text-decoration: none; border-color: var(--success); color: var(--success);"><i class="fas fa-file-signature"></i> Lihat BA Signed ${baFiles.length > 1 ? (idx + 1) : ''}</a>`;
        });
      }

      const content = `
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;padding:10px;background:var(--surface2);border-radius:var(--radius-sm)">
        <div><strong>Lokasi:</strong><br>${row.lokasi_pekerjaan}</div>
        <div><strong>Ruang:</strong><br>${row.ruang || '-'}</div>
        <div><strong>Jenis:</strong><br>${row.jenis_pekerjaan}</div>
        <div><strong>Type:</strong><br>${row.type || '-'}</div>
        <div><strong>Qty:</strong><br>${row.qty} ${row.satuan}</div>
        <div><strong>Teknisi:</strong><br>${Array.isArray(row.teknisi) ? row.teknisi.join(', ') : (row.teknisi || '-')}</div>
        <div><strong>Status:</strong><br><span class="badge badge-${row.status === 'DONE' ? 'success' : (row.status === 'PROSES' ? 'warning' : 'danger')}">${row.status}</span></div>
        <div><strong>Tgl Mulai:</strong><br>${row.tgl_masuk ? new Date(row.tgl_masuk).toLocaleDateString('id-ID') : '-'}</div>
        <div><strong>Tgl Selesai:</strong><br>${row.tgl_selesai ? new Date(row.tgl_selesai).toLocaleDateString('id-ID') : '-'}</div>
        <div><strong>Harga:</strong><br>${(row.harga && parseFloat(row.harga) > 0) ? 'Rp ' + new Intl.NumberFormat('id-ID').format(row.harga) : '-'}</div>
        <div style="grid-column: span 2"><strong>Total Biaya:</strong><br>${(row.total_biaya && parseFloat(row.total_biaya) > 0) ? 'Rp ' + new Intl.NumberFormat('id-ID').format(row.total_biaya) : '-'}</div>
      </div>
      <div style="margin-bottom:12px"><strong>Kerusakan:</strong><br>${row.kerusakan || '-'}</div>
      <div style="margin-bottom:12px"><strong>Action (Work Done):</strong><br>${row.action || '-'}</div>
      <div style="margin-bottom:12px"><strong>Pergantian Perangkat:</strong><br>${row.pergantian_perangkat || '-'}</div>
      <div style="margin-bottom:12px"><strong>Keterangan Tambahan:</strong><br>${row.keterangan_tambahan || '-'}</div>
       <div style="margin-bottom:12px"><strong>Catatan:</strong><br>${row.keterangan || '-'}</div>

      <div style="margin-top: 15px; border-top: 1px solid var(--border); padding-top: 15px;">
        <strong>Dokumentasi Kegiatan (Evidence)</strong>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 8px;">
          ${renderEvidenceCol(row.foto_masuk, 'Foto Masuk')}
          ${renderEvidenceCol(row.foto_proses, 'Foto Proses')}
          ${renderEvidenceCol(row.foto_keluar, 'Foto Keluar')}
        </div>
      </div>

      <div style="margin-top: 15px; border-top: 1px solid var(--border); padding-top: 15px;">
        <strong>Berita Acara (BA)</strong>
        <div style="display: flex; gap: 8px; margin-top: 8px; flex-wrap: wrap;">
          ${baButtons}
        </div>
      </div>
    `;
      document.getElementById('viewContent').innerHTML = content;
      openModal('viewModal');
    }

    setTimeout(() => { const a = document.getElementById('alertBox'); if (a) a.remove(); const b = document.getElementById('alertBoxErr'); if (b) b.remove(); }, 4000);
  </script>
@endsection
@section('extra-styles')
  <style>
    .alert-success {
      background: rgba(16, 185, 129, .12);
      border: 1px solid rgba(16, 185, 129, .3);
      color: #10b981;
      padding: 12px 16px;
      border-radius: var(--radius-sm);
      margin-bottom: 16px;
      font-weight: 600;
      font-size: 14px;
    }

    .alert-error {
      background: rgba(239, 68, 68, .12);
      border: 1px solid rgba(239, 68, 68, .3);
      color: #ef4444;
      padding: 12px 16px;
      border-radius: var(--radius-sm);
      margin-bottom: 16px;
      font-weight: 600;
      font-size: 14px;
    }

    .badge-outline {
      background: transparent;
      border: 1px solid var(--border);
      color: var(--text2);
      font-weight: 500;
    }

    .multi-select-container {
      position: relative;
    }

    .multi-select-display {
      padding: 9px 12px;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      background: var(--surface);
      display: flex;
      justify-content: space-between;
      align-items: center;
      cursor: pointer;
      font-size: 13px;
      min-height: 38px;
    }

    .multi-select-display span {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 180px;
      color: var(--text2);
    }

    .multi-select-list {
      position: absolute;
      top: 105%;
      left: 0;
      right: 0;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      box-shadow: var(--shadow-lg);
      z-index: 10;
      max-height: 200px;
      overflow-y: auto;
      display: none;
      padding: 4px;
    }

    .multi-select-list.show {
      display: block;
    }

    .multi-select-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 10px;
      cursor: pointer;
      border-radius: 4px;
      transition: var(--transition);
      font-size: 13px;
    }

    .multi-select-item:hover {
      background: var(--surface2);
    }

    .multi-select-item input {
      width: auto;
      margin: 0;
    }

    .multi-select-item span {
      flex: 1;
    }

    /* Table Styling for Wide Data */
    .table-wrap {
      width: 100%;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      border-radius: var(--radius-sm);
    }
    table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 1500px; }
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
      white-space: nowrap;
    }
    table td { padding: 12px 16px !important; border-bottom: 1px solid var(--border); vertical-align: top; font-size: 12px; }
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

    /* Drag & Drop Upload Zone Styling */
    .upload-dropzone {
      border: 2px dashed var(--border) !important;
      border-radius: var(--radius-sm) !important;
      padding: 16px 12px !important;
      text-align: center !important;
      background: var(--surface2) !important;
      cursor: pointer !important;
      transition: all 0.2s ease-in-out !important;
      position: relative !important;
      min-height: 125px !important;
      display: flex !important;
      flex-direction: column !important;
      align-items: center !important;
      justify-content: center !important;
      outline: none !important;
      width: 100% !important;
      box-sizing: border-box !important;
    }
    .upload-dropzone:hover, .upload-dropzone:focus, .upload-dropzone.dz-focused {
      border-color: var(--primary) !important;
      background: rgba(59, 130, 246, 0.05) !important;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
    }
    .upload-dropzone.drag-active {
      border-color: #25D366 !important;
      background: rgba(37, 211, 102, 0.1) !important;
      border-style: solid !important;
      transform: scale(1.02) !important;
    }
    .upload-placeholder {
      display: flex !important;
      flex-direction: column !important;
      align-items: center !important;
      justify-content: center !important;
      gap: 5px !important;
      color: var(--text2) !important;
      pointer-events: none !important;
      width: 100% !important;
    }
    .upload-icon-circle {
      width: 38px !important;
      height: 38px !important;
      border-radius: 50% !important;
      background: rgba(59, 130, 246, 0.1) !important;
      color: var(--primary) !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      font-size: 16px !important;
      margin-bottom: 2px !important;
      transition: transform 0.2s ease !important;
    }
    .upload-dropzone:hover .upload-icon-circle {
      transform: scale(1.1) !important;
      background: var(--primary) !important;
      color: #ffffff !important;
    }
    .upload-title {
      font-size: 12px !important;
      font-weight: 700 !important;
      color: var(--text) !important;
      display: block !important;
      line-height: 1.3 !important;
      text-align: center !important;
    }
    .upload-sub {
      font-size: 11px !important;
      color: var(--text2) !important;
      display: block !important;
      line-height: 1.3 !important;
      text-align: center !important;
    }
    .upload-badge {
      display: inline-flex !important;
      align-items: center !important;
      gap: 4px !important;
      padding: 3px 8px !important;
      border-radius: 12px !important;
      background: rgba(37, 211, 102, 0.12) !important;
      color: #16a34a !important;
      font-size: 10px !important;
      font-weight: 600 !important;
      margin-top: 3px !important;
    }
    .upload-preview {
      display: flex !important;
      flex-direction: column !important;
      align-items: center !important;
      width: 100% !important;
      position: relative !important;
    }
    .preview-thumb-wrap {
      position: relative !important;
      width: 100% !important;
      height: 110px !important;
      overflow: hidden !important;
      border-radius: 8px !important;
      border: 1px solid var(--border) !important;
      background: #0f172a !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1) !important;
    }
    .preview-thumb-wrap img {
      width: 100% !important;
      height: 110px !important;
      object-fit: cover !important;
    }
    .remove-file-btn {
      position: absolute !important;
      top: 6px !important;
      right: 6px !important;
      background: rgba(239, 68, 68, 0.95) !important;
      color: #fff !important;
      border: none !important;
      border-radius: 50% !important;
      width: 24px !important;
      height: 24px !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      font-size: 11px !important;
      cursor: pointer !important;
      transition: transform 0.15s ease, background 0.15s ease !important;
      z-index: 10 !important;
      box-shadow: 0 2px 4px rgba(0,0,0,0.2) !important;
    }
    .remove-file-btn:hover {
      background: #dc2626 !important;
      transform: scale(1.15) !important;
    }
    .preview-file-info {
      font-size: 10px !important;
      color: var(--text) !important;
      font-weight: 600 !important;
      margin-top: 5px !important;
      white-space: nowrap !important;
      overflow: hidden !important;
      text-overflow: ellipsis !important;
      max-width: 100% !important;
      background: var(--surface) !important;
      padding: 2px 8px !important;
      border-radius: 4px !important;
      border: 1px solid var(--border) !important;
    }
  </style>
@endsection
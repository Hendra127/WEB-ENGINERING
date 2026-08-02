@extends('layouts.app')
@section('title','Dashboard - Engineering')
@section('page-title','Dashboard')
@section('content')
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon" style="background:rgba(59,130,246,.12);color:#3b82f6"><i class="fas fa-tools"></i></div>
    <div><div class="stat-value">{{ $totalSparepart ?? 48 }}</div><div class="stat-label">Total Sparepart</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:rgba(16,185,129,.12);color:#10b981"><i class="fas fa-toolbox"></i></div>
    <div><div class="stat-value">{{ $totalAlat ?? 32 }}</div><div class="stat-label">Alat Kantor</div></div>
  </div>
  {{-- 
  <div class="stat-card">
    <div class="stat-icon" style="background:rgba(245,158,11,.12);color:#f59e0b"><i class="fas fa-boxes"></i></div>
    <div><div class="stat-value">{{ $totalKlasifikasi ?? 17 }}</div><div class="stat-label">Log Barang Masuk dan Keluar</div></div>
  </div>
  --}}
  <div class="stat-card">
    <div class="stat-icon" style="background:rgba(239,68,68,.12);color:#ef4444"><i class="fas fa-exclamation-triangle"></i></div>
    <div><div class="stat-value">{{ $totalPending ?? 5 }}</div><div class="stat-label">Pending Request</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:rgba(139,92,246,.12);color:#8b5cf6"><i class="fas fa-hand-holding"></i></div>
    <div><div class="stat-value">{{ $totalPeminjaman ?? 0 }}</div><div class="stat-label">Alat Dipinjam</div></div>
  </div>
</div>
<div class="grid-2">
  <div class="card">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center">
      <span class="card-title"><i class="fas fa-history" style="color:var(--primary)"></i> Aktivitas Terbaru</span>
      <a href="{{ route('engineering.peminjaman') }}" style="font-size:12px;color:var(--primary);text-decoration:none;font-weight:600">Lihat Semua <i class="fas fa-arrow-right"></i></a>
    </div>
    <div style="display:flex;flex-direction:column;gap:12px">
      {{-- Peminjaman Alat Items --}}
      @foreach($recentPeminjaman as $pem)
      <div onclick="viewPeminjamanDetail({{ json_encode($pem) }})" style="display:flex;align-items:center;gap:12px;padding:10px;background:var(--surface2);border-radius:8px;cursor:pointer;transition:all .15s ease" onmouseover="this.style.background='rgba(59,130,246,.08)'" onmouseout="this.style.background='var(--surface2)'" title="Klik untuk melihat detail peminjam">
        <div style="width:36px;height:36px;border-radius:8px;background:rgba(239,68,68,.12);color:#ef4444;display:flex;align-items:center;justify-content:center;font-size:14px">
          <i class="fas fa-hand-holding"></i>
        </div>
        <div style="flex:1">
          <div style="font-size:13px;font-weight:600;display:flex;align-items:center;gap:6px">
            {{ $pem->nama_alat }}
            <span style="font-size:11px;font-weight:500;color:var(--text2)">({{ $pem->qty }} Unit)</span>
          </div>
          <div style="font-size:12px;color:var(--text2)"><i class="fas fa-info-circle" style="font-size:10px"></i> Peminjaman Alat &bull; Klik untuk info peminjam</div>
        </div>
        <span class="badge badge-danger"><i class="fas fa-clock"></i> DIPINJAM</span>
      </div>
      @endforeach

      {{-- Sparepart Log Items --}}
      @foreach($recent as $act)
      <div style="display:flex;align-items:center;gap:12px;padding:10px;background:var(--surface2);border-radius:8px">
        <div style="width:36px;height:36px;border-radius:8px;background:rgba(59,130,246,.1);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:14px"><i class="fas fa-tools"></i></div>
        <div style="flex:1"><div style="font-size:13px;font-weight:600">{{ $act->type ?: $act->jenis_pekerjaan }}</div><div style="font-size:12px;color:var(--text2)">{{ $act->lokasi_pekerjaan }}</div></div>
        <span class="badge badge-{{ $act->status==='DONE'?'success':($act->status==='PROSES'?'warning':'danger') }}">{{ $act->status }}</span>
      </div>
      @endforeach

      @if($recentPeminjaman->isEmpty() && $recent->isEmpty())
      <div style="text-align:center;padding:20px;color:var(--text2);font-size:13px">Belum ada aktivitas terbaru.</div>
      @endif
    </div>

  </div>
  <div class="card">
    <div class="card-header"><span class="card-title"><i class="fas fa-chart-pie" style="color:var(--accent)"></i> Status Barang</span></div>
    <div style="display:flex;flex-direction:column;gap:10px">
      @foreach([['Baik','75','success'],['Rusak Ringan','15','warning'],['Rusak Berat','10','danger']] as $s)
      <div>
        <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px"><span>{{ $s[0] }}</span><strong>{{ $s[1] }}%</strong></div>
        <div style="height:8px;background:var(--surface2);border-radius:4px;overflow:hidden"><div style="height:100%;width:{{ $s[1] }}%;background:{{ $s[2]=='success'?'#10b981':($s[2]=='warning'?'#f59e0b':'#ef4444') }};border-radius:4px;transition:width .8s ease"></div></div>
      </div>
      @endforeach
    </div>
    <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border);display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:12px">
      <a href="{{ route('engineering.sparepart') }}" class="btn btn-primary" style="justify-content:center"><i class="fas fa-tools"></i> Sparepart</a>
      <a href="{{ route('engineering.alat') }}" class="btn btn-outline" style="justify-content:center"><i class="fas fa-toolbox"></i> Alat Kantor</a>
      <a href="{{ route('engineering.peminjaman') }}" class="btn btn-outline" style="justify-content:center"><i class="fas fa-hand-holding"></i> Peminjaman</a>
    </div>
  </div>
</div>

{{-- Modal Detail Peminjaman --}}
<div class="modal-overlay" id="viewPeminjamanModal">
  <div class="modal" style="max-width:500px">
    <div class="modal-header">
      <span class="modal-title"><i class="fas fa-info-circle" style="color:var(--primary)"></i> Detail Peminjaman Alat</span>
      <button type="button" onclick="closeModal('viewPeminjamanModal')" style="background:none;border:none;cursor:pointer;font-size:16px;color:var(--text2)"><i class="fas fa-times"></i></button>
    </div>
    <div id="modalPeminjamanContent">
      <!-- Dynamic Javascript Content -->
    </div>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:16px;padding-top:12px;border-top:1px solid var(--border)">
      <a href="{{ route('engineering.peminjaman') }}" class="btn btn-outline btn-sm"><i class="fas fa-external-link-alt"></i> Ke Halaman Peminjaman</a>
      <button type="button" class="btn btn-primary btn-sm" onclick="closeModal('viewPeminjamanModal')">Tutup</button>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
function openModal(id){
  document.getElementById(id).classList.add('open');
}
function closeModal(id){
  document.getElementById(id).classList.remove('open');
}
document.querySelectorAll('.modal-overlay').forEach(m=>{
  m.addEventListener('click', e=>{ if(e.target===m) m.classList.remove('open'); });
});

function viewPeminjamanDetail(row) {
  const tglPinjamFormatted = row.tgl_pinjam ? new Date(row.tgl_pinjam).toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric'}) : '-';
  const tglKembaliFormatted = row.tgl_kembali ? new Date(row.tgl_kembali).toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric'}) : '-';

  let detailLokasi = '-';
  if (row.alat_kantor && row.alat_kantor.tempat) {
    detailLokasi = row.alat_kantor.tempat;
  }

  const content = `
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;padding:12px;background:var(--surface2);border-radius:var(--radius-sm)">
      <div>
        <div style="font-size:11px;color:var(--text2);text-transform:uppercase;font-weight:600">Nama Barang / Alat</div>
        <div style="font-size:14px;font-weight:700;color:var(--primary);margin-top:2px"><i class="fas fa-toolbox"></i> ${row.nama_alat}</div>
      </div>
      <div>
        <div style="font-size:11px;color:var(--text2);text-transform:uppercase;font-weight:600">Jumlah Dipinjam (QTY)</div>
        <div style="font-size:14px;font-weight:700;margin-top:2px">${row.qty} Unit</div>
      </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:10px;font-size:13px">
      <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px dashed var(--border)">
        <span style="color:var(--text2)"><i class="fas fa-user" style="width:20px;color:var(--primary)"></i> Nama Peminjam</span>
        <strong style="color:var(--text)">${row.nama_peminjam}</strong>
      </div>
      <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px dashed var(--border)">
        <span style="color:var(--text2)"><i class="fas fa-calendar-alt" style="width:20px;color:var(--primary)"></i> Tanggal Pinjam</span>
        <strong>${tglPinjamFormatted}</strong>
      </div>
      <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px dashed var(--border)">
        <span style="color:var(--text2)"><i class="fas fa-calendar-check" style="width:20px;color:var(--primary)"></i> Tanggal Kembali</span>
        <strong>${tglKembaliFormatted}</strong>
      </div>
      <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px dashed var(--border)">
        <span style="color:var(--text2)"><i class="fas fa-info-circle" style="width:20px;color:var(--primary)"></i> Status</span>
        <span class="badge badge-${row.status==='DIPINJAM'?'danger':'success'}">${row.status}</span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px dashed var(--border)">
        <span style="color:var(--text2)"><i class="fas fa-map-marker-alt" style="width:20px;color:var(--primary)"></i> Lokasi Penyimpanan</span>
        <strong>${detailLokasi}</strong>
      </div>
      <div style="padding:8px 0">
        <span style="color:var(--text2);display:block;margin-bottom:4px"><i class="fas fa-sticky-note" style="width:20px;color:var(--primary)"></i> Keterangan</span>
        <div style="padding:10px;background:var(--surface2);border-radius:6px;font-size:12px;color:var(--text)">${row.keterangan || 'Tidak ada keterangan.'}</div>
      </div>
    </div>
  `;

  document.getElementById('modalPeminjamanContent').innerHTML = content;
  openModal('viewPeminjamanModal');
}
</script>
@endsection

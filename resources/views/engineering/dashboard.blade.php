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
  <div class="stat-card">
    <div class="stat-icon" style="background:rgba(245,158,11,.12);color:#f59e0b"><i class="fas fa-boxes"></i></div>
    <div><div class="stat-value">{{ $totalKlasifikasi ?? 17 }}</div><div class="stat-label">Log Barang Masuk dan Keluar</div></div>
  </div>
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
    <div class="card-header"><span class="card-title"><i class="fas fa-history" style="color:var(--primary)"></i> Aktivitas Terbaru</span></div>
    <div style="display:flex;flex-direction:column;gap:12px">
      @forelse($recent as $act)
      <div style="display:flex;align-items:center;gap:12px;padding:10px;background:var(--surface2);border-radius:8px">
        <div style="width:36px;height:36px;border-radius:8px;background:rgba(59,130,246,.1);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:14px"><i class="fas fa-tools"></i></div>
        <div style="flex:1"><div style="font-size:13px;font-weight:600">{{ $act->type ?: $act->jenis_pekerjaan }}</div><div style="font-size:12px;color:var(--text2)">{{ $act->lokasi_pekerjaan }}</div></div>
        <span class="badge badge-{{ $act->status==='DONE'?'success':($act->status==='PROSES'?'warning':'danger') }}">{{ $act->status }}</span>
      </div>
      @empty
      <div style="text-align:center;padding:20px;color:var(--text2);font-size:13px">Belum ada aktivitas terbaru.</div>
      @endforelse
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
@endsection

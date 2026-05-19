@extends('layouts.app')

@section('title', 'Pengajuan Perangkat')
@section('page-title', 'Pengajuan Perangkat')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengajuan Perangkat</h3>
        <div class="card-actions">
            <button class="btn btn-primary" onclick="openModal('addModal')">
                <i class="fas fa-plus"></i> Tambah Pengajuan
            </button>
        </div>
    </div>

    @if(session('success'))
    <div style="padding:12px; background:rgba(16,185,129,.12); color:#10b981; border-radius:8px; margin-bottom:16px; font-size:13px; font-weight:600;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="padding:12px; background:rgba(239,68,68,.12); color:#ef4444; border-radius:8px; margin-bottom:16px; font-size:13px; font-weight:600;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th width="50">NO</th>
                    <th>PENGUSUL</th>
                    <th>NAMA PERANGKAT</th>
                    <th>JUMLAH</th>
                    <th>ALASAN</th>
                    <th>STATUS</th>
                    <th width="150">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                <tr>
                    <td>{{ $data->firstItem() + $index }}</td>
                    <td>
                        <div style="font-weight:600">{{ $item->user->name ?? '-' }}</div>
                        <div style="font-size:11px; color:var(--text2)">{{ ucfirst($item->user->role ?? '') }}</div>
                    </td>
                    <td style="font-weight:500;">{{ $item->nama_perangkat }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ Str::limit($item->alasan, 50) }}</td>
                    <td>
                        @if($item->status == 'pending_manager')
                            <span class="badge badge-warning"><i class="fas fa-clock"></i> Menunggu Manager</span>
                        @elseif($item->status == 'pending_accounting')
                            <span class="badge badge-warning"><i class="fas fa-clock"></i> Menunggu Accounting</span>
                        @elseif($item->status == 'pending_direktur')
                            <span class="badge badge-warning"><i class="fas fa-clock"></i> Menunggu Direktur</span>
                        @elseif($item->status == 'approved')
                            <span class="badge badge-success"><i class="fas fa-check"></i> Disetujui</span>
                        @elseif($item->status == 'rejected')
                            <span class="badge badge-danger" title="Alasan: {{ $item->alasan_penolakan }}"><i class="fas fa-times"></i> Ditolak</span>
                            <div style="font-size:11px; color:#ef4444; margin-top:4px;">Alasan: {{ $item->alasan_penolakan }}</div>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:8px">
                            @if(
                                (auth()->user()->role === 'manager' && $item->status === 'pending_manager') ||
                                (auth()->user()->role === 'accounting' && $item->status === 'pending_accounting') ||
                                (auth()->user()->role === 'direktur' && $item->status === 'pending_direktur')
                            )
                                <form action="{{ route('engineering.pengajuan_perangkat.approve', $item->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm" style="background:#10b981; color:#fff;" title="Approve">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-outline" style="color:#ef4444; border-color:#ef4444;" title="Reject" onclick="openRejectModal({{ $item->id }})">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            @endif
                            
                            <a href="{{ route('engineering.pengajuan_perangkat.print', $item->id) }}" target="_blank" class="btn btn-sm btn-outline" style="color:#3b82f6; border-color:#3b82f6;" title="Print">
                                <i class="fas fa-print"></i>
                            </a>

                            <form action="{{ route('engineering.pengajuan_perangkat.destroy', $item->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline" style="color:#ef4444; border-color:#ef4444;" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:30px; color:var(--text2)">Belum ada data pengajuan perangkat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px">
        {{ $data->links() }}
    </div>
</div>

<div class="modal-overlay" id="addModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Pengajuan Perangkat</h3>
            <button class="btn-icon" onclick="closeModal('addModal')"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('engineering.pengajuan_perangkat.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Perangkat</label>
                <input type="text" name="nama_perangkat" required placeholder="Contoh: Laptop, Printer, dsb">
            </div>
            <div class="form-group">
                <label>Jumlah</label>
                <input type="text" name="jumlah" required placeholder="Contoh: 1 Unit, 2 Pcs, dsb">
            </div>
            <div class="form-group">
                <label>Alasan Pengajuan</label>
                <textarea name="alasan" rows="4" required placeholder="Jelaskan untuk keperluan apa perangkat ini..."></textarea>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px">
                <button type="button" class="btn btn-outline" onclick="closeModal('addModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal-overlay" id="rejectModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Tolak Pengajuan</h3>
            <button class="btn-icon" onclick="closeModal('rejectModal')"><i class="fas fa-times"></i></button>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="form-group">
                <label>Alasan Penolakan</label>
                <textarea name="alasan_penolakan" id="alasan_penolakan" rows="4" required placeholder="Tulis alasan mengapa pengajuan ditolak..."></textarea>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px">
                <button type="button" class="btn btn-outline" onclick="closeModal('rejectModal')">Batal</button>
                <button type="submit" class="btn btn-primary" style="background:#ef4444; border-color:#ef4444;">Tolak Pengajuan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

function openRejectModal(id) {
    document.getElementById('rejectForm').action = '/engineering/pengajuan-perangkat/' + id + '/reject';
    openModal('rejectModal');
}
</script>
@endsection

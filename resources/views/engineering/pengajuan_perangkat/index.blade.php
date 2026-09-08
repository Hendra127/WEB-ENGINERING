@extends('layouts.app')

@section('title', 'Pengajuan Perangkat')
@section('page-title', 'Pengajuan Perangkat')

@section('content')
<style>
    .page-header-card {
        background: var(--surface, #ffffff);
        border: 1px solid var(--border, #e2e8f0);
        border-radius: 12px;
        padding: 18px 24px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        box-shadow: var(--shadow, 0 1px 3px rgba(0, 0, 0, 0.03));
    }
    .page-header-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .page-header-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: rgba(37, 99, 235, 0.12);
        color: #3b82f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .page-header-heading {
        font-size: 18px;
        font-weight: 700;
        color: var(--text, #0f172a);
        margin: 0;
    }
    .page-header-subtext {
        font-size: 12px;
        color: var(--text2, #64748b);
        margin-top: 2px;
    }
    .stat-badge {
        background: var(--surface2, #f8fafc);
        border: 1px solid var(--border, #e2e8f0);
        border-radius: 20px;
        padding: 6px 16px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text2, #475569);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .stat-badge-count {
        background: #2563eb;
        color: #ffffff;
        padding: 2px 9px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
    }
    /* Filter Card */
    .filter-card {
        background: var(--surface, #ffffff);
        border: 1px solid var(--border, #e2e8f0);
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 20px;
        box-shadow: var(--shadow, 0 1px 3px rgba(0, 0, 0, 0.02));
    }
    .filter-form {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .form-select-custom {
        width: auto !important;
        max-width: 170px;
        border-radius: 8px;
        padding: 6px 28px 6px 10px;
        border: 1px solid var(--border, #cbd5e1);
        font-size: 12.5px;
        color: var(--text, #334155);
        background-color: var(--surface, #ffffff);
        height: 36px;
        outline: none;
        transition: all 0.2s ease;
    }
    .form-input-custom {
        border-radius: 8px;
        padding: 6px 12px;
        border: 1px solid var(--border, #cbd5e1);
        font-size: 12.5px;
        color: var(--text, #334155);
        background-color: var(--surface, #ffffff);
        height: 36px;
        outline: none;
        transition: all 0.2s ease;
    }
    .form-select-custom:focus, .form-input-custom:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    .btn-action-filter {
        height: 36px;
        border-radius: 8px;
        padding: 0 14px;
        font-weight: 600;
        font-size: 12.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        white-space: nowrap;
    }
    .btn-blue {
        background: #2563eb;
        color: #ffffff;
    }
    .btn-blue:hover {
        background: #1d4ed8;
    }
    .btn-green {
        background: #10b981;
        color: #ffffff;
    }
    .btn-green:hover {
        background: #059669;
    }
    .btn-reset {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1px solid var(--border, #cbd5e1);
        background: var(--surface, #ffffff);
        color: var(--text2, #64748b);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        flex-shrink: 0;
    }
    .btn-reset:hover {
        background: var(--surface2, #f1f5f9);
        color: var(--text, #0f172a);
    }

    .table-container {
        background: var(--surface, #ffffff);
        border: 1px solid var(--border, #e2e8f0);
        border-radius: 12px;
        box-shadow: var(--shadow, 0 1px 3px rgba(0,0,0,0.02));
        overflow: hidden;
    }
    .custom-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .custom-table th {
        background: var(--surface2, #f8fafc);
        border-bottom: 1px solid var(--border, #e2e8f0);
        padding: 12px 16px;
        font-size: 11px;
        font-weight: 700;
        color: var(--text2, #64748b);
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .custom-table td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border, #f1f5f9);
        vertical-align: middle;
        color: var(--text);
    }
    .custom-table tr:last-child td {
        border-bottom: none;
    }
    .custom-table tr:hover {
        background-color: var(--surface2, #f8fafc);
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .status-badge-paid {
        background: rgba(16, 185, 129, 0.12);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.25);
    }
    .status-badge-unpaid {
        background: rgba(239, 68, 68, 0.12);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.25);
    }

    .stepper-dots {
        display: flex;
        justify-content: center;
        gap: 5px;
        margin-top: 6px;
    }
    .stepper-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        transition: background 0.2s ease;
    }
    .stepper-dot.active {
        background: #10b981;
    }
    .stepper-dot.inactive {
        background: var(--border, #cbd5e1);
    }

    .btn-action-group {
        display: flex;
        justify-content: center;
        gap: 6px;
    }
    .btn-tbl-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border, #e2e8f0);
        background: var(--surface, #ffffff);
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-tbl-action.btn-view {
        color: #38bdf8;
        border-color: rgba(56, 189, 248, 0.3);
        background: rgba(56, 189, 248, 0.1);
    }
    .btn-tbl-action.btn-view:hover {
        background: #0284c7;
        color: #ffffff;
    }
    .btn-tbl-action.btn-print {
        color: var(--text2, #475569);
        border-color: var(--border, #cbd5e1);
    }
    .btn-tbl-action.btn-print:hover {
        background: var(--surface2, #f1f5f9);
        color: var(--text, #0f172a);
    }
    .btn-tbl-action.btn-edit {
        color: #fbbf24;
        border-color: rgba(251, 191, 36, 0.3);
        background: rgba(251, 191, 36, 0.1);
    }
    .btn-tbl-action.btn-edit:hover {
        background: #d97706;
        color: #ffffff;
    }
    .btn-tbl-action.btn-delete {
        color: #f87171;
        border-color: rgba(248, 113, 113, 0.3);
        background: rgba(248, 113, 113, 0.1);
    }
    .btn-tbl-action.btn-delete:hover {
        background: #dc2626;
        color: #ffffff;
    }

    /* Dark Mode Global Overrides for Modals, Dynamic Row Cards & Form Control */
    html.dark .modal {
        background: var(--surface, #1e293b) !important;
        color: var(--text, #f1f5f9) !important;
        border: 1px solid var(--border, #334155) !important;
    }
    html.dark .modal h3, html.dark .modal h4, html.dark .modal h5, html.dark .modal label {
        color: var(--text, #f1f5f9) !important;
    }
    html.dark .modal input, html.dark .modal select, html.dark .modal textarea,
    html.dark .form-select-custom, html.dark .form-input-custom {
        background-color: var(--surface2, #263548) !important;
        color: var(--text, #f1f5f9) !important;
        border-color: var(--border, #334155) !important;
    }
    html.dark .device-item-row {
        background: var(--surface2, #263548) !important;
        border-color: var(--border, #334155) !important;
    }
    html.dark .modal div[style*="background: #f8fafc"],
    html.dark .modal div[style*="background:#f8fafc"],
    html.dark .modal div[style*="background: #ffffff"],
    html.dark .modal div[style*="background:#ffffff"],
    html.dark .modal div[style*="background: #fff"],
    html.dark .modal div[style*="background:#fff"] {
        background: var(--surface2, #263548) !important;
        border-color: var(--border, #334155) !important;
        color: var(--text, #f1f5f9) !important;
    }
    html.dark .modal span[style*="background: #f1f5f9"],
    html.dark .modal span[style*="background:#f1f5f9"] {
        background: var(--surface, #1e293b) !important;
        border-color: var(--border, #334155) !important;
        color: var(--text2, #94a3b8) !important;
    }
    html.dark .modal td, html.dark .modal th {
        border-color: var(--border, #334155) !important;
        color: var(--text, #f1f5f9) !important;
    }
    html.dark .modal th {
        background: var(--surface, #1e293b) !important;
    }
</style>
</style>

<!-- Clean Header Bar -->
<div class="page-header-card">
    <div class="page-header-title">
        <div class="page-header-icon">
            <i class="fas fa-file-invoice"></i>
        </div>
        <div>
            <h1 class="page-header-heading">Daftar Pengajuan Perangkat</h1>
            <div class="page-header-subtext">Kelola permohonan pengajuan & perbaikan perangkat engineering</div>
        </div>
    </div>
    <div class="stat-badge">
        <span>Total Sparepart Needed:</span>
        <span class="stat-badge-count">{{ $totalSparepartNeeded ?? 0 }}</span>
    </div>
</div>

<!-- Filters Bar -->
<div class="filter-card">
    <form method="GET" action="{{ route('engineering.pengajuan_perangkat') }}" class="filter-form">
        <select name="klasifikasi" class="form-select-custom" onchange="this.form.submit()">
            <option value="">Semua Klasifikasi</option>
            <option value="pembelian" {{ request('klasifikasi')=='pembelian'?'selected':'' }}>Pembelian Baru (Stok)</option>
            <option value="repair" {{ request('klasifikasi')=='repair'?'selected':'' }}>Repair Perangkat</option>
            <option value="pembelian_rt" {{ request('klasifikasi')=='pembelian_rt'?'selected':'' }}>Pembelian Peralatan Rumah Tangga</option>
        </select>

        <select name="status_bayar" class="form-select-custom" onchange="this.form.submit()">
            <option value="">Semua Status Bayar</option>
            <option value="Belum Dibayar" {{ request('status_bayar')=='Belum Dibayar'?'selected':'' }}>Belum Dibayar</option>
            <option value="Lunas" {{ request('status_bayar')=='Lunas'?'selected':'' }}>Lunas</option>
        </select>

        <select name="status" class="form-select-custom" onchange="this.form.submit()">
            <option value="">Semua Status Approval</option>
            <option value="pending_leader" {{ request('status')=='pending_leader'?'selected':'' }}>Menunggu Leader</option>
            <option value="pending_manager" {{ request('status')=='pending_manager'?'selected':'' }}>Menunggu Manager</option>
            <option value="pending_accounting" {{ request('status')=='pending_accounting'?'selected':'' }}>Menunggu Accounting</option>
            <option value="pending_direktur" {{ request('status')=='pending_direktur'?'selected':'' }}>Menunggu Direktur</option>
            <option value="pending_penasihat" {{ request('status')=='pending_penasihat'?'selected':'' }}>Menunggu Penasihat</option>
            <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Disetujui</option>
            <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Ditolak</option>
        </select>

        <button type="submit" class="btn-action-filter btn-blue">
            <i class="fas fa-filter"></i> Filter
        </button>

        <a href="{{ route('engineering.pengajuan_perangkat') }}" class="btn-reset" title="Reset Filter">
            <i class="fas fa-redo-alt"></i>
        </a>

        <div style="position:relative; flex:1; min-width:220px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No / Divisi / Perangkat..." class="form-input-custom" style="width:100%; padding-right:34px;">
            <i class="fas fa-search" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:13px;"></i>
        </div>

        <button type="button" class="btn-action-filter btn-green" onclick="openModal('addModal')">
            <i class="fas fa-plus-circle"></i> Buat Pengajuan
        </button>
    </form>
</div>

<!-- Main Table Container -->
<div class="table-container">
    <div class="table-wrap">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="text-align:left;">DETAIL PERANGKAT</th>
                    <th style="text-align:center;">TOTAL DANA</th>
                    <th style="text-align:center;">STATUS PEMBAYARAN</th>
                    <th style="text-align:center;">PROGRESS APPROVAL</th>
                    <th style="text-align:center;" width="180">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                @php
                    $details = $item->details ?? [];
                    $itemsList = $details['items'] ?? [];
                    $grandTotal = $details['grand_total'] ?? 0;
                    $statusBayar = $details['status_pembayaran'] ?? ($item->status == 'approved' ? 'Lunas' : 'Belum Dibayar');
                    
                    $deviceSummaryList = [];
                    if (!empty($itemsList)) {
                        foreach ($itemsList as $it) {
                            if (!empty($it['perangkat'])) {
                                $deviceSummaryList[] = $it['perangkat'] . ' (' . ($it['qty'] ?? 1) . ')';
                            }
                        }
                    }
                    $deviceSummaryText = !empty($deviceSummaryList) ? implode(', ', $deviceSummaryList) : $item->nama_perangkat;
                @endphp
                <tr>
                    <td style="color:var(--text, #1e293b); font-weight:600; max-width:320px;">
                        <div style="font-size:13.5px; line-height:1.4;">{{ $deviceSummaryText }}</div>
                        @if(!empty($details['no_pengajuan']))
                            <div style="font-size:11px; color:var(--text2, #64748b); font-weight:400; margin-top:3px;">
                                <i class="fas fa-hashtag" style="font-size:10px;"></i> {{ $details['no_pengajuan'] }}
                            </div>
                        @endif
                    </td>
                    <td style="text-align:center; font-weight:700; color:#10b981; font-size:14px;">
                        Rp {{ number_format($grandTotal, 0, ',', '.') }}
                    </td>
                    <td style="text-align:center;">
                        @if($statusBayar === 'Lunas')
                            <div>
                                <span class="status-badge status-badge-paid">
                                    <i class="fas fa-check-circle"></i> Lunas
                                </span>
                            </div>
                            <button type="button" class="btn btn-sm" onclick="showPaymentInfo({{ json_encode($item) }})" style="background:rgba(56, 189, 248, 0.15); color:#38bdf8; border-radius:6px; font-size:10px; font-weight:700; padding:2px 8px; margin-top:5px; border:1px solid rgba(56, 189, 248, 0.3); cursor:pointer;">
                                <i class="fas fa-info-circle"></i> Info
                            </button>
                        @else
                            <span class="status-badge status-badge-unpaid">
                                <i class="fas fa-exclamation-circle"></i> Belum Dibayar
                            </span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        @php
                            $stepCount = 1;
                            $badgeStyle = 'background:rgba(59, 130, 246, 0.15); color:#60a5fa; border:1px solid rgba(59, 130, 246, 0.3);';
                            $statusText = 'Menunggu Manager';

                            if ($item->status === 'pending_leader' || $item->status === 'pending_manager') {
                                $stepCount = 1;
                                $statusText = 'Menunggu Manager';
                                $badgeStyle = 'background:rgba(59, 130, 246, 0.15); color:#60a5fa; border:1px solid rgba(59, 130, 246, 0.3);';
                            } elseif ($item->status === 'pending_accounting') {
                                $stepCount = 2;
                                $statusText = 'Disetujui Manager – Menunggu Accounting';
                                $badgeStyle = 'background:rgba(59, 130, 246, 0.15); color:#60a5fa; border:1px solid rgba(59, 130, 246, 0.3);';
                            } elseif ($item->status === 'pending_direktur') {
                                $stepCount = 3;
                                $statusText = 'Disetujui Accounting – Menunggu Direktur';
                                $badgeStyle = 'background:rgba(59, 130, 246, 0.15); color:#60a5fa; border:1px solid rgba(59, 130, 246, 0.3);';
                            } elseif ($item->status === 'pending_penasihat' || $item->status === 'approved') {
                                $stepCount = 3;
                                $statusText = 'Disetujui (Lengkap)';
                                $badgeStyle = 'background:rgba(16, 185, 129, 0.15); color:#34d399; border:1px solid rgba(16, 185, 129, 0.3);';
                            } elseif ($item->status === 'rejected') {
                                $stepCount = 0;
                                $statusText = 'Ditolak';
                                $badgeStyle = 'background:rgba(239, 68, 68, 0.15); color:#f87171; border:1px solid rgba(239, 68, 68, 0.3);';
                            }
                        @endphp
                        
                        <span class="status-badge" style="{{ $badgeStyle }}">
                            <i class="fas fa-clock"></i> {{ $statusText }}
                        </span>

                        <div class="stepper-dots">
                            @for($d = 1; $d <= 3; $d++)
                                <span class="stepper-dot {{ $d <= $stepCount ? 'active' : 'inactive' }}"></span>
                            @endfor
                        </div>
                    </td>
                    <td style="text-align:center;">
                        @php
                            $userRole = auth()->user()->role;
                            $canApprove = false;
                            if (in_array($item->status, ['pending_leader', 'pending_manager']) && in_array($userRole, ['manager', 'admin'])) $canApprove = true;
                            if ($item->status === 'pending_accounting' && in_array($userRole, ['accounting', 'direktur', 'admin'])) $canApprove = true;
                            if (in_array($item->status, ['pending_direktur', 'pending_penasihat']) && in_array($userRole, ['direktur', 'accounting', 'admin'])) $canApprove = true;

                            $canReject = $canApprove || (in_array($userRole, ['manager', 'accounting', 'direktur', 'admin']) && !in_array($item->status, ['approved', 'rejected']));
                        @endphp
                        <div class="btn-action-group">
                            @if($canApprove)
                                <form action="{{ route('engineering.pengajuan_perangkat.approve', $item->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Setujui pengajuan ini?');">
                                    @csrf
                                    <button type="submit" class="btn-tbl-action" title="Setujui Pengajuan" style="color:#059669; border-color:#a7f3d0; background:#ecfdf5;">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif

                            @if($canReject)
                                <button type="button" class="btn-tbl-action" onclick="openRejectModal({{ json_encode($item) }})" title="Tolak Pengajuan" style="color:#dc2626; border-color:#fecaca; background:#fef2f2;">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif

                            <button type="button" class="btn-tbl-action btn-view" onclick="viewDetailModal({{ json_encode($item) }})" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            <a href="{{ route('engineering.pengajuan_perangkat.print', $item->id) }}" target="_blank" class="btn-tbl-action btn-print" title="Cetak">
                                <i class="fas fa-print"></i>
                            </a>

                            @if(in_array($userRole, ['admin', 'manager', 'leader']) || $item->user_id === auth()->id())
                                <button type="button" class="btn-tbl-action btn-edit" onclick="openEditModal({{ json_encode($item) }})" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </button>
                            @endif

                            @if(in_array($userRole, ['admin', 'manager', 'leader', 'karyawan']) || $item->user_id === auth()->id())
                                <form action="{{ route('engineering.pengajuan_perangkat.destroy', $item->id) }}" method="POST" style="margin:0; display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-tbl-action btn-delete" onclick="if(confirm('Apakah Anda yakin ingin menghapus data pengajuan ini?')) { this.closest('form').submit(); }" title="Hapus Pengajuan">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:40px 20px; color:#94a3b8;">
                        <i class="fas fa-inbox" style="font-size:32px; margin-bottom:8px; display:block; color:#cbd5e1;"></i>
                        Belum ada data pengajuan perangkat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($data->hasPages())
    <div style="padding:16px; border-top:1px solid #f1f5f9;">
        {{ $data->links() }}
    </div>
    @endif
</div>

<!-- Modal Pengajuan Perangkat (Siap Print) -->
<div class="modal-overlay" id="addModal">
    <div class="modal" style="max-width: 850px; width: 95%; max-height: 90vh; overflow-y: auto; padding: 0; border-radius: 12px;">
        <div class="modal-header" style="background: #15803d; color: #ffffff; padding: 14px 20px; border-top-left-radius: 12px; border-top-right-radius: 12px; display: flex; align-items: center; justify-content: space-between;">
            <h3 class="modal-title" style="color: #ffffff; font-size: 17px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-desktop" id="modalHeaderIcon"></i> <span id="modalHeaderTitle">Pengajuan Perangkat</span>
            </h3>
            <button type="button" onclick="closeModal('addModal')" style="background: none; border: none; color: #ffffff; font-size: 18px; cursor: pointer;"><i class="fas fa-times"></i></button>
        </div>

        <form action="{{ route('engineering.pengajuan_perangkat.store') }}" method="POST" style="padding: 20px;">
            @csrf
            <input type="hidden" name="id" id="edit_id" value="">
            
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <div style="font-size: 13px; font-weight: 700; color: #2563eb; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-cog"></i> Tipe Pengajuan:
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <select name="tipe_pengajuan" id="tipe_pengajuan_select" onchange="updateTipePengajuan(this.value)" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #cbd5e1; font-weight: 600; font-size: 13px; background: #fff;">
                        <option value="repair">Repair Perangkat</option>
                        <option value="pembelian">Pengajuan Perangkat (Pembelian)</option>
                        <option value="pembelian_rt">Pengajuan Pembelian Peralatan Rumah Tangga</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                <div>
                    <label style="font-size: 12px; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Tempat, Tanggal</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" name="tempat" id="form_tempat" value="Mataram" style="width: 40%; padding: 7px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;" required>
                        <input type="date" name="tanggal" id="form_tanggal" value="{{ date('Y-m-d') }}" style="width: 60%; padding: 7px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;" required>
                    </div>
                </div>
                <div>
                    <label style="font-size: 12px; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Divisi / Bagian</label>
                    <input type="text" name="divisi" id="form_divisi" value="Manage Service AI BAKTI" style="width: 100%; padding: 7px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;" required>
                </div>
                <div>
                    <label style="font-size: 12px; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">No. Surat</label>
                    <input type="text" name="no_pengajuan" id="form_no_pengajuan" value="-" style="width: 100%; padding: 7px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="font-size: 12px; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Keterangan Pengajuan Perangkat</label>
                <textarea name="keterangan_pengajuan" id="form_keterangan_pengajuan" rows="2" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12.5px; outline: none; font-family: inherit;">Dengan ini saya mengajukan perangkat sparepart untuk pergantian perangkat yang rusak dengan perincian sebagai berikut :</textarea>
            </div>

            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;">

            <div style="margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <h4 style="font-size: 14px; font-weight: 700; color: #334155; margin: 0;">Detail Perangkat</h4>
                    <button type="button" class="btn btn-sm btn-outline" style="color: #2563eb; border-color: #3b82f6; width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;" onclick="addDeviceRow()">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <div id="deviceItemsWrapper"></div>
            </div>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: flex-end; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <span style="font-weight: 700; font-size: 14px;">Grand Total :</span>
                    <div style="display: flex; align-items: center; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden;">
                        <span style="background: #15803d; color: #ffffff; padding: 8px 12px; font-weight: 700; font-size: 13px;">Rp</span>
                        <input type="hidden" name="grand_total" id="grandTotalValue" value="0">
                        <span id="grandTotalDisplay" style="padding: 8px 16px; font-weight: 700; font-size: 15px; color: #0f172a;">0</span>
                    </div>
                </div>

                <div style="margin-bottom: 10px;">
                    <label style="font-size: 12px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Terbilang</label>
                    <input type="text" id="terbilangInput" name="terbilang" oninput="onTerbilangManualInput(this.value)" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px 12px; font-size: 13px; color: #334155; width: 100%; font-weight: 500;" value="Nol Rupiah">
                </div>

                <div>
                    <label style="font-size: 12px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Catatan Tambahan</label>
                    <input type="text" id="catatanInput" name="catatan" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px 12px; font-size: 13px; color: #334155; width: 100%; font-weight: 500;" value="-">
                </div>
            </div>

            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;">

            <div style="margin-bottom: 20px;">
                <h4 style="font-size: 14px; font-weight: 700; color: #334155; margin-bottom: 12px;">Tertanda (Tanda Tangan & Approval)</h4>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div>
                        <label style="font-size: 12px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Pemohon</label>
                        <select name="pemohon_nama" id="form_pemohon_nama" style="margin-bottom: 6px; width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; outline: none; background: #ffffff;">
                            <option value="Lalu Taufik Wijaya">Lalu Taufik Wijaya</option>
                            <option value="Misdan">Misdan</option>
                            <option value="Rossie Maulana Septian, S.Kom">Rossie Maulana Septian, S.Kom</option>
                        </select>
                        <select name="pemohon_jabatan" id="form_pemohon_jabatan" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; outline: none; background: #ffffff;">
                            <option value="Engineering Leader">Engineering Leader</option>
                            <option value="Rumah Tangga">Rumah Tangga</option>
                            <option value="NOC Leader">NOC Leader</option>
                            <option value="Leader VSAT">Leader VSAT</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Diverifikasi 1</label>
                        <input type="text" name="verifikasi1_nama" value="Dimas Farid Awaludin, S.Kom" readonly style="margin-bottom: 6px; background: #f1f5f9; color: #475569; cursor: not-allowed;" placeholder="Nama">
                        <input type="text" name="verifikasi1_jabatan" value="Manager" readonly style="background: #f1f5f9; color: #475569; cursor: not-allowed;" placeholder="Jabatan">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px;">
                    <div>
                        <label style="font-size: 12px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Diverifikasi 2</label>
                        <input type="text" name="verifikasi2_nama" value="Baiq Nana Erlina, A.Md" readonly style="margin-bottom: 6px; background: #f1f5f9; color: #475569; cursor: not-allowed;" placeholder="Nama">
                        <input type="text" name="verifikasi2_jabatan" value="Accounting" readonly style="background: #f1f5f9; color: #475569; cursor: not-allowed;" placeholder="Jabatan">
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Disetujui</label>
                        <input type="text" name="disetujui_nama" value="Galuh Zakiyatun, S.Kom" readonly style="margin-bottom: 6px; background: #f1f5f9; color: #475569; cursor: not-allowed;" placeholder="Nama">
                        <input type="text" name="disetujui_jabatan" value="Direktur" readonly style="background: #f1f5f9; color: #475569; cursor: not-allowed;" placeholder="Jabatan">
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Mengetahui</label>
                        <input type="text" name="mengetahui_nama" value="Raden Yuniarta Alba, S.Kom" readonly style="margin-bottom: 6px; background: #f1f5f9; color: #475569; cursor: not-allowed;" placeholder="Nama">
                        <input type="text" name="mengetahui_jabatan" value="Penasihat" readonly style="background: #f1f5f9; color: #475569; cursor: not-allowed;" placeholder="Jabatan">
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
                <button type="button" class="btn btn-outline" onclick="closeModal('addModal')">Batal</button>
                <button type="submit" name="action" value="save" class="btn btn-primary" style="background: #2563eb; border-color: #2563eb;">
                    <i class="fas fa-save"></i> Save
                </button>
                <button type="submit" name="action" value="print" class="btn btn-primary" style="background: #15803d; border-color: #15803d;">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Info Pembayaran -->
<div class="modal-overlay" id="paymentInfoModal">
    <div class="modal" style="max-width: 500px; width: 90%;">
        <div class="modal-header">
            <h3 class="modal-title" style="display:flex; align-items:center; gap:8px;">
                <i class="fas fa-info-circle" style="color:#0ea5e9"></i> Informasi Pembayaran
            </h3>
            <button class="btn-icon" onclick="closeModal('paymentInfoModal')"><i class="fas fa-times"></i></button>
        </div>
        <div id="paymentInfoContent" style="padding: 16px 0;"></div>
        <div style="display:flex; justify-content:flex-end;">
            <button type="button" class="btn btn-primary" onclick="closeModal('paymentInfoModal')">Tutup</button>
        </div>
    </div>
</div>

<!-- Modal View Detail -->
<div class="modal-overlay" id="viewDetailModal">
    <div class="modal" style="max-width: 650px; width: 90%;">
        <div class="modal-header">
            <h3 class="modal-title" style="display:flex; align-items:center; gap:8px;">
                <i class="fas fa-file-alt" style="color:#2563eb"></i> Detail Pengajuan Perangkat
            </h3>
            <button class="btn-icon" onclick="closeModal('viewDetailModal')"><i class="fas fa-times"></i></button>
        </div>
        <div id="viewDetailContent" style="padding: 16px 0;"></div>
        <div style="display:flex; justify-content:flex-end; gap:10px;">
            <button type="button" class="btn btn-outline" onclick="closeModal('viewDetailModal')">Tutup</button>
            <a id="printBtnDetail" href="#" target="_blank" class="btn btn-primary" style="background:#15803d; border-color:#15803d;">
                <i class="fas fa-print"></i> Cetak Dokumen
            </a>
        </div>
    </div>
</div>

<!-- Modal Penolakan -->
<div class="modal-overlay" id="rejectModal">
    <div class="modal" style="max-width: 480px; width: 90%;">
        <div class="modal-header">
            <h3 class="modal-title" style="color:#dc2626; display:flex; align-items:center; gap:8px;">
                <i class="fas fa-exclamation-triangle"></i> Tolak Pengajuan Perangkat
            </h3>
            <button class="btn-icon" onclick="closeModal('rejectModal')"><i class="fas fa-times"></i></button>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            <div style="padding: 10px 0;">
                <label style="font-size:12px; font-weight:700; color:#334155; display:block; margin-bottom:6px;">Alasan Penolakan <span style="color:#dc2626;">*</span></label>
                <textarea name="alasan_penolakan" rows="3" required placeholder="Tuliskan alasan penolakan secara jelas..." style="width:100%; border:1px solid #cbd5e1; border-radius:8px; padding:10px; font-size:13px; outline:none; font-family:inherit;"></textarea>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:16px;">
                <button type="button" class="btn btn-outline" onclick="closeModal('rejectModal')">Batal</button>
                <button type="submit" class="btn btn-primary" style="background:#dc2626; border-color:#dc2626;">
                    <i class="fas fa-times-circle"></i> Konfirmasi Penolakan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
let itemIndex = 0;
let isTerbilangManual = false;

function onTerbilangManualInput(val) {
    if (val.trim() === '') {
        isTerbilangManual = false;
        const grandTotal = parseFloat(document.getElementById('grandTotalValue').value) || 0;
        document.getElementById('terbilangInput').value = terbilang(grandTotal);
    } else {
        isTerbilangManual = true;
    }
}

function openModal(id) { 
    document.getElementById(id).classList.add('open'); 
    if (id === 'addModal' && !document.getElementById('edit_id').value) {
        document.getElementById('modalHeaderTitle').innerText = 'Pengajuan Perangkat';
        isTerbilangManual = false;
        if (document.getElementById('deviceItemsWrapper').children.length === 0) {
            addDeviceRow('MODEM HT2010', 1, 0, 'MITRA/KANTOR', 'STOK', '-');
        }
    }
}

function closeModal(id) { 
    document.getElementById(id).classList.remove('open'); 
    if (id === 'addModal') {
        document.getElementById('edit_id').value = '';
        document.getElementById('deviceItemsWrapper').innerHTML = '';
        document.getElementById('grandTotalValue').value = 0;
        document.getElementById('grandTotalDisplay').innerText = '0';
        document.getElementById('terbilangInput').value = '';
        isTerbilangManual = false;
    }
}

function updateTipePengajuan(val) {
    const headerTitle = document.getElementById('modalHeaderTitle');
    const headerIcon = document.getElementById('modalHeaderIcon');
    if (val === 'repair') {
        headerTitle.innerText = 'Repair Perangkat';
        headerIcon.className = 'fas fa-tools';
    } else if (val === 'pembelian_rt') {
        headerTitle.innerText = 'Pengajuan Pembelian Rumah Tangga';
        headerIcon.className = 'fas fa-home';
    } else {
        headerTitle.innerText = 'Pengajuan Perangkat';
        headerIcon.className = 'fas fa-desktop';
    }
}

function addDeviceRow(perangkat = '', qty = 1, hargaSatuan = 0, layanan = 'MITRA/KANTOR', peruntukan = 'STOK', keterangan = '-') {
    const wrapper = document.getElementById('deviceItemsWrapper');
    const idx = itemIndex++;

    const rowDiv = document.createElement('div');
    rowDiv.className = 'device-item-row';
    rowDiv.style.cssText = 'border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 12px; background: #ffffff;';
    
    rowDiv.innerHTML = `
        <div style="display: grid; grid-template-columns: 2fr 0.8fr 1.3fr 1.3fr auto; gap: 10px; align-items: start; margin-bottom: 10px;">
            <div>
                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Perangkat</label>
                <input type="text" name="items[${idx}][perangkat]" value="${perangkat}" placeholder="Contoh: MODEM HT2010" style="width:100%; padding:6px 10px; border:1px solid #cbd5e1; border-radius:6px; font-size:12.5px;" required>
            </div>
            <div>
                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Qty</label>
                <input type="number" name="items[${idx}][qty]" value="${qty}" min="1" oninput="recalculateRow(${idx})" style="width:100%; padding:6px 10px; border:1px solid #cbd5e1; border-radius:6px; font-size:12.5px;" required>
            </div>
            <div>
                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Harga Satuan</label>
                <div style="display: flex; align-items: center; border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden; background: #fff;">
                    <span style="background: #f1f5f9; padding: 6px 8px; font-size: 12px; font-weight: 600; color: #475569; border-right: 1px solid #cbd5e1;">Rp</span>
                    <input type="number" name="items[${idx}][harga_satuan]" id="harga_${idx}" value="${hargaSatuan}" min="0" oninput="recalculateRow(${idx})" style="border: none; width:100%; padding:6px; font-size:12.5px;" required>
                </div>
            </div>
            <div>
                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Total</label>
                <div style="display: flex; align-items: center; border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden; background: #f8fafc;">
                    <span style="background: #f1f5f9; padding: 6px 8px; font-size: 12px; font-weight: 600; color: #475569; border-right: 1px solid #cbd5e1;">Rp</span>
                    <input type="text" id="total_display_${idx}" readonly value="${(qty * hargaSatuan).toLocaleString('id-ID')}" style="border: none; width:100%; padding:6px; background: transparent; font-weight: 600; font-size:12.5px;">
                    <input type="hidden" name="items[${idx}][total]" id="total_${idx}" value="${qty * hargaSatuan}">
                </div>
            </div>
            <div style="padding-top: 18px;">
                <button type="button" style="background: #fff; border: 1px solid #fca5a5; color: #ef4444; width: 32px; height: 32px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center;" onclick="removeDeviceRow(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1.5fr; gap: 10px;">
            <div>
                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Mitra / Kantor</label>
                <input type="text" name="items[${idx}][layanan]" value="${layanan}" placeholder="Contoh: POLDA NTB, BNN NTB" style="width:100%; padding:6px 10px; border:1px solid #cbd5e1; border-radius:6px; font-size:12.5px;">
            </div>
            <div>
                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Peruntukan</label>
                <input type="text" name="items[${idx}][peruntukan]" value="${peruntukan}" placeholder="STOK" style="width:100%; padding:6px 10px; border:1px solid #cbd5e1; border-radius:6px; font-size:12.5px;">
            </div>
            <div>
                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Keterangan Item</label>
                <input type="text" name="items[${idx}][keterangan]" value="${keterangan}" placeholder="-" style="width:100%; padding:6px 10px; border:1px solid #cbd5e1; border-radius:6px; font-size:12.5px;">
            </div>
        </div>
    `;

    wrapper.appendChild(rowDiv);
    recalculateGrandTotal();
}

function removeDeviceRow(btn) {
    const row = btn.closest('.device-item-row');
    if (row) {
        row.remove();
        recalculateGrandTotal();
    }
}

function recalculateRow(idx) {
    const qtyInput = document.querySelector(`input[name="items[${idx}][qty]"]`);
    const hargaInput = document.querySelector(`input[name="items[${idx}][harga_satuan]"]`);
    const totalInput = document.getElementById(`total_${idx}`);
    const totalDisplay = document.getElementById(`total_display_${idx}`);

    const qty = parseFloat(qtyInput ? qtyInput.value : 0) || 0;
    const harga = parseFloat(hargaInput ? hargaInput.value : 0) || 0;
    const total = qty * harga;

    if (totalInput) totalInput.value = total;
    if (totalDisplay) totalDisplay.value = total.toLocaleString('id-ID');

    recalculateGrandTotal();
}

function recalculateGrandTotal() {
    let grandTotal = 0;
    document.querySelectorAll('input[name$="[total]"]').forEach(inp => {
        grandTotal += parseFloat(inp.value) || 0;
    });

    document.getElementById('grandTotalValue').value = grandTotal;
    document.getElementById('grandTotalDisplay').innerText = grandTotal.toLocaleString('id-ID');
    
    if (!isTerbilangManual) {
        document.getElementById('terbilangInput').value = terbilang(grandTotal);
    }
}

function terbilang(angka) {
    angka = Math.abs(parseInt(angka)) || 0;
    if (angka === 0) return "";
    const bil = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
    let hasil = "";
    if (angka < 12) hasil = bil[angka];
    else if (angka < 20) hasil = terbilang(angka - 10).replace(" Rupiah", "") + " Belas";
    else if (angka < 100) hasil = terbilang(Math.floor(angka / 10)).replace(" Rupiah", "") + " Puluh " + bil[angka % 10];
    else if (angka < 200) hasil = "Seratus " + terbilang(angka - 100).replace(" Rupiah", "");
    else if (angka < 1000) hasil = terbilang(Math.floor(angka / 100)).replace(" Rupiah", "") + " Ratus " + terbilang(angka % 100).replace(" Rupiah", "");
    else if (angka < 2000) hasil = "Seribu " + terbilang(angka - 1000).replace(" Rupiah", "");
    else if (angka < 1000000) hasil = terbilang(Math.floor(angka / 1000)).replace(" Rupiah", "") + " Ribu " + terbilang(angka % 1000).replace(" Rupiah", "");
    else if (angka < 1000000000) hasil = terbilang(Math.floor(angka / 1000000)).replace(" Rupiah", "") + " Juta " + terbilang(angka % 1000000).replace(" Rupiah", "");
    else if (angka < 1000000000000) hasil = terbilang(Math.floor(angka / 1000000000)).replace(" Rupiah", "") + " Milyar " + terbilang(angka % 1000000000).replace(" Rupiah", "");
    return hasil.trim() + " Rupiah";
}

function showPaymentInfo(item) {
    const details = item.details || {};
    const total = details.grand_total ? 'Rp ' + Number(details.grand_total).toLocaleString('id-ID') : 'Rp 0';
    const html = `
        <div style="background:#f8fafc; border-radius:8px; padding:12px; font-size:13px;">
            <div style="display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px dashed #cbd5e1;">
                <span style="color:#64748b;">Status Pembayaran:</span>
                <strong style="color:#10b981;">LUNAS</strong>
            </div>
            <div style="display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px dashed #cbd5e1;">
                <span style="color:#64748b;">Total Nominal:</span>
                <strong>${total}</strong>
            </div>
            <div style="display:flex; justify-content:space-between; padding:6px 0;">
                <span style="color:#64748b;">Divisi / Pengusul:</span>
                <strong>${details.divisi || 'Manage Service AI BAKTI'}</strong>
            </div>
        </div>
    `;
    document.getElementById('paymentInfoContent').innerHTML = html;
    openModal('paymentInfoModal');
}

function viewDetailModal(item) {
    const details = item.details || {};
    const items = details.items || [];
    let itemsHtml = '';
    
    if (items.length > 0) {
        items.forEach((it, i) => {
            itemsHtml += `
                <tr>
                    <td style="padding:6px; border:1px solid #cbd5e1; text-align:center;">${i+1}</td>
                    <td style="padding:6px; border:1px solid #cbd5e1;"><strong>${it.perangkat || '-'}</strong></td>
                    <td style="padding:6px; border:1px solid #cbd5e1; text-align:center;">${it.qty || 1}</td>
                    <td style="padding:6px; border:1px solid #cbd5e1; text-align:right;">Rp ${Number(it.harga_satuan || 0).toLocaleString('id-ID')}</td>
                    <td style="padding:6px; border:1px solid #cbd5e1; text-align:right;">Rp ${Number(it.total || 0).toLocaleString('id-ID')}</td>
                    <td style="padding:6px; border:1px solid #cbd5e1; text-align:center;">${it.layanan || 'MITRA/KANTOR'}</td>
                    <td style="padding:6px; border:1px solid #cbd5e1; text-align:center;">${it.peruntukan || 'STOK'}</td>
                    <td style="padding:6px; border:1px solid #cbd5e1; text-align:center;">${it.keterangan || '-'}</td>
                </tr>
            `;
        });
    } else {
        itemsHtml = `<tr><td colspan="8" style="text-align:center; padding:10px;">${item.nama_perangkat}</td></tr>`;
    }

    const html = `
        <div style="font-size:13px; color:#334155;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px; background:#f8fafc; padding:10px; border-radius:6px;">
                <div><strong>Tempat, Tanggal:</strong> ${details.tempat || 'Mataram'}, ${details.tanggal || '-'}</div>
                <div><strong>Divisi:</strong> ${details.divisi || '-'}</div>
                <div><strong>No. Surat:</strong> ${details.no_pengajuan || '-'}</div>
                <div><strong>Tipe Pengajuan:</strong> ${details.tipe_pengajuan || 'Repair Perangkat'}</div>
            </div>

            <div style="margin-bottom:12px; background:#fff; padding:10px; border:1px solid #e2e8f0; border-radius:6px; font-size:12px;">
                <strong>Keterangan Pengajuan:</strong><br>
                ${details.keterangan_pengajuan || 'Dengan ini saya mengajukan perangkat sparepart untuk pergantian perangkat yang rusak dengan perincian sebagai berikut :'}
            </div>

            <h5 style="margin:10px 0 6px 0; font-weight:700;">Perincian Perangkat:</h5>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; margin-bottom:12px; font-size:11px;">
                    <thead>
                        <tr style="background:#f1f5f9;">
                            <th style="padding:6px; border:1px solid #cbd5e1;">No</th>
                            <th style="padding:6px; border:1px solid #cbd5e1;">Perangkat</th>
                            <th style="padding:6px; border:1px solid #cbd5e1;">Qty</th>
                            <th style="padding:6px; border:1px solid #cbd5e1;">Harga</th>
                            <th style="padding:6px; border:1px solid #cbd5e1;">Total</th>
                            <th style="padding:6px; border:1px solid #cbd5e1;">Mitra / Kantor</th>
                            <th style="padding:6px; border:1px solid #cbd5e1;">Peruntukan</th>
                            <th style="padding:6px; border:1px solid #cbd5e1;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>${itemsHtml}</tbody>
                </table>
            </div>

            <div style="background:#f8fafc; padding:10px; border-radius:6px; margin-top:8px;">
                <div style="display:flex; justify-content:space-between; font-weight:700; font-size:13.5px; color:#10b981;">
                    <span>Grand Total:</span>
                    <span>Rp ${Number(details.grand_total || 0).toLocaleString('id-ID')}</span>
                </div>
                <div style="font-size:11.5px; color:#64748b; margin-top:4px;">
                    <strong>Terbilang:</strong> <em>${details.terbilang || '-'}</em>
                </div>
                <div style="font-size:11.5px; color:#64748b; margin-top:2px;">
                    <strong>Catatan:</strong> ${details.catatan || '-'}
                </div>
            </div>
        </div>
    `;

    document.getElementById('viewDetailContent').innerHTML = html;
    document.getElementById('printBtnDetail').href = '/engineering/pengajuan-perangkat/' + item.id + '/print';
    openModal('viewDetailModal');
}

function openEditModal(item) {
    document.getElementById('edit_id').value = item.id;
    const details = item.details || {};
    
    if (details.tipe_pengajuan) {
        document.getElementById('tipe_pengajuan_select').value = details.tipe_pengajuan;
        updateTipePengajuan(details.tipe_pengajuan);
    }
    if (details.tempat) document.getElementById('form_tempat').value = details.tempat;
    if (details.tanggal) document.getElementById('form_tanggal').value = details.tanggal;
    if (details.divisi) document.getElementById('form_divisi').value = details.divisi;
    if (details.no_pengajuan) document.getElementById('form_no_pengajuan').value = details.no_pengajuan;
    if (details.keterangan_pengajuan) document.getElementById('form_keterangan_pengajuan').value = details.keterangan_pengajuan;
    if (details.catatan) document.getElementById('catatanInput').value = details.catatan;

    const wrapper = document.getElementById('deviceItemsWrapper');
    wrapper.innerHTML = '';
    const items = details.items || [];
    if (items.length > 0) {
        items.forEach(it => {
            addDeviceRow(it.perangkat || '', it.qty || 1, it.harga_satuan || 0, it.layanan || 'MITRA/KANTOR', it.peruntukan || 'STOK', it.keterangan || '-');
        });
    } else {
        addDeviceRow(item.nama_perangkat || '', 1, 0, 'MITRA/KANTOR', 'STOK', '-');
    }

    if (details.terbilang) {
        document.getElementById('terbilangInput').value = details.terbilang;
        isTerbilangManual = true;
    }

    if (details.tertanda) {
        const t = details.tertanda;
        if (t.pemohon_nama && document.getElementById('form_pemohon_nama')) document.getElementById('form_pemohon_nama').value = t.pemohon_nama;
        if (t.pemohon_jabatan && document.getElementById('form_pemohon_jabatan')) document.getElementById('form_pemohon_jabatan').value = t.pemohon_jabatan;
    }

    openModal('addModal');
}

function openRejectModal(item) {
    document.getElementById('rejectForm').action = '/engineering/pengajuan-perangkat/' + item.id + '/reject';
    openModal('rejectModal');
}
</script>
@endsection

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanPerangkat;

class PengajuanPerangkatController extends Controller
{
    public function index(Request $req)
    {
        $user = auth()->user();
        $query = PengajuanPerangkat::with('user')->latest();

        // Filter role
        if ($user->role === 'karyawan') {
            $query->where('user_id', $user->id);
        }

        // Filter Search (No, Divisi, Perangkat)
        if ($req->filled('search')) {
            $search = $req->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_perangkat', 'like', "%{$search}%")
                  ->orWhere('alasan', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%");
            });
        }

        // Filter Klasifikasi
        if ($req->filled('klasifikasi')) {
            $klas = $req->klasifikasi;
            $query->where(function($q) use ($klas) {
                $q->where('details->tipe_pengajuan', $klas);
            });
        }

        // Filter Status Approval
        if ($req->filled('status')) {
            $query->where('status', $req->status);
        }

        $totalSparepartNeeded = $query->count();
        $data = $query->paginate(20)->withQueryString();

        return view('engineering.pengajuan_perangkat.index', compact('data', 'totalSparepartNeeded'));
    }

    public function store(Request $req)
    {
        $details = [
            'tipe_pengajuan' => $req->input('tipe_pengajuan', 'pembelian'),
            'tempat' => $req->input('tempat', 'Mataram'),
            'tanggal' => $req->input('tanggal', date('Y-m-d')),
            'divisi' => $req->input('divisi', 'Manage Service AI BAKTI'),
            'no_pengajuan' => $req->input('no_pengajuan', ''),
            'items' => $req->input('items', []),
            'grand_total' => (float)$req->input('grand_total', 0),
            'terbilang' => $req->input('terbilang', ''),
            'tertanda' => [
                'pemohon_nama' => $req->input('pemohon_nama', 'Rossie Maulana Septian, S.Kom'),
                'pemohon_jabatan' => $req->input('pemohon_jabatan', 'NOC Leader'),
                'verifikasi1_nama' => $req->input('verifikasi1_nama', 'Dimas Farid Awaludin, S.Kom'),
                'verifikasi1_jabatan' => $req->input('verifikasi1_jabatan', 'Manager'),
                'verifikasi2_nama' => $req->input('verifikasi2_nama', 'Baiq Nana Erlina, A.Md'),
                'verifikasi2_jabatan' => $req->input('verifikasi2_jabatan', 'Accounting'),
                'disetujui_nama' => $req->input('disetujui_nama', 'Galuh Zakiyatun, S.Kom'),
                'disetujui_jabatan' => $req->input('disetujui_jabatan', 'Direktur'),
                'mengetahui_nama' => $req->input('mengetahui_nama', 'Raden Yuniarta Alba, S.Kom'),
                'mengetahui_jabatan' => $req->input('mengetahui_jabatan', 'Penasihat'),
            ]
        ];

        $itemNames = [];
        $totalQty = 0;
        if (!empty($details['items']) && is_array($details['items'])) {
            foreach ($details['items'] as $it) {
                if (!empty($it['perangkat'])) $itemNames[] = $it['perangkat'];
                $totalQty += intval($it['qty'] ?? 1);
            }
        }

        $namaPerangkat = !empty($itemNames) ? implode(', ', $itemNames) : ($req->nama_perangkat ?: 'Pengajuan Perangkat');
        $jumlahStr = $totalQty > 0 ? $totalQty . ' Unit' : ($req->jumlah ?: '1 Unit');
        $alasanStr = !empty($details['no_pengajuan']) ? "No: {$details['no_pengajuan']} ({$details['divisi']})" : ($req->alasan ?: 'Pengajuan Perangkat');

        $item = PengajuanPerangkat::create([
            'user_id' => auth()->id(),
            'nama_perangkat' => $namaPerangkat,
            'jumlah' => $jumlahStr,
            'alasan' => $alasanStr,
            'details' => $details,
            'status' => 'pending_manager',
        ]);

        if ($req->input('action') === 'print') {
            return redirect()->route('engineering.pengajuan_perangkat.print', $item->id);
        }

        return back()->with('success', 'Pengajuan berhasil dibuat.');
    }

    public function approve(Request $req, PengajuanPerangkat $item)
    {
        $user = auth()->user();
        $role = $user->role;

        if ($role === 'manager' && $item->status === 'pending_manager') {
            $item->update(['status' => 'pending_accounting']);
        } elseif ($role === 'accounting' && $item->status === 'pending_accounting') {
            $item->update(['status' => 'pending_direktur']);
        } elseif ($role === 'direktur' && $item->status === 'pending_direktur') {
            $item->update(['status' => 'approved']);
        } else {
            return back()->with('error', 'Anda tidak memiliki akses untuk menyetujui tahap ini.');
        }

        return back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject(Request $req, PengajuanPerangkat $item)
    {
        $req->validate([
            'alasan_penolakan' => 'required'
        ]);

        $item->update([
            'status' => 'rejected',
            'alasan_penolakan' => $req->alasan_penolakan
        ]);

        return back()->with('success', 'Pengajuan telah ditolak.');
    }

    public function destroy(PengajuanPerangkat $item)
    {
        $item->delete();
        return back()->with('success', 'Pengajuan berhasil dihapus.');
    }

    public function print(PengajuanPerangkat $item)
    {
        return view('engineering.pengajuan_perangkat.print', compact('item'));
    }
}

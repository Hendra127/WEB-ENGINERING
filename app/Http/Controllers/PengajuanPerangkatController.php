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
            'tipe_pengajuan' => $req->input('tipe_pengajuan', 'repair'),
            'tempat' => $req->input('tempat', 'Mataram'),
            'tanggal' => $req->input('tanggal', date('Y-m-d')),
            'divisi' => $req->input('divisi', 'Manage Service AI BAKTI'),
            'no_pengajuan' => $req->input('no_pengajuan', '-'),
            'keterangan_pengajuan' => $req->input('keterangan_pengajuan', 'Dengan ini saya mengajukan perangkat sparepart untuk pergantian perangkat yang rusak dengan perincian sebagai berikut :'),
            'catatan' => $req->input('catatan', '-'),
            'items' => $req->input('items', []),
            'grand_total' => (float)$req->input('grand_total', 0),
            'terbilang' => $req->input('terbilang', 'Nol Rupiah'),
            'tertanda' => [
                'pemohon_nama' => $req->input('pemohon_nama', 'Lalu Taufik Wijaya'),
                'pemohon_jabatan' => $req->input('pemohon_jabatan', 'Engineering Leader'),
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
        $alasanStr = !empty($details['no_pengajuan']) && $details['no_pengajuan'] !== '-' ? "No: {$details['no_pengajuan']} ({$details['divisi']})" : ($req->alasan ?: 'Pengajuan Perangkat');

        if ($req->filled('id')) {
            $item = PengajuanPerangkat::find($req->id);
            if ($item) {
                $item->update([
                    'nama_perangkat' => $namaPerangkat,
                    'jumlah' => $jumlahStr,
                    'alasan' => $alasanStr,
                    'details' => $details,
                ]);
            }
        } else {
            $item = PengajuanPerangkat::create([
                'user_id' => auth()->id(),
                'nama_perangkat' => $namaPerangkat,
                'jumlah' => $jumlahStr,
                'alasan' => $alasanStr,
                'details' => $details,
                'status' => 'pending_manager',
            ]);
        }

        if ($req->input('action') === 'print') {
            return redirect()->route('engineering.pengajuan_perangkat.print', $item->id);
        }

        return back()->with('success', 'Pengajuan berhasil disimpan.');
    }

    public function approve(Request $req, PengajuanPerangkat $item)
    {
        $user = auth()->user();
        $role = $user->role;

        if (in_array($role, ['manager', 'admin']) && in_array($item->status, ['pending_manager', 'pending_leader'])) {
            $item->update(['status' => 'pending_accounting']);
        } elseif (in_array($role, ['accounting', 'admin']) && $item->status === 'pending_accounting') {
            $item->update(['status' => 'pending_direktur']);
        } elseif (in_array($role, ['direktur', 'accounting', 'admin']) && in_array($item->status, ['pending_direktur', 'pending_accounting', 'pending_penasihat'])) {
            $item->update(['status' => 'approved']);
        } else {
            return back()->with('error', 'Anda tidak memiliki akses untuk menyetujui tahap ini.');
        }

        return back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject(Request $req, PengajuanPerangkat $item)
    {
        $user = auth()->user();
        $role = $user->role;

        if ($role === 'karyawan') {
            return back()->with('error', 'Karyawan tidak memiliki hak untuk menolak pengajuan.');
        }

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

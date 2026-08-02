<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeminjamanTool;
use App\Models\AlatKantor;

class PeminjamanAlatController extends Controller
{
    public function index(Request $req)
    {
        $q = PeminjamanTool::query();
        if ($req->search) {
            $q->where(function($x) use ($req) {
                $x->where('nama_alat', 'like', "%{$req->search}%")
                  ->orWhere('nama_peminjam', 'like', "%{$req->search}%");
            });
        }
        if ($req->status) {
            $q->where('status', $req->status);
        }
        $startDate = $req->start_date;
        $endDate = $req->end_date;
        if ($startDate && $endDate && $startDate > $endDate) {
            $tmp = $startDate;
            $startDate = $endDate;
            $endDate = $tmp;
        }

        if ($startDate) {
            $q->whereDate('tgl_pinjam', '>=', $startDate);
        }
        if ($endDate) {
            $q->whereDate('tgl_pinjam', '<=', $endDate);
        }

        $data = $q->latest()->paginate(20)->withQueryString();
        
        $alatList = AlatKantor::all();
        
        $stats = [
            'dipinjam'  => PeminjamanTool::where('status', 'DIPINJAM')->count(),
            'dikembali' => PeminjamanTool::where('status', 'DIKEMBALI')->count(),
            'total'     => PeminjamanTool::count()
        ];
        
        return view('engineering.peminjaman_alat', compact('data', 'alatList', 'stats'));
    }

    public function store(Request $req)
    {
        $req->validate([
            'nama_peminjam' => 'required',
            'qty'           => 'required|integer|min:1',
            'tgl_pinjam'    => 'required|date',
            'tgl_kembali'   => 'nullable|date|after_or_equal:tgl_pinjam',
            'status'        => 'required|in:DIPINJAM,DIKEMBALI',
        ]);

        if ($req->tgl_kembali) {
            $pinjam = \Carbon\Carbon::parse($req->tgl_pinjam);
            $kembali = \Carbon\Carbon::parse($req->tgl_kembali);
            if ($pinjam->diffInDays($kembali) > 7) {
                return back()->withInput()->withErrors(['tgl_kembali' => 'Durasi peminjaman tidak boleh lebih dari 7 hari dari tanggal peminjaman.']);
            }
        }

        $data = $req->only(['alat_kantor_id', 'nama_peminjam', 'qty', 'tgl_pinjam', 'tgl_kembali', 'status', 'keterangan']);
        
        if ($req->alat_kantor_id) {
            $alat = AlatKantor::find($req->alat_kantor_id);
            $data['nama_alat'] = $alat ? $alat->nama_tool : ($req->nama_alat ?: 'Unknown');
        } else {
            $req->validate(['nama_alat' => 'required']);
            $data['nama_alat'] = $req->nama_alat;
        }

        if ($data['status'] === 'DIKEMBALI' && !$data['tgl_kembali']) {
            $data['tgl_kembali'] = date('Y-m-d');
        }

        PeminjamanTool::create($data);

        return back()->with('success', 'Peminjaman alat berhasil ditambahkan!');
    }

    public function update(Request $req, PeminjamanTool $item)
    {
        $req->validate([
            'nama_peminjam' => 'required',
            'qty'           => 'required|integer|min:1',
            'tgl_pinjam'    => 'required|date',
            'tgl_kembali'   => 'nullable|date|after_or_equal:tgl_pinjam',
            'status'        => 'required|in:DIPINJAM,DIKEMBALI',
        ]);

        if ($req->tgl_kembali) {
            $pinjam = \Carbon\Carbon::parse($req->tgl_pinjam);
            $kembali = \Carbon\Carbon::parse($req->tgl_kembali);
            if ($pinjam->diffInDays($kembali) > 7) {
                return back()->withInput()->withErrors(['tgl_kembali' => 'Durasi peminjaman tidak boleh lebih dari 7 hari dari tanggal peminjaman.']);
            }
        }

        $data = $req->only(['alat_kantor_id', 'nama_peminjam', 'qty', 'tgl_pinjam', 'tgl_kembali', 'status', 'keterangan']);
        
        if ($req->alat_kantor_id) {
            $alat = AlatKantor::find($req->alat_kantor_id);
            $data['nama_alat'] = $alat ? $alat->nama_tool : ($req->nama_alat ?: $item->nama_alat);
        } else {
            $req->validate(['nama_alat' => 'required']);
            $data['nama_alat'] = $req->nama_alat;
        }

        if ($data['status'] === 'DIKEMBALI') {
            if (!$data['tgl_kembali']) {
                $data['tgl_kembali'] = date('Y-m-d');
            }
        } else {
            $data['tgl_kembali'] = null;
        }

        $item->update($data);

        return back()->with('success', 'Peminjaman alat berhasil diupdate!');
    }

    public function destroy(PeminjamanTool $item)
    {
        $item->delete();
        return back()->with('success', 'Peminjaman alat berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanPerangkat;

class PengajuanPerangkatController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = PengajuanPerangkat::with('user')->latest();

        // If karyawan, only see their own requests
        if ($user->role === 'karyawan') {
            $query->where('user_id', $user->id);
        }

        $data = $query->paginate(20);
        return view('engineering.pengajuan_perangkat.index', compact('data'));
    }

    public function store(Request $req)
    {
        $req->validate([
            'nama_perangkat' => 'required',
            'jumlah' => 'required|string',
            'alasan' => 'required',
        ]);

        PengajuanPerangkat::create([
            'user_id' => auth()->id(),
            'nama_perangkat' => $req->nama_perangkat,
            'jumlah' => $req->jumlah,
            'alasan' => $req->alasan,
            'status' => 'pending_manager',
        ]);

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

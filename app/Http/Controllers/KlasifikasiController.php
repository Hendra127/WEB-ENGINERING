<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KlasifikasiBarang;

class KlasifikasiController extends Controller
{
    public function index(Request $req)
    {
        return redirect()->route('engineering.dashboard');
        // $q = KlasifikasiBarang::query();
        // if ($req->search) $q->where('nama_barang','like',"%{$req->search}%");
        // if ($req->status) $q->where('status',$req->status);
        // $data = $q->latest()->paginate(20)->withQueryString();
        // return view('engineering.klasifikasi', compact('data'));
    }

    public function store(Request $req)
    {
        $req->validate(['nama_barang'=>'required','qty'=>'required|integer|min:1']);
        KlasifikasiBarang::create($req->only(['tgl_masuk','tgl_keluar','nama_barang','qty','satuan','nama_penerima','lokasi','status','keterangan']));
        return back()->with('success','Log barang masuk dan keluar berhasil ditambahkan!');
    }

    public function update(Request $req, KlasifikasiBarang $item)
    {
        $req->validate(['nama_barang'=>'required','qty'=>'required|integer|min:1']);
        $item->update($req->only(['tgl_masuk','tgl_keluar','nama_barang','qty','satuan','nama_penerima','lokasi','status','keterangan']));
        return back()->with('success','Log barang masuk dan keluar berhasil diupdate!');
    }

    public function destroy(KlasifikasiBarang $item)
    {
        $item->delete();
        return back()->with('success','Log barang masuk dan keluar berhasil dihapus!');
    }
}

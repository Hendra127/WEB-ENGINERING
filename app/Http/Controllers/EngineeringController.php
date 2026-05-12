<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SparepartNeeded;
use App\Models\AlatKantor;
use App\Models\KlasifikasiBarang;

class EngineeringController extends Controller
{
    /* ===================== DASHBOARD ===================== */
    public function dashboard()
    {
        return view('engineering.dashboard', [
            'totalSparepart'   => SparepartNeeded::count(),
            'totalAlat'        => AlatKantor::count(),
            'totalKlasifikasi' => KlasifikasiBarang::count(),
            'totalPending'     => SparepartNeeded::where('status','PENDING')->count(),
            'recent'           => SparepartNeeded::latest()->take(5)->get(),
        ]);
    }

    /* ===================== SPAREPART ===================== */
    public function sparepart(Request $req)
    {
        $q = SparepartNeeded::query();
        if ($req->search) $q->where(fn($x) => $x->where('lokasi_pekerjaan','like',"%{$req->search}%")->orWhere('jenis_pekerjaan','like',"%{$req->search}%")->orWhere('teknisi','like',"%{$req->search}%")->orWhere('type','like',"%{$req->search}%"));
        if ($req->status) $q->where('status',$req->status);
        $data = $q->latest()->paginate(15)->withQueryString();
        
        $counts = [
            'total' => SparepartNeeded::count(),
            'DONE' => SparepartNeeded::where('status','DONE')->count(),
            'PROSES' => SparepartNeeded::where('status','PROSES')->count(),
            'PENDING' => SparepartNeeded::where('status','PENDING')->count(),
        ];
        
        return view('engineering.sparepart', compact('data', 'counts'));
    }

    public function sparepartStore(Request $req)
    {
        $req->validate(['lokasi_pekerjaan'=>'required','jenis_pekerjaan'=>'required','qty'=>'required|integer|min:1']);
        $data = $req->only(['lokasi_pekerjaan','ruang','jenis_pekerjaan','type','qty','satuan','teknisi','tgl_masuk','tgl_selesai','kerusakan','action','keterangan','status','pergantian_perangkat','keterangan_tambahan','harga','pengantaran_perangkat']);
        $data['total_biaya'] = ($req->qty ?? 0) * ($req->harga ?? 0);
        SparepartNeeded::create($data);
        return back()->with('success','Data sparepart berhasil ditambahkan!');
    }

    public function sparepartUpdate(Request $req, SparepartNeeded $item)
    {
        $req->validate(['lokasi_pekerjaan'=>'required','jenis_pekerjaan'=>'required','qty'=>'required|integer|min:1']);
        $data = $req->only(['lokasi_pekerjaan','ruang','jenis_pekerjaan','type','qty','satuan','teknisi','tgl_masuk','tgl_selesai','kerusakan','action','keterangan','status','pergantian_perangkat','keterangan_tambahan','harga','pengantaran_perangkat']);
        $data['total_biaya'] = ($req->qty ?? 0) * ($req->harga ?? 0);
        $item->update($data);
        return back()->with('success','Data sparepart berhasil diupdate!');
    }

    public function sparepartDestroy(SparepartNeeded $item)
    {
        $item->delete();
        return back()->with('success','Data sparepart berhasil dihapus!');
    }

    /* ===================== ALAT KANTOR ===================== */
    public function alatKantor(Request $req)
    {
        $q = AlatKantor::query();
        if ($req->search) $q->where('nama_tool','like',"%{$req->search}%");
        if ($req->kondisi) $q->where('kondisi',$req->kondisi);
        if ($req->tempat) $q->where('tempat','like',"%{$req->tempat}%");
        $data = $q->latest()->paginate(20)->withQueryString();
        $stats = ['baik'=>AlatKantor::where('kondisi','BAIK')->count(),'ringan'=>AlatKantor::where('kondisi','RUSAK RINGAN')->count(),'berat'=>AlatKantor::where('kondisi','RUSAK BERAT')->count(),'total'=>AlatKantor::count()];
        return view('engineering.alat_kantor', compact('data','stats'));
    }

    public function alatStore(Request $req)
    {
        $req->validate(['nama_tool'=>'required','qty'=>'required|integer|min:1']);
        AlatKantor::create($req->only(['nama_tool','qty','satuan','kondisi','tempat','keterangan']));
        return back()->with('success','Alat kantor berhasil ditambahkan!');
    }

    public function alatUpdate(Request $req, AlatKantor $item)
    {
        $req->validate(['nama_tool'=>'required','qty'=>'required|integer|min:1']);
        $item->update($req->only(['nama_tool','qty','satuan','kondisi','tempat','keterangan']));
        return back()->with('success','Alat kantor berhasil diupdate!');
    }

    public function alatDestroy(AlatKantor $item)
    {
        $item->delete();
        return back()->with('success','Alat kantor berhasil dihapus!');
    }

    /* ===================== KLASIFIKASI ===================== */
    public function klasifikasi(Request $req)
    {
        $q = KlasifikasiBarang::query();
        if ($req->search) $q->where('nama_barang','like',"%{$req->search}%");
        if ($req->status) $q->where('status',$req->status);
        $data = $q->latest()->paginate(20)->withQueryString();
        return view('engineering.klasifikasi', compact('data'));
    }

    public function klasifikasiStore(Request $req)
    {
        $req->validate(['nama_barang'=>'required','qty'=>'required|integer|min:1']);
        KlasifikasiBarang::create($req->only(['tgl_masuk','tgl_keluar','nama_barang','qty','satuan','nama_penerima','lokasi','status','keterangan']));
        return back()->with('success','Klasifikasi barang berhasil ditambahkan!');
    }

    public function klasifikasiUpdate(Request $req, KlasifikasiBarang $item)
    {
        $req->validate(['nama_barang'=>'required','qty'=>'required|integer|min:1']);
        $item->update($req->only(['tgl_masuk','tgl_keluar','nama_barang','qty','satuan','nama_penerima','lokasi','status','keterangan']));
        return back()->with('success','Klasifikasi barang berhasil diupdate!');
    }

    public function klasifikasiDestroy(KlasifikasiBarang $item)
    {
        $item->delete();
        return back()->with('success','Klasifikasi barang berhasil dihapus!');
    }

    /* ===================== PROFILE & SETTINGS ===================== */
    public function profile()  { return view('engineering.profile'); }
    public function settings() { return view('engineering.settings'); }
}

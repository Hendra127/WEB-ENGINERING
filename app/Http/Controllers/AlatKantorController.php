<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AlatKantor;

class AlatKantorController extends Controller
{
    public function index(Request $req)
    {
        $q = AlatKantor::query();
        if ($req->search) $q->where('nama_tool','like',"%{$req->search}%");
        if ($req->kondisi) {
            $kondisi = strtoupper(trim($req->kondisi));
            $q->whereRaw('UPPER(kondisi) = ?', [$kondisi]);
        }
        if ($req->tempat) $q->where('tempat',$req->tempat);
        $data = $q->latest()->paginate(20)->withQueryString();
        $stats = [
            'baik'   => AlatKantor::whereRaw('UPPER(kondisi) = ?', ['BAIK'])->count(),
            'ringan' => AlatKantor::whereRaw('UPPER(kondisi) = ?', ['RUSAK RINGAN'])->count(),
            'berat'  => AlatKantor::whereRaw('UPPER(kondisi) = ?', ['RUSAK BERAT'])->count(),
            'total'  => AlatKantor::count()
        ];
        $tempatList = AlatKantor::whereNotNull('tempat')->where('tempat','!=','')->distinct()->pluck('tempat');
        return view('engineering.alat_kantor', compact('data','stats','tempatList'));
    }

    public function store(Request $req)
    {
        $req->validate(['nama_tool'=>'required','qty'=>'required|integer|min:1']);
        AlatKantor::create($req->only(['nama_tool','qty','satuan','kondisi','tempat','keterangan']));
        return back()->with('success','Alat kantor berhasil ditambahkan!');
    }

    public function update(Request $req, AlatKantor $item)
    {
        $req->validate(['nama_tool'=>'required','qty'=>'required|integer|min:1']);
        $item->update($req->only(['nama_tool','qty','satuan','kondisi','tempat','keterangan']));
        return back()->with('success','Alat kantor berhasil diupdate!');
    }

    public function destroy(AlatKantor $item)
    {
        $item->delete();
        return back()->with('success','Alat kantor berhasil dihapus!');
    }
}

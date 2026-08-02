<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SparepartNeeded;
use Illuminate\Support\Facades\Storage;

class SparepartController extends Controller
{
    public function index(Request $req)
    {
        $q = SparepartNeeded::query();

        if ($req->search) {
            $search = strtolower(trim($req->search));
            $q->where(function($x) use ($search) {
                $x->whereRaw('LOWER(lokasi_pekerjaan) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(ruang) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(jenis_pekerjaan) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(type) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(teknisi) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(kerusakan) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(action) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(pergantian_perangkat) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(keterangan_tambahan) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(pengantaran_perangkat) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($req->status) {
            $q->where('status', $req->status);
        }

        if ($req->lokasi) {
            if ($req->lokasi === 'POLDA NTB (RESKRIMSUS)') {
                $q->where(function($l) {
                    $l->where('lokasi_pekerjaan', 'POLDA NTB (RESKRIMSUS)')
                      ->orWhere('lokasi_pekerjaan', 'POLDA NTB,RESKRIMSUS')
                      ->orWhere('lokasi_pekerjaan', 'POLDA NTB RESKRIMSUS');
                });
            } else {
                $q->where('lokasi_pekerjaan', $req->lokasi);
            }
        }

        if ($req->start_date) {
            $q->whereDate('tgl_masuk', '>=', $req->start_date);
        }

        if ($req->end_date) {
            $q->whereDate('tgl_masuk', '<=', $req->end_date);
        }

        $data = $q->latest()->paginate(15)->withQueryString();
        
        $counts = [
            'total' => SparepartNeeded::count(),
            'DONE' => SparepartNeeded::where('status','DONE')->count(),
            'PROSES' => SparepartNeeded::where('status','PROSES')->count(),
            'PENDING' => SparepartNeeded::where('status','PENDING')->count(),
        ];
        
        $rawLokasi = SparepartNeeded::whereNotNull('lokasi_pekerjaan')->where('lokasi_pekerjaan','!=','')->distinct()->pluck('lokasi_pekerjaan')->toArray();
        $normalizedList = [];
        foreach ($rawLokasi as $lok) {
            $trimLok = trim($lok);
            if (in_array(strtoupper($trimLok), ['POLDA NTB (RESKRIMSUS)', 'POLDA NTB,RESKRIMSUS', 'POLDA NTB RESKRIMSUS'])) {
                $normalizedList[] = 'POLDA NTB (RESKRIMSUS)';
            } else {
                $normalizedList[] = $trimLok;
            }
        }
        foreach (['POLDA NTB (RESKRIMSUS)', 'BNNP'] as $defaultLokasi) {
            $normalizedList[] = $defaultLokasi;
        }
        $lokasiList = array_values(array_unique($normalizedList));
        sort($lokasiList);

        return view('engineering.sparepart', compact('data', 'counts', 'lokasiList'));
    }

    public function store(Request $req)
    {
        $req->validate([
            'lokasi_pekerjaan'=>'required',
            'jenis_pekerjaan'=>'required',
            'qty'=>'required|integer|min:1',
            'foto_masuk' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'foto_proses' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'foto_keluar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'file_ba' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf,doc,docx|max:10240',
        ]);
        
        $data = $req->only(['lokasi_pekerjaan','ruang','jenis_pekerjaan','type','qty','satuan','teknisi','tgl_masuk','tgl_selesai','kerusakan','action','keterangan','status','pergantian_perangkat','keterangan_tambahan','harga','pengantaran_perangkat']);
        $data['total_biaya'] = ($req->qty ?? 0) * ($req->harga ?? 0);
        
        if ($req->hasFile('foto_masuk')) {
            $data['foto_masuk'] = $req->file('foto_masuk')->store('spareparts', 'public');
        }
        if ($req->hasFile('foto_proses')) {
            $data['foto_proses'] = $req->file('foto_proses')->store('spareparts', 'public');
        }
        if ($req->hasFile('foto_keluar')) {
            $data['foto_keluar'] = $req->file('foto_keluar')->store('spareparts', 'public');
        }
        if ($req->hasFile('file_ba')) {
            $data['file_ba'] = $req->file('file_ba')->store('spareparts', 'public');
        }

        SparepartNeeded::create($data);
        return back()->with('success','Data sparepart berhasil ditambahkan!');
    }

    public function update(Request $req, SparepartNeeded $item)
    {
        $req->validate([
            'lokasi_pekerjaan'=>'required',
            'jenis_pekerjaan'=>'required',
            'qty'=>'required|integer|min:1',
            'foto_masuk' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'foto_proses' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'foto_keluar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'file_ba' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf,doc,docx|max:10240',
        ]);

        $data = $req->only(['lokasi_pekerjaan','ruang','jenis_pekerjaan','type','qty','satuan','teknisi','tgl_masuk','tgl_selesai','kerusakan','action','keterangan','status','pergantian_perangkat','keterangan_tambahan','harga','pengantaran_perangkat']);
        $data['total_biaya'] = ($req->qty ?? 0) * ($req->harga ?? 0);
        
        if ($req->hasFile('foto_masuk')) {
            if ($item->foto_masuk) {
                Storage::disk('public')->delete($item->foto_masuk);
            }
            $data['foto_masuk'] = $req->file('foto_masuk')->store('spareparts', 'public');
        }
        if ($req->hasFile('foto_proses')) {
            if ($item->foto_proses) {
                Storage::disk('public')->delete($item->foto_proses);
            }
            $data['foto_proses'] = $req->file('foto_proses')->store('spareparts', 'public');
        }
        if ($req->hasFile('foto_keluar')) {
            if ($item->foto_keluar) {
                Storage::disk('public')->delete($item->foto_keluar);
            }
            $data['foto_keluar'] = $req->file('foto_keluar')->store('spareparts', 'public');
        }
        if ($req->hasFile('file_ba')) {
            if ($item->file_ba) {
                Storage::disk('public')->delete($item->file_ba);
            }
            $data['file_ba'] = $req->file('file_ba')->store('spareparts', 'public');
        }

        $item->update($data);
        return back()->with('success','Data sparepart berhasil diupdate!');
    }

    public function destroy(SparepartNeeded $item)
    {
        if ($item->foto_masuk) {
            Storage::disk('public')->delete($item->foto_masuk);
        }
        if ($item->foto_proses) {
            Storage::disk('public')->delete($item->foto_proses);
        }
        if ($item->foto_keluar) {
            Storage::disk('public')->delete($item->foto_keluar);
        }
        if ($item->file_ba) {
            Storage::disk('public')->delete($item->file_ba);
        }

        $item->delete();
        return back()->with('success','Data sparepart berhasil dihapus!');
    }

    public function printBA(SparepartNeeded $item)
    {
        return view('engineering.sparepart_print_ba', compact('item'));
    }
}

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SparepartNeeded;
use App\Models\AlatKantor;
use App\Models\KlasifikasiBarang;
use App\Models\PeminjamanTool;

class EngineeringController extends Controller
{
    /* ===================== DASHBOARD ===================== */
    public function dashboard()
    {
        return view('engineering.dashboard', [
            'totalSparepart'   => SparepartNeeded::count(),
            'totalAlat'        => AlatKantor::count(),
            'totalKlasifikasi' => KlasifikasiBarang::count(),
            'totalPeminjaman'  => PeminjamanTool::where('status','DIPINJAM')->count(),
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

    public function sparepartUpdate(Request $req, SparepartNeeded $item)
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
                \Illuminate\Support\Facades\Storage::disk('public')->delete($item->foto_masuk);
            }
            $data['foto_masuk'] = $req->file('foto_masuk')->store('spareparts', 'public');
        }
        if ($req->hasFile('foto_proses')) {
            if ($item->foto_proses) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($item->foto_proses);
            }
            $data['foto_proses'] = $req->file('foto_proses')->store('spareparts', 'public');
        }
        if ($req->hasFile('foto_keluar')) {
            if ($item->foto_keluar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($item->foto_keluar);
            }
            $data['foto_keluar'] = $req->file('foto_keluar')->store('spareparts', 'public');
        }
        if ($req->hasFile('file_ba')) {
            if ($item->file_ba) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($item->file_ba);
            }
            $data['file_ba'] = $req->file('file_ba')->store('spareparts', 'public');
        }

        $item->update($data);
        return back()->with('success','Data sparepart berhasil diupdate!');
    }

    public function sparepartDestroy(SparepartNeeded $item)
    {
        if ($item->foto_masuk) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($item->foto_masuk);
        }
        if ($item->foto_proses) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($item->foto_proses);
        }
        if ($item->foto_keluar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($item->foto_keluar);
        }
        if ($item->file_ba) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($item->file_ba);
        }

        $item->delete();
        return back()->with('success','Data sparepart berhasil dihapus!');
    }

    public function printBA(SparepartNeeded $item)
    {
        return view('engineering.sparepart_print_ba', compact('item'));
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
        return back()->with('success','Log barang masuk dan keluar berhasil ditambahkan!');
    }

    public function klasifikasiUpdate(Request $req, KlasifikasiBarang $item)
    {
        $req->validate(['nama_barang'=>'required','qty'=>'required|integer|min:1']);
        $item->update($req->only(['tgl_masuk','tgl_keluar','nama_barang','qty','satuan','nama_penerima','lokasi','status','keterangan']));
        return back()->with('success','Log barang masuk dan keluar berhasil diupdate!');
    }

    public function klasifikasiDestroy(KlasifikasiBarang $item)
    {
        $item->delete();
        return back()->with('success','Log barang masuk dan keluar berhasil dihapus!');
    }


    /* ===================== PEMINJAMAN ALAT ===================== */
    public function peminjamanAlat(Request $req)
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
        $data = $q->latest()->paginate(20)->withQueryString();
        
        $alatList = AlatKantor::all();
        
        $stats = [
            'dipinjam' => PeminjamanTool::where('status', 'DIPINJAM')->count(),
            'dikembali' => PeminjamanTool::where('status', 'DIKEMBALI')->count(),
            'total' => PeminjamanTool::count()
        ];
        
        return view('engineering.peminjaman_alat', compact('data', 'alatList', 'stats'));
    }

    public function peminjamanStore(Request $req)
    {
        $req->validate([
            'nama_peminjam' => 'required',
            'qty' => 'required|integer|min:1',
            'tgl_pinjam' => 'required|date',
            'status' => 'required|in:DIPINJAM,DIKEMBALI',
        ]);

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

    public function peminjamanUpdate(Request $req, PeminjamanTool $item)
    {
        $req->validate([
            'nama_peminjam' => 'required',
            'qty' => 'required|integer|min:1',
            'tgl_pinjam' => 'required|date',
            'status' => 'required|in:DIPINJAM,DIKEMBALI',
        ]);

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

    public function peminjamanDestroy(PeminjamanTool $item)
    {
        $item->delete();
        return back()->with('success', 'Peminjaman alat berhasil dihapus!');
    }


    /* ===================== PROFILE & SETTINGS ===================== */
    public function profile()  { return view('engineering.profile'); }
    public function settings() { return view('engineering.settings'); }

    public function profileUpdate(Request $req)
    {
        $user = auth()->user();
        $req->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($req->only(['name', 'email', 'phone']));

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function passwordUpdate(Request $req)
    {
        $user = auth()->user();
        $req->validate([
            'current_password' => 'required|current_password',
            'password'         => 'required|min:8|confirmed',
        ]);

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($req->password),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}

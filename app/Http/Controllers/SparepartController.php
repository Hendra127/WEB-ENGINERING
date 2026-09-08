<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SparepartNeeded;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
            'foto_masuk.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'foto_proses.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'foto_keluar.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'file_ba.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf,doc,docx|max:10240',
        ]);
        
        $data = $req->only(['lokasi_pekerjaan','ruang','jenis_pekerjaan','type','qty','satuan','teknisi','tgl_masuk','tgl_selesai','kerusakan','action','keterangan','status','pergantian_perangkat','keterangan_tambahan','harga','pengantaran_perangkat']);
        $data['harga'] = ($req->filled('harga') && (float)$req->harga > 0) ? (float)$req->harga : null;
        $data['total_biaya'] = ($req->qty ?? 0) * ($data['harga'] ?? 0);
        
        foreach (['foto_masuk', 'foto_proses', 'foto_keluar', 'file_ba'] as $field) {
            if ($req->hasFile($field)) {
                $files = $req->file($field);
                if (!is_array($files)) {
                    $files = [$files];
                }
                $paths = [];
                foreach ($files as $f) {
                    if ($f->isValid()) {
                        $paths[] = $f->store('spareparts', 'public');
                    }
                }
                if (!empty($paths)) {
                    $data[$field] = json_encode($paths);
                }
            }
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
            'foto_masuk.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'foto_proses.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'foto_keluar.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'file_ba.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf,doc,docx|max:10240',
        ]);

        $data = $req->only(['lokasi_pekerjaan','ruang','jenis_pekerjaan','type','qty','satuan','teknisi','tgl_masuk','tgl_selesai','kerusakan','action','keterangan','status','pergantian_perangkat','keterangan_tambahan','harga','pengantaran_perangkat']);
        $data['harga'] = ($req->filled('harga') && (float)$req->harga > 0) ? (float)$req->harga : null;
        $data['total_biaya'] = ($req->qty ?? 0) * ($data['harga'] ?? 0);
        
        foreach (['foto_masuk', 'foto_proses', 'foto_keluar', 'file_ba'] as $field) {
            $existingPaths = $req->input('existing_' . $field, []);
            if (!is_array($existingPaths)) {
                $existingPaths = [];
            }

            // Remove deleted files from disk
            $currentStored = $item->$field;
            if ($currentStored) {
                $oldList = is_array(json_decode($currentStored, true)) ? json_decode($currentStored, true) : [$currentStored];
                foreach ($oldList as $oldFile) {
                    if (!in_array($oldFile, $existingPaths)) {
                        Storage::disk('public')->delete($oldFile);
                    }
                }
            }

            // Upload new files
            if ($req->hasFile($field)) {
                $files = $req->file($field);
                if (!is_array($files)) {
                    $files = [$files];
                }
                foreach ($files as $f) {
                    if ($f->isValid()) {
                        $existingPaths[] = $f->store('spareparts', 'public');
                    }
                }
            }

            $data[$field] = !empty($existingPaths) ? json_encode(array_values($existingPaths)) : null;
        }

        $item->update($data);
        return back()->with('success','Data sparepart berhasil diupdate!');
    }

    public function destroy(SparepartNeeded $item)
    {
        foreach (['foto_masuk', 'foto_proses', 'foto_keluar', 'file_ba'] as $field) {
            if ($item->$field) {
                $oldPaths = is_array(json_decode($item->$field, true)) ? json_decode($item->$field, true) : [$item->$field];
                foreach ($oldPaths as $op) {
                    Storage::disk('public')->delete($op);
                }
            }
        }
        $item->delete();
        return back()->with('success','Data sparepart berhasil dihapus!');
    }

    public function printBA(SparepartNeeded $item)
    {
        return view('engineering.sparepart_print_ba', compact('item'));
    }

    public function import(Request $req)
    {
        $req->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $file = $req->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Remove header row
            array_shift($rows);

            $imported = 0;
            foreach ($rows as $row) {
                // Check if row is empty (first column is empty)
                if (empty(array_filter($row))) {
                    continue;
                }

                // Parse teknisi (assuming comma separated)
                $teknisiStr = $row[6] ?? '';
                $teknisi = array_map('trim', explode(',', $teknisiStr));
                
                // Parse dates
                $tgl_masuk = null;
                $tgl_selesai = null;
                
                if (!empty($row[7])) {
                    if (is_numeric($row[7])) {
                        $tgl_masuk = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[7])->format('Y-m-d');
                    } else {
                        $tgl_masuk = date('Y-m-d', strtotime(str_replace('/', '-', $row[7])));
                    }
                }
                
                if (!empty($row[8])) {
                    if (is_numeric($row[8])) {
                        $tgl_selesai = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[8])->format('Y-m-d');
                    } else {
                        $tgl_selesai = date('Y-m-d', strtotime(str_replace('/', '-', $row[8])));
                    }
                }

                $qty = (int)($row[4] ?? 1);
                $harga = (float)($row[14] ?? 0);

                SparepartNeeded::create([
                    'lokasi_pekerjaan' => $row[0] ?? '-',
                    'ruang' => $row[1] ?? '',
                    'jenis_pekerjaan' => $row[2] ?? 'LAINNYA',
                    'type' => $row[3] ?? '',
                    'qty' => $qty,
                    'satuan' => $row[5] ?? 'Unit',
                    'teknisi' => $teknisi,
                    'tgl_masuk' => $tgl_masuk,
                    'tgl_selesai' => $tgl_selesai,
                    'kerusakan' => $row[9] ?? '',
                    'action' => $row[10] ?? '',
                    'pergantian_perangkat' => $row[11] ?? '',
                    'keterangan_tambahan' => $row[12] ?? '',
                    'keterangan' => $row[13] ?? '',
                    'harga' => $harga,
                    'total_biaya' => $qty * $harga,
                    'pengantaran_perangkat' => $row[15] ?? '',
                    'status' => strtoupper($row[16] ?? 'PENDING')
                ]);
                $imported++;
            }

            return back()->with('success', "Berhasil mengimport $imported data!");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Headers
        $headers = [
            'Lokasi Pekerjaan', 'Ruang', 'Jenis Pekerjaan', 'Type/Merk', 'Qty', 
            'Satuan', 'Teknisi (Pisahkan dengan koma)', 'Tgl Mulai (YYYY-MM-DD)', 
            'Tgl Selesai (YYYY-MM-DD)', 'Kerusakan', 'Action', 'Pergantian Perangkat', 
            'Keterangan Tambahan', 'Catatan Lainnya', 'Harga Barang', 
            'Pengantaran Perangkat', 'Status (DONE/PROSES/PENDING)'
        ];
        
        $sheet->fromArray([$headers], NULL, 'A1');
        
        // Auto size columns
        foreach (range('A', 'Q') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Sample data
        $sample = [
            'POLDA NTB (RESKRIMSUS)', 'RUMDIN', 'AC', 'DAIKIN 1 PK', 1, 
            'Unit', 'MISDAN, ANDRI PRATAMA', '2026-08-01', 
            '2026-08-02', 'Tidak dingin', 'Cleaning dan tambah freon', 'Freon R32', 
            '', '', 150000, 
            '', 'DONE'
        ];
        $sheet->fromArray([$sample], NULL, 'A2');

        $writer = new Xlsx($spreadsheet);
        
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, 'Template_Import_Sparepart.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}

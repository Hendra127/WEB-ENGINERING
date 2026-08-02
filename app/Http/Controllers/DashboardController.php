<?php

namespace App\Http\Controllers;

use App\Models\SparepartNeeded;
use App\Models\AlatKantor;
use App\Models\KlasifikasiBarang;
use App\Models\PeminjamanTool;

class DashboardController extends Controller
{
    public function index()
    {
        $recentPeminjaman = PeminjamanTool::with('alatKantor')
            ->where('status', 'DIPINJAM')
            ->latest()
            ->take(5)
            ->get();

        return view('engineering.dashboard', [
            'totalSparepart'   => SparepartNeeded::count(),
            'totalAlat'        => AlatKantor::count(),
            'totalKlasifikasi' => KlasifikasiBarang::count(),
            'totalPeminjaman'  => PeminjamanTool::where('status','DIPINJAM')->count(),
            'totalPending'     => SparepartNeeded::where('status','PENDING')->count(),
            'recent'           => SparepartNeeded::latest()->take(5)->get(),
            'recentPeminjaman' => $recentPeminjaman,
        ]);
    }
}

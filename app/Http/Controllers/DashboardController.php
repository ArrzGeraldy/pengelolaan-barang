<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Pinjaman;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Barang::count();

        $barangDipinjam = Pinjaman::where('status', 'Dipinjam')->sum('jumlah_dipinjam');

        $stokHabis = Barang::where('stock_tersedia', 0)->count();

        $transaksiSelesai = Pinjaman::where('status', 'Selesai')->count();

        $pinjamans = Pinjaman::with('barang')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBarang',
            'barangDipinjam',
            'stokHabis',
            'transaksiSelesai',
            'pinjamans'
        ));
    }
}

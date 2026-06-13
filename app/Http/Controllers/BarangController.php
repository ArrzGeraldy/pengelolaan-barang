<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Jurusan;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with(['kategori', 'jurusan'])->latest();

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('kategori_id') && $request->kategori_id != '') {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->has('jurusan_id') && $request->jurusan_id != '') {
            $query->where('jurusan_id', $request->jurusan_id);
        }

        $barangs = $query->paginate(10)->withQueryString();

        $kategoris = Kategori::all();
        $jurusans = Jurusan::all();

        return view('admin.barang.index', compact('barangs', 'kategoris', 'jurusans'));
    }

    public function create()
    {
        $jurusans = Jurusan::orderBy('nama')->get();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('admin.barang.create', compact('jurusans', 'kategoris'));
    }

    public function store(Request $request)
    {
        // 1. Hapus 'kode' dari validasi karena kodenya di-generate oleh sistem
        $request->validate([
            'jurusan_id' => 'required|exists:jurusans,id',
            'kategori_id' => 'required|exists:kategoris,id',
            'nama' => 'required|string|max:255',
            'stok_total' => 'required|integer|min:0',
            'stock_tersedia' => 'required|integer|min:0',
        ]);

        try {
            // 2. LOGIKA GENERATE KODE OTOMATIS
            // Format target: BRG-YYYYMM-0001 (Contoh: BRG-202606-0001)
            $prefix = 'BRG-' . Carbon::now()->format('Ym') . '-';
            
            // Cari kode terakhir di database yang awalannya mirip dengan prefix bulan ini
            $lastBarang = Barang::where('kode', 'like', $prefix . '%')
                ->orderBy('kode', 'desc')
                ->first();

            if ($lastBarang) {
                // Mengambil 4 angka terakhir dari kode terakhir (misal '0001' -> diubah ke integer 1)
                $lastSerialNumber = intval(substr($lastBarang->kode, -4));
                $nextSerialNumber = $lastSerialNumber + 1;
            } else {
                // Jika belum ada barang yang terdaftar di bulan ini, mulai dari 1
                $nextSerialNumber = 1;
            }

            // Gabungkan prefix dengan nomor urut yang dipadding dengan angka 0 di depan (panjang 4 digit)
            // Hasilnya: BRG-202606-0001
            $generatedKode = $prefix . str_pad($nextSerialNumber, 4, '0', STR_PAD_LEFT);

            // 3. Simpan data ke Database
            Barang::create([
                'jurusan_id' => $request->jurusan_id,
                'kategori_id' => $request->kategori_id,
                'kode' => $generatedKode, // Menggunakan kode yang di-generate otomatis
                'nama' => $request->nama,
                'stok_total' => $request->stok_total,
                'stock_tersedia' => $request->stock_tersedia,
            ]);

            return redirect()->route('admin.barang.index')->with('success', "Barang berhasil ditambahkan dengan kode {$generatedKode}!");
        } catch (\Exception $e) {
            Log::error('Error Barang Store: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data barang.');
        }
    }
    public function edit(Barang $barang)
    {
        $jurusans = Jurusan::orderBy('nama')->get();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('admin.barang.edit', compact('barang', 'jurusans', 'kategoris'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'jurusan_id' => 'required|exists:jurusans,id',
            'kategori_id' => 'required|exists:kategoris,id',
            'nama' => 'required|string|max:255',
            'stok_total' => 'required|integer|min:0',
            'stock_tersedia' => 'required|integer|min:0',
        ]);

        try {
            $barang->update([
                'jurusan_id' => $request->jurusan_id,
                'kategori_id' => $request->kategori_id,
                'nama' => $request->nama,
                'stok_total' => $request->stok_total,
                'stock_tersedia' => $request->stock_tersedia,
            ]);

            return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error Barang Update: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data barang.');
        }
    }

    public function destroy(Barang $barang)
    {
        try {
            $barang->delete();

            return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error Barang Delete: ' . $e->getMessage());

            return redirect()->route('admin.barang.index')->with('error', 'Terjadi kesalahan saat menghapus barang.');
        }
    }
}

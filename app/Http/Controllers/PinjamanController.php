<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PinjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pinjaman::with(['barang', 'user'])->latest();

        if ($request->has('search') && $request->search != '') {
            $query->where('peminjam', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $pinjamans = $query->paginate(15)->withQueryString();

        return view('admin.pinjaman.index', compact('pinjamans'));
    }

    public function pending(Request $request)
    {
        $query = Pinjaman::with(['barang', 'user'])
            ->where('status', 'Pending')
            ->latest();

        if ($request->has('search') && $request->search != '') {
            $query->where('peminjam', 'like', '%' . $request->search . '%');
        }

        $pinjamans = $query->paginate(15)->withQueryString();

        return view('admin.pinjaman.pending', compact('pinjamans'));
    }

    public function create()
    {
        $barangs = Barang::where('stock_tersedia', '>', 0)->get();
        return view('admin.pinjaman.create', compact('barangs'));
    }

    // 3. Logika Memproses Peminjaman Baru (Store)
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'peminjam' => 'required|string|max:250',
            'jumlah_dipinjam' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_dikembalikan' => 'required|date|after_or_equal:tanggal_pinjam',
        ], [
            'tanggal_dikembalikan.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam!'
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        if ($barang->stock_tersedia < $request->jumlah_dipinjam) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Stok tidak mencukupi! Sisa stok '{$barang->nama}' saat ini adalah {$barang->stock_tersedia}.");
        }

        // Gunakan Database Transaction agar aman jika salah satu proses gagal
        DB::transaction(function () use ($request, $barang) {
            // Generasi otomatis kode transaksi secara simpel (Contoh: TRX-20260605-001)
            $stringRandom = strtoupper(bin2hex(random_bytes(3)));
            $kodePeminjaman = 'TRX-' . date('Ymd') . '-' . $stringRandom;

            // 1. Simpan data ke tabel pinjamen
            Pinjaman::create([
                'barang_id' => $request->barang_id,
                'kode_peminjaman' => $kodePeminjaman,
                'peminjam' => $request->peminjam,
                'jumlah_dipinjam' => $request->jumlah_dipinjam,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_dikembalikan' => $request->tanggal_dikembalikan,
                'status' => 'Dipinjam'
            ]);

            // 2. POTONG STOK TERSEDIA di tabel barangs
            $barang->decrement('stock_tersedia', $request->jumlah_dipinjam);
        });

        return redirect()->route('admin.pinjaman.index')->with('success', 'Transaksi peminjaman berhasil dicatat!');
    }

    public function approve(Request $request, $id)
    {
        $pinjaman = Pinjaman::findOrFail($id);

        if ($pinjaman->status !== 'Pending') {
            return redirect()->back()->with('error', 'Permintaan ini tidak berada pada status Pending.');
        }

        $barang = Barang::findOrFail($pinjaman->barang_id);

        if ($barang->stock_tersedia < $pinjaman->jumlah_dipinjam) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi untuk menyetujui permintaan ini.');
        }

        DB::transaction(function () use ($pinjaman, $barang) {
            $pinjaman->update([
                'status' => 'Dipinjam',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            $barang->decrement('stock_tersedia', $pinjaman->jumlah_dipinjam);
        });

        return redirect()->back()->with('success', 'Permintaan pinjaman berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $pinjaman = Pinjaman::findOrFail($id);

        if ($pinjaman->status !== 'Pending') {
            return redirect()->back()->with('error', 'Permintaan ini tidak berada pada status Pending.');
        }

        $pinjaman->update([
            'status' => 'Ditolak',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Permintaan pinjaman telah ditolak.');
    }

    // 4. Logika Mengembalikan Barang (Update Status ke Selesai)
    public function kembalikan(Request $request, $id)
    {
        $pinjaman = Pinjaman::findOrFail($id);

        // Jika statusnya memang masih dipinjam / terlambat, baru bisa dikembalikan
        if ($pinjaman->status === 'Selesai') {
            return redirect()->back()->with('error', 'Barang pada transaksi ini sudah dikembalikan sebelumnya.');
        }

        DB::transaction(function () use ($pinjaman) {
            // 1. Ubah status transaksi menjadi Selesai
            $pinjaman->update([
                'status' => 'Selesai'
            ]);

            // 2. KEMBALIKAN STOK TERSEDIA ke tabel barangs
            $barang = Barang::findOrFail($pinjaman->barang_id);
            $barang->increment('stock_tersedia', $pinjaman->jumlah_dipinjam);
        });

        return redirect()->route('admin.pinjaman.index')->with('success', 'Barang berhasil dikembalikan. Stok terupdate!');
    }

    public function edit($id)
    {
        $pinjaman = Pinjaman::findOrFail($id);
        
        // Jika transaksi sudah selesai, sebaiknya tidak boleh diedit lagi demi validitas data stok
        if ($pinjaman->status === 'Selesai') {
            return redirect()->route('admin.pinjaman.index')->with('error', 'Transaksi yang sudah selesai tidak dapat diubah.');
        }

        // Ambil semua barang untuk pilihan di dropdown form
        $barangs = Barang::all();
        
        return view('admin.pinjaman.edit', compact('pinjaman', 'barangs'));
    }

    // 6. Memproses Perubahan Data Peminjaman (Update)
    public function update(Request $request, $id)
    {
        $pinjaman = Pinjaman::findOrFail($id);

        if ($pinjaman->status === 'Selesai') {
            return redirect()->route('admin.pinjaman.index')->with('error', 'Transaksi yang sudah selesai tidak dapat diubah.');
        }

        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'peminjam' => 'required|string|max:250',
            'jumlah_dipinjam' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_dikembalikan' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        $barangBaru = Barang::findOrFail($request->barang_id);

        // LOGIKA PENYESUAIAN STOK YANG AMAN
        // Kita hitung total stok simulasi seolah-olah barang lama sudah dikembalikan terlebih dahulu
        if ($pinjaman->barang_id == $request->barang_id) {
            // Jika barangnya SAMA, stok tersedia sementara ditambah dengan jumlah lama sebelum dicek
            $stokSimulasi = $barangBaru->stock_tersedia + $pinjaman->jumlah_dipinjam;
        } else {
            // Jika ganti BARANG LAIN, maka langsung cek ke stok barang baru tersebut
            $stokSimulasi = $barangBaru->stock_tersedia;
        }

        // Jalankan pengecekan apakah stok simulasi cukup untuk jumlah pinjaman yang baru
        if ($stokSimulasi < $request->jumlah_dipinjam) {
            return redirect()->back()->withInput()->with('error', "Stok tidak mencukupi! Sisa stok yang tersedia untuk barang ini adalah {$stokSimulasi}.");
        }

        DB::transaction(function () use ($request, $pinjaman, $barangBaru) {
            // 1. Kembalikan dulu stok barang yang LAMA
            $barangLama = Barang::findOrFail($pinjaman->barang_id);
            $barangLama->increment('stock_tersedia', $pinjaman->jumlah_dipinjam);

            // 2. Update data transaksi peminjaman
            $pinjaman->update([
                'barang_id' => $request->barang_id,
                'peminjam' => $request->peminjam,
                'jumlah_dipinjam' => $request->jumlah_dipinjam,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_dikembalikan' => $request->tanggal_dikembalikan,
            ]);

            // 3. Potong stok barang yang BARU (merefresh data terbaru dari database)
            $barangBaru->refresh();
            $barangBaru->decrement('stock_tersedia', $request->jumlah_dipinjam);
        });

        return redirect()->route('admin.pinjaman.index')->with('success', 'Data peminjaman berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pinjaman = Pinjaman::findOrFail($id);

        DB::transaction(function () use ($pinjaman) {
            // JIKA statusnya masih 'Dipinjam', maka stok barang wajib dikembalikan dulu sebelum dihapus
            if ($pinjaman->status !== 'Selesai') {
                $barang = Barang::findOrFail($pinjaman->barang_id);
                $barang->increment('stock_tersedia', $pinjaman->jumlah_dipinjam);
            }

            // Hapus data transaksi dari database
            $pinjaman->delete();
        });

        return redirect()->route('admin.pinjaman.index')->with('success', 'Riwayat peminjaman berhasil dihapus dan stok disesuaikan!');
    }
}

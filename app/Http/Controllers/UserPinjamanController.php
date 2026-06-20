<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserPinjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pinjaman::with('barang')
            ->where('user_id', Auth::id())
            ->latest();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $pinjamans = $query->paginate(15)->withQueryString();

        return view('pinjaman.my', compact('pinjamans'));
    }

    public function create()
    {
        $barangs = Barang::where('stock_tersedia', '>', 0)->get();

        return view('pinjaman.request', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
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

        DB::transaction(function () use ($request) {
            $stringRandom = strtoupper(bin2hex(random_bytes(3)));
            $kodePeminjaman = 'REQ-' . date('Ymd') . '-' . $stringRandom;

            Pinjaman::create([
                'user_id' => Auth::id(),
                'barang_id' => $request->barang_id,
                'kode_peminjaman' => $kodePeminjaman,
                'peminjam' => Auth::user()->name,
                'jumlah_dipinjam' => $request->jumlah_dipinjam,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_dikembalikan' => $request->tanggal_dikembalikan,
                'status' => 'Pending',
            ]);
        });

        return redirect()->route('pinjaman.my')->with('success', 'Permintaan pinjaman telah diajukan. Tunggu verifikasi admin.');
    }

    public function kembalikan(Request $request, $id)
    {
        $pinjaman = Pinjaman::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if ($pinjaman->status !== 'Dipinjam') {
            return redirect()->back()->with('error', 'Hanya pinjaman yang disetujui dan sedang dipinjam yang bisa dikembalikan.');
        }

        DB::transaction(function () use ($pinjaman) {
            $pinjaman->update([
                'status' => 'Selesai',
            ]);

            $barang = Barang::findOrFail($pinjaman->barang_id);
            $barang->increment('stock_tersedia', $pinjaman->jumlah_dipinjam);
        });

        return redirect()->route('pinjaman.my')->with('success', 'Barang berhasil dikembalikan. Terima kasih.');
    }
}

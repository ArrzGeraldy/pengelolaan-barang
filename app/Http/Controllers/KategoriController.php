<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = Kategori::latest();;
        if($request->has('search')){
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $kategoris = $query->paginate(10)->withQueryString();

        return view('admin.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:kategoris,nama',
        ]);

        Kategori::create([
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:kategoris,nama,' . $kategori->id,
        ]);

        try {
            $kategori->update([
                'nama' => $request->nama,
            ]);

            return redirect()->route('admin.kategori.index')
                ->with('success', 'Kategori berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error Kategori Update: ' . $e->getMessage());

            return redirect()->route('admin.kategori.index')
                ->with('error', 'Gagal memperbarui data kategori.');
        }
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->barangs()->exists()) {
            return redirect()->route('admin.kategori.index')->with(
                'error',
                "Gagal menghapus! Kategori '{$kategori->nama}' masih memiliki data barang inventaris."
            );
        }

        try {
            $kategori->delete();

            return redirect()->route('admin.kategori.index')
                ->with('success', 'Kategori berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Terjadi kesalahan sistem saat menghapus data.');
        }
    }
}

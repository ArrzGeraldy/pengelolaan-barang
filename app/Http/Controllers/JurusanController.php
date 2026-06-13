<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JurusanController extends Controller
{

    public function index(Request $request)
    {
        $query = Jurusan::latest();;
        if($request->has('search')){
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $jurusans = $query->paginate(10)->withQueryString();
        return view('admin.jurusan.index', compact('jurusans'));
    }
        
    
    public function create()
    {
        return view('admin.jurusan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:jurusans,nama', 
        ]);

        Jurusan::create([
            'nama' => $request->nama
        ]);

        return redirect()->route('admin.jurusan.index')->with('success', 'Jurusan berhasil ditambahkan!');
    }

    public function edit(Jurusan $jurusan)
    {
        return view('admin.jurusan.edit', compact('jurusan'));
    }

    public function update(Request $request, Jurusan $jurusan)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:jurusans,nama,' . $jurusan->id,
        ]);

        try {
            $jurusan->update([
                'nama' => $request->nama
            ]);

            return redirect()->route('admin.jurusan.index')->with('success', 'Jurusan berhasil diperbarui!');
            
        } catch (\Exception $e) {
            Log::error('Error Jurusan Update: '. $e->getMessage());
            return redirect()->route('admin.jurusan.index')->with('error', 'Gagal memperbarui data jurusan.');
        }
    }

    public function destroy(Jurusan $jurusan)
    {
        if ($jurusan->barangs()->exists()) {
            return redirect()->route('admin.jurusan.index')->with(
                'error', 
                "Gagal menghapus! Jurusan '{$jurusan->nama_jurusan}' masih memiliki data barang inventaris."
            );
        }

        try {
            $jurusan->delete($jurusan->id);
            
            return redirect()->route('admin.jurusan.index')->with('success', 'Jurusan berhasil dihapus!');
            
        } catch (\Exception $e) {
            return redirect()->route('admin.jurusan.index')->with('error', 'Terjadi kesalahan sistem saat menghapus data.');
        }
    }
}

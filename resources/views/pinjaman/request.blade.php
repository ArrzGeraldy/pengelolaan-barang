<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'Laravel') }} - Ajukan Peminjaman</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="font-sans antialiased bg-gray-50">
    <div class="w-full h-screen flex relative">
      <main class="flex-1 flex flex-col overflow-hidden ms-0 " id="content">
        <x-topbar />
        <div class="flex-1 overflow-y-auto p-6 bg-slate-50 px-12">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
              <h1 class="text-xl font-bold text-slate-900 tracking-tight">Ajukan Peminjaman Barang</h1>
              <p class="text-sm mt-1">Ajukan permintaan pinjaman barang dan tunggu verifikasi admin.</p>
            </div>
          </div>
          <x-alert-message />
          <div class="rounded-lg w-full px-4 py-4 border border-slate-200 shadow-md mt-6">
            <form action="{{ route('pinjaman.request.store') }}" method="POST" class="grid md:grid-cols-2 gap-x-6 gap-y-2">
              @csrf
              <div class="mb-4">
                <x-input-label for="barang_id" :value="__('Barang')" />
                <select id="barang_id" name="barang_id" class="block mt-2 w-full border-orange-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm bg-transparent" required>
                  <option value="">Pilih Barang</option>
                  @foreach($barangs as $barang)
                    <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>{{ $barang->nama }} ({{ $barang->stock_tersedia }} tersedia)</option>
                  @endforeach
                </select>
                <x-input-error :messages="$errors->get('barang_id')" class="mt-2" />
              </div>
              <div class="mb-4">
                <x-input-label for="jumlah_dipinjam" :value="__('Jumlah Dipinjam')" />
                <x-text-input id="jumlah_dipinjam" class="block mt-2 w-full" type="number" name="jumlah_dipinjam" :value="old('jumlah_dipinjam', 1)" min="1" required />
                <x-input-error :messages="$errors->get('jumlah_dipinjam')" class="mt-2" />
              </div>
              <div class="mb-4">
                <x-input-label for="tanggal_pinjam" :value="__('Tanggal Pinjam')" />
                <x-text-input id="tanggal_pinjam" class="block mt-2 w-full" type="date" name="tanggal_pinjam" :value="old('tanggal_pinjam')" required />
                <x-input-error :messages="$errors->get('tanggal_pinjam')" class="mt-2" />
              </div>
              <div class="mb-4">
                <x-input-label for="tanggal_dikembalikan" :value="__('Tanggal Dikembalikan')" />
                <x-text-input id="tanggal_dikembalikan" class="block mt-2 w-full" type="date" name="tanggal_dikembalikan" :value="old('tanggal_dikembalikan')" required />
                <x-input-error :messages="$errors->get('tanggal_dikembalikan')" class="mt-2" />
              </div>
              <div class="flex items-center gap-2 mt-6">
                <button type="submit" class="px-3 py-2 rounded-md text-sm font-semibold text-white bg-orange-600 hover:bg-orange-600/90 transition-all">Ajukan</button>
                <a href="{{ route('pinjaman.my') }}" class="px-3 py-2 rounded-md text-sm font-semibold text-white bg-slate-600 hover:bg-slate-600/90 transition-all">Batal</a>
              </div>
            </form>
          </div>
        </div>
      </main>
    </div>
  </body>
</html>

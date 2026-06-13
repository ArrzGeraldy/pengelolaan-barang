<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config("app.name", "Laravel") }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link
      href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
      rel="stylesheet"
    />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js',
    'resources/js/dashboard.js'])
  </head>
  <body class="font-sans antialiased bg-gray-50">
    <div class="w-full h-screen flex relative">
      <x-sidebar />

      <!-- main content -->
      <main
        class="flex-1 flex flex-col overflow-hidden ms-0 lg:ms-64"
        id="content"
      >
        <x-topbar />
        {{-- content --}}
        <div class="flex-1 overflow-y-auto p-6 bg-slate-50">
          {{-- header --}}
          <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6"
          >
            <div>
              <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                Edit Barang
              </h1>
              <p class="text-sm mt-1">
                Perbarui data barang inventaris dan pilih ulang jurusan atau kategori.
              </p>
            </div>
          </div>
          <x-alert-message />

          <div
            class="rounded-lg w-full px-4 py-4 border border-slate-200 shadow-md mt-6"
          >
            <form action="{{ route('admin.barang.update', $barang->id) }}" method="POST" class="grid md:grid-cols-2 gap-x-6 gap-y-2">
              @csrf @method('PUT')

              <div class="mb-4">
                <x-input-label for="jurusan_id" :value="__('Jurusan')" />
                <select
                  id="jurusan_id"
                  name="jurusan_id"
                 class="block mt-2 w-full border-orange-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm bg-transparent"
                  required
                >
                  <option value="">Pilih Jurusan</option>
                  @foreach($jurusans as $jurusan)
                    <option value="{{ $jurusan->id }}" {{ old('jurusan_id', $barang->jurusan_id) == $jurusan->id ? 'selected' : '' }}>
                      {{ $jurusan->nama }}
                    </option>
                  @endforeach
                </select>
                <x-input-error :messages="$errors->get('jurusan_id')" class="mt-2" />
              </div>

              <div class="mb-4">
                <x-input-label for="kategori_id" :value="__('Kategori')" />
                <select
                  id="kategori_id"
                  name="kategori_id"
              class="block mt-2 w-full border-orange-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm bg-transparent"
                  required
                >
                  <option value="">Pilih Kategori</option>
                  @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" {{ old('kategori_id', $barang->kategori_id) == $kategori->id ? 'selected' : '' }}>
                      {{ $kategori->nama }}
                    </option>
                  @endforeach
                </select>
                <x-input-error :messages="$errors->get('kategori_id')" class="mt-2" />
              </div>

              {{-- <div class="mb-4">
                <x-input-label for="kode" :value="__('Kode')" />
                <x-text-input
                  id="kode"
                  class="block mt-2 w-full"
                  type="text"
                  name="kode"
                  :value="old('kode', $barang->kode)"
                  required
                />
                <x-input-error :messages="$errors->get('kode')" class="mt-2" />
              </div> --}}

              <div class="mb-4">
                <x-input-label for="nama" :value="__('Nama Barang')" />
                <x-text-input
                  id="nama"
                  class="block mt-2 w-full"
                  type="text"
                  name="nama"
                  :value="old('nama', $barang->nama)"
                  required
                />
                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
              </div>

              <div class="mb-4">
                <x-input-label for="stok_total" :value="__('Stok Total')" />
                <x-text-input
                  id="stok_total"
                  class="block mt-2 w-full"
                  type="number"
                  name="stok_total"
                  :value="old('stok_total', $barang->stok_total)"
                  min="0"
                  required
                />
                <x-input-error :messages="$errors->get('stok_total')" class="mt-2" />
              </div>

              <div class="mb-4">
                <x-input-label for="stock_tersedia" :value="__('Stok Tersedia')" />
                <x-text-input
                  id="stock_tersedia"
                  class="block mt-2 w-full"
                  type="number"
                  name="stock_tersedia"
                  :value="old('stock_tersedia', $barang->stock_tersedia)"
                  min="0"
                  required
                />
                <x-input-error :messages="$errors->get('stock_tersedia')" class="mt-2" />
              </div>

              <div class="flex items-center gap-2 mt-6 col-span-2">
                <button
                  type="submit"
                  class="px-3 py-2 rounded-md text-sm font-semibold text-white bg-orange-600 hover:bg-orange-600/90 transition-all"
                >
                  Update
                </button>
                <a
                  href="{{ route('admin.barang.index') }}"
                  class="px-3 py-2 rounded-md text-sm font-semibold text-white bg-slate-600 hover:bg-slate-600/90 transition-all text-center"
                >
                  Batal
                </a>
              </div>
            </form>
          </div>
        </div>
      </main>
    </div>
  </body>
</html>

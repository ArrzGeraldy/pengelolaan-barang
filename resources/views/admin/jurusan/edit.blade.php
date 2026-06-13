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
                Edit Jurusan
              </h1>
              <p class="text-sm mt-1">
                Edit jurusan untuk inventaris barang.
              </p>
            </div>
          </div>
          <x-alert-message />
          <div
            class="rounded-lg w-full md:w-1/2 px-4 py-4 border border-slate-200 shadow-md mt-6"
          >
            <form
              action="{{ route('admin.jurusan.update', $jurusan->id) }}"
              method="POST"
            >
              @csrf @method('PUT')
              {{-- WAJIB untuk proses Update/Put di Laravel --}}

              <div>
                <x-input-label for="nama" :value="__('Nama')" />
                <x-text-input
                  id="nama"
                  class="block mt-2 w-full"
                  type="text"
                  name="nama"
                  :value="old('nama', $jurusan->nama)"
                  required
                  autofocus
                  placeholder="Nama Jurusan (Contoh: TKJ)"
                />
                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
              </div>

              <div class="flex items-center gap-2 mt-6">
                <button
                  type="submit"
                  class="px-3 py-2 rounded-md text-sm font-semibold text-white bg-orange-600 hover:bg-orange-600/90 transition-all"
                >
                  Update
                </button>
                <a
                  href="{{ route('admin.jurusan.index') }}"
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

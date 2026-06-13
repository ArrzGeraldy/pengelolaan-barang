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
                Manajemen Jurusan
              </h1>
              <p class="text-sm mt-1">
                Kelola daftar jurusan dan hak akses inventaris barang SMK.
              </p>
            </div>
            <div>
              <a
                href="{{ route('admin.jurusan.create') }}"
                class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white font-semibold text-sm px-4 py-2.5 rounded-xl shadow-sm shadow-orange-600/10 transition-all"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="2.5"
                  stroke="currentColor"
                  class="w-4 h-4"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 4.5v15m7.5-7.5h-15"
                  />
                </svg>
                Tambah Jurusan
              </a>
            </div>
          </div>

          <x-alert-message />

          {{-- filter --}}
          <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
            <form action="{{ route('admin.jurusan.index') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
              <div class="relative w-full sm:w-72 flex">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.603 10.603Z" />
                  </svg>
                </div>
                <input 
                  type="text" 
                  name="search" 
                  value="{{ request('search') }}" 
                  placeholder="Cari nama jurusan..." 
                  class="block w-full pl-9 pr-4 py-2 text-sm bg-transparent border border-orange-200 rounded-xl focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors placeholder:text-slate-400"
                >
              </div>

              <button 
                type="submit" 
                class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-xl text-sm font-semibold shadow-sm shadow-orange-600/10 transition-all flex items-center gap-1.5"
              >
                <span>Cari</span>
              </button>

              @if(request()->filled('search'))
                <a 
                  href="{{ route('admin.jurusan.index') }}" 
                  class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition-all flex items-center gap-1.5"
                  title="Bersihkan Pencarian"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                  </svg>
                  <span>Reset</span>
                </a>
              @endif
            </form>
          </div>

          {{-- card table --}}
          <div
            class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"
          >
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr class="bg-orange-100 border-b border-slate-200">
                    <th
                      class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700 w-16 text-center"
                    >
                      No
                    </th>
                    <th
                      class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700"
                    >
                      Nama Jurusan
                    </th>
                    <th
                      class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700"
                    >
                      Tanggal Dibuat
                    </th>
                    <th
                      class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700 w-40 text-right"
                    >
                      Aksi
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @forelse($jurusans as $index => $jurusan)
                  <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="px-6 py-4 text-sm text-center font-medium">
                      {{ $index + 1 }}
                    </td>
                    <td class="px-6 py-4">
                      <span
                        class="inline-flex items-center px-2.5 py-1 rounded-md text-sm font-semibold bg-orange-50 text-orange-700 border border-orange-100"
                      >
                        {{ $jurusan->nama }}
                      </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                      {{ $jurusan->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                      <div class="flex items-center justify-end gap-3">
                        <a
                          href="{{ route('admin.jurusan.edit', $jurusan->id) }}"
                          class="p-2 text-white bg-orange-500 hover:bg-orange-500/85 rounded-lg transition-all"
                          title="Edit Jurusan"
                        >
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="w-4 h-4"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"
                            />
                          </svg>
                        </a>

                        <form
                          action="{{ route('admin.jurusan.destroy', $jurusan->id) }}"
                          method="POST"
                          onsubmit="
                            return confirm(
                              'Apakah Anda yakin ingin menghapus jurusan ini ?',
                            );
                          "
                          class="inline"
                        >
                          @csrf @method('DELETE')
                          <button
                            type="submit"
                            class="p-2 text-white bg-red-500 hover:bg-red-500/85 rounded-lg transition-all"
                            title="Hapus Jurusan"
                          >
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              fill="none"
                              viewBox="0 0 24 24"
                              stroke-width="2"
                              stroke="currentColor"
                              class="w-4 h-4"
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                              />
                            </svg>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                      <div class="flex flex-col items-center justify-center">
                        <div
                          class="p-3 bg-orange-50 text-orange-600 rounded-full mb-3"
                        >
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-6 h-6"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18a2.25 2.25 0 012.25 2.25v4.25a2.25 2.25 0 01-2.25 2.25H2.25A2.25 2.25 0 010 20.25v-4.25A2.25 2.25 0 012.25 13.5z"
                            />
                          </svg>
                        </div>
                        <p class="text-sm font-medium text-slate-900">
                          Belum ada data jurusan
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">
                          Silakan tambah data jurusan baru terlebih dahulu.
                        </p>
                      </div>
                    </td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>


          <x-simple-pagination :data="$jurusans"/>

        </div>
      </main>
    </div>
  </body>
</html>

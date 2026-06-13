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
          <x-alert-message />

          <div
            class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-3 gap-6 mb-8"
          >
            <div
              class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between transition-all hover:shadow-md"
            >
              <div>
                <p
                  class="text-xs font-semibold text-slate-400 uppercase tracking-wider"
                >
                  Total Barang
                </p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">
                  {{ $totalBarang }}
                </h3>
                <p class="text-xs text-slate-400 mt-1">
                  Model barang terdaftar
                </p>
              </div>
              <div class="p-3 bg-orange-50 text-orange-600 rounded-xl">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="currentColor"
                  class="w-6 h-6"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"
                  />
                </svg>
              </div>
            </div>

            <div
              class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between transition-all hover:shadow-md"
            >
              <div>
                <p
                  class="text-xs font-semibold text-slate-400 uppercase tracking-wider"
                >
                  Sedang Dipinjam
                </p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">
                  {{ $barangDipinjam ?? 0 }}
                </h3>
                <p class="text-xs text-slate-400 mt-1">
                  Unit fisik di luar lab
                </p>
              </div>
              <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="currentColor"
                  class="w-6 h-6"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"
                  />
                </svg>
              </div>
            </div>

            <div
              class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between transition-all hover:shadow-md"
            >
              <div>
                <p
                  class="text-xs font-semibold text-slate-400 uppercase tracking-wider"
                >
                  Selesai Kembali
                </p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">
                  {{ $transaksiSelesai }}
                </h3>
                <p class="text-xs text-slate-400 mt-1">
                  Transaksi sukses kembali
                </p>
              </div>
              <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="currentColor"
                  class="w-6 h-6"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                  />
                </svg>
              </div>
            </div>
          </div>

          {{-- card table --}} {{-- card table --}}

          <div
            class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"
          >
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr class="bg-orange-100 border-b border-slate-200">
                    <th
                      class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700"
                    >
                      Kode
                    </th>
                    <th
                      class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700"
                    >
                      Barang
                    </th>
                    <th
                      class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700"
                    >
                      Peminjam
                    </th>
                    <th
                      class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700"
                    >
                      Jumlah
                    </th>
                    <th
                      class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700"
                    >
                      Tanggal Pinjam
                    </th>
                    <th
                      class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700"
                    >
                      Tanggal Kembali
                    </th>
                    <th
                      class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700"
                    >
                      Status
                    </th>
                    <th
                      class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700 w-40 text-right"
                    >
                      Aksi
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @forelse($pinjamans as $index => $pinjaman)
                  <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">
                      {{ $pinjaman->kode_peminjaman }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                      {{ $pinjaman->barang?->nama ?? '-' }}
                      <span
                        class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold mt-1 bg-orange-100 text-orange-700"
                        >{{ $pinjaman->barang?->jurusan?->nama ?? '-' }}</span
                      >
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $pinjaman->peminjam }}</td>
                    <td class="px-6 py-4 text-sm">
                      {{ $pinjaman->jumlah_dipinjam }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                      {{
                      \Carbon\Carbon::parse($pinjaman->tanggal_pinjam)->format('d
                      M Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                      {{
                      \Carbon\Carbon::parse($pinjaman->tanggal_dikembalikan)->format('d
                      M Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                      <span
                        class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $pinjaman->status == 'Selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700' }}"
                      >
                        {{ $pinjaman->status }}
                      </span>
                    </td>
                    <td class="px-6 py-4">
                      <div class="flex items-center justify-end gap-2">
                     

                        <a
                          href="{{ route('admin.pinjaman.edit', $pinjaman->id) }}"
                          class="p-2 text-white bg-orange-500 hover:bg-orange-500/85 rounded-lg transition-all"
                          title="Edit Transaksi"
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
                          action="{{ route('admin.pinjaman.destroy', $pinjaman->id) }}"
                          method="POST"
                          onsubmit="
                            return confirm(
                              'Apakah Anda yakin ingin menghapus riwayat peminjaman ini?',
                            );
                          "
                          class="inline"
                        >
                          @csrf @method('DELETE')
                          <button
                            type="submit"
                            class="p-2 text-white bg-red-500 hover:bg-red-500/85 rounded-lg transition-all"
                            title="Hapus Transaksi"
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
                    <td colspan="11" class="px-6 py-12 text-center">
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
                          Belum ada data pinjaman
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">
                          Silakan tambah transaksi pinjaman baru terlebih
                          dahulu.
                        </p>
                      </div>
                    </td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>
    </div>
  </body>
</html>

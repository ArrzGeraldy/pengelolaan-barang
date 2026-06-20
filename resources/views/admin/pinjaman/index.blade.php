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
                Manajemen Pinjaman
              </h1>
              <p class="text-sm mt-1">
                Kelola daftar pinjaman inventaris dan kembalikan barang ketika selesai.
              </p>
            </div>
            <div>
              <a
                href="{{ route('admin.pinjaman.create') }}"
                class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white font-semibold text-sm px-4 py-2.5 rounded-xl shadow-sm shadow-orange-600/10 transition-all"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Pinjaman Manual
              </a>
            </div>
          </div>

          <x-alert-message />

          {{-- filter --}}
          <div class="mb-6 bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
            <form action="{{ route('admin.pinjaman.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
              
              <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Cari Peminjam</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.603 10.603Z" />
                    </svg>
                  </div>
                  <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Nama peminjam..." 
                    class="block w-full pl-9 pr-4 py-2 text-sm border border-orange-200 rounded-xl focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 bg-transparent transition-colors placeholder:text-slate-400"
                  >
                </div>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Status Peminjaman</label>
                <select 
                  name="status" 
                  class="block w-full py-2 px-3 text-sm border border-orange-200 rounded-xl focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 bg-transparent text-slate-700"
                >
                  <option value="">Semua Status</option>
                  <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                  <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                  <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                  <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
              </div>

              <div class="flex gap-2">
                <button 
                  type="submit" 
                  class="flex-1 py-2 px-4 bg-orange-600 hover:bg-orange-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-all"
                >
                  Filter
                </button>

                @if(request()->anyFilled(['search', 'status']))
                  <a 
                    href="{{ route('admin.pinjaman.index') }}" 
                    class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition-all flex items-center justify-center"
                    title="Bersihkan Filter"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                  </a>
                @endif
              </div>

            </form>
          </div>

          {{-- card table --}}

          <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr class="bg-orange-100 border-b border-slate-200">
                   
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700">Kode</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700">Barang</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700">Peminjam</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700">Jumlah</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700">Tanggal Pinjam</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700">Tanggal Kembali</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700">Status</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700 w-40 text-right">Aksi</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @forelse($pinjamans as $index => $pinjaman)
                  <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $pinjaman->kode_peminjaman }}</td>
                    <td class="px-6 py-4 text-sm">{{ $pinjaman->barang?->nama ?? '-' }} <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold mt-1 bg-orange-100 text-orange-700">{{ $pinjaman->barang?->jurusan?->nama ?? '-' }}</span></td>
                    <td class="px-6 py-4 text-sm"><div>{{ $pinjaman->peminjam }}</div><div class="text-xs text-slate-500">{{ $pinjaman->user?->email ?? '-' }}</div></td>
                    <td class="px-6 py-4 text-sm">{{ $pinjaman->jumlah_dipinjam }}</td>
                    <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($pinjaman->tanggal_pinjam)->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($pinjaman->tanggal_dikembalikan)->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-sm">
                      <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold @if($pinjaman->status == 'Selesai') bg-emerald-100 text-emerald-700 @elseif($pinjaman->status == 'Dipinjam') bg-orange-100 text-orange-700 @elseif($pinjaman->status == 'Pending') bg-yellow-100 text-yellow-700 @elseif($pinjaman->status == 'Ditolak') bg-red-100 text-red-700 @else bg-slate-100 text-slate-700 @endif">
                        {{ $pinjaman->status }}
                      </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                          
                          @if($pinjaman->status === 'Pending')
                            <form action="{{ route('admin.pinjaman.approve', $pinjaman->id) }}" method="POST" class="inline">
                              @csrf
                              <button type="submit" class="p-2 text-white bg-green-600 hover:bg-green-700 rounded-lg transition-all" onclick="return confirm('Setujui permintaan ini?');" title="Setujui">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                              </button>
                            </form>
                            <form action="{{ route('admin.pinjaman.reject', $pinjaman->id) }}" method="POST" class="inline">
                              @csrf
                              <button type="submit" class="p-2 text-white bg-red-600 hover:bg-red-700 rounded-lg transition-all" onclick="return confirm('Tolak permintaan ini?');" title="Tolak">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                              </button>
                            </form>
                          @elseif($pinjaman->status === 'Dipinjam')
                            <form action="{{ route('admin.pinjaman.kembalikan', $pinjaman->id) }}" method="POST" class="inline">
                              @csrf
                              <button type="submit" class="p-2 text-white bg-green-500 hover:bg-green-500/90 rounded-lg transition-all" onclick="return confirm('Yakin ingin mengembalikan barang ini? Stok tersedia akan otomatis bertambah.');" title="Kembalikan Barang">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                </svg>
                              </button>
                            </form>
                          @elseif($pinjaman->status === 'Selesai')
                            <span class="p-2 text-green-600 opacity-50" title="Sudah Dikembalikan">
                              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                              </svg>
                            </span>
                          @endif

                          <a href="{{ route('admin.pinjaman.edit', $pinjaman->id) }}" class="p-2 text-white bg-orange-500 hover:bg-orange-500/85 rounded-lg transition-all" title="Edit Transaksi">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                              <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                            </svg>
                          </a>

                          <form action="{{ route('admin.pinjaman.destroy', $pinjaman->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat peminjaman ini?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-white bg-red-500 hover:bg-red-500/85 rounded-lg transition-all" title="Hapus Transaksi">
                              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
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
                          <div class="p-3 bg-orange-50 text-orange-600 rounded-full mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18a2.25 2.25 0 012.25 2.25v4.25a2.25 2.25 0 01-2.25 2.25H2.25A2.25 2.25 0 010 20.25v-4.25A2.25 2.25 0 012.25 13.5z" />
                            </svg>
                          </div>
                          <p class="text-sm font-medium text-slate-900">Belum ada data pinjaman</p>
                          <p class="text-xs text-slate-400 mt-0.5">Silakan tambah transaksi pinjaman baru terlebih dahulu.</p>
                        </div>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <x-simple-pagination :data="$pinjamans"/>


        </div>
      </main>
    </div>
  </body>
</html>

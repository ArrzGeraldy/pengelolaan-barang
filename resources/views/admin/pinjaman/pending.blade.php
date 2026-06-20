<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'Laravel') }} - Pending Peminjaman</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="font-sans antialiased bg-gray-50">
    <div class="w-full h-screen flex relative">
      <x-sidebar />
      <main class="flex-1 flex flex-col overflow-hidden ms-0 lg:ms-64" id="content">
        <x-topbar />
        <div class="flex-1 overflow-y-auto p-6 bg-slate-50">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
              <h1 class="text-xl font-bold text-slate-900 tracking-tight">Permintaan Peminjaman Pending</h1>
              <p class="text-sm mt-1">Tinjau dan verifikasi permintaan pinjaman yang diajukan user.</p>
            </div>
          </div>
          <x-alert-message />
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
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700 w-48 text-right">Aksi</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @forelse($pinjamans as $pinjaman)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                      <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $pinjaman->kode_peminjaman }}</td>
                      <td class="px-6 py-4 text-sm">{{ $pinjaman->barang?->nama ?? '-' }}</td>
                      <td class="px-6 py-4 text-sm">{{ $pinjaman->user?->name ?? $pinjaman->peminjam }}</td>
                      <td class="px-6 py-4 text-sm">{{ $pinjaman->jumlah_dipinjam }}</td>
                      <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($pinjaman->tanggal_pinjam)->format('d M Y') }}</td>
                      <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($pinjaman->tanggal_dikembalikan)->format('d M Y') }}</td>
                      <td class="px-6 py-4 text-right">
                        <div class="inline-flex gap-2">
                          <form action="{{ route('admin.pinjaman.approve', $pinjaman->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-2 text-xs font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 transition-all" onclick="return confirm('Setujui permintaan pinjaman ini?');">Setujui</button>
                          </form>
                          <form action="{{ route('admin.pinjaman.reject', $pinjaman->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-2 text-xs font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-all" onclick="return confirm('Tolak permintaan pinjaman ini?');">Tolak</button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center gap-2">
                          <p class="text-sm font-medium text-slate-900">Tidak ada permintaan pending.</p>
                          <p class="text-xs text-slate-400">Semua pengajuan sudah ditangani atau belum ada request baru.</p>
                        </div>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
          <x-simple-pagination :data="$pinjamans" />
        </div>
      </main>
    </div>
  </body>
</html>

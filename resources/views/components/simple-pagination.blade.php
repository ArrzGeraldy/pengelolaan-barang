@props(['data'])

@if ($data->hasPages())
  <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-slate-100 pt-4">
    <div class="text-xs text-slate-500 text-center sm:text-left">
      Menampilkan halaman 
      <span class="font-semibold text-slate-700">{{ $data->currentPage() }}</span> 
      dari <span class="font-semibold text-slate-700">{{ $data->lastPage() }}</span> 
      (<span class="font-semibold text-slate-700">{{ $data->total() }}</span> data ditemukan)
    </div>

    <div class="flex items-center gap-2 w-full sm:w-auto justify-center">
      {{-- Tombol Sebelum --}}
      @if ($data->onFirstPage())
        <span class="px-3 py-1.5 bg-slate-100 text-slate-400 rounded-xl text-xs font-semibold cursor-not-allowed select-none">
          Prev
        </span>
      @else
        <a 
          href="{{ $data->previousPageUrl() }}" 
          class="px-3 py-1.5 bg-white border border-orange-200 hover:border-orange-500 text-orange-600 hover:text-orange-700 rounded-xl text-xs font-semibold transition-all shadow-sm"
        >
          Prev
        </a>
      @endif

      <span class="text-sm font-bold px-2.5 py-1 bg-orange-50 text-orange-600 rounded-lg">
        {{ $data->currentPage() }}
      </span>

      {{-- Tombol Sesudah --}}
      @if ($data->hasMorePages())
        <a 
          href="{{ $data->nextPageUrl() }}" 
          class="px-3 py-1.5 bg-white border border-orange-200 hover:border-orange-500 text-orange-600 hover:text-orange-700 rounded-xl text-xs font-semibold transition-all shadow-sm"
        >
          Next
        </a>
      @else
        <span class="px-3 py-1.5 bg-slate-100 text-slate-400 rounded-xl text-xs font-semibold cursor-not-allowed select-none">
          Next
        </span>
      @endif
    </div>
  </div>
@endif
<aside
  class="text-white w-64 transition-all duration-300 flex-shrink-0 fixed top-0 h-screen z-10 -translate-x-full lg:-translate-x-0 bg-gradient-to-b from-orange-600 to-orange-700 shadow-sm"
>
  <div class="flex items-center justify-between">
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 px-4">
      {{-- <svg class="w-8 h-8 mr-2" fill="currentColor" viewBox="0 0 20 20">
        <path
          d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"
        />
        <path
          fill-rule="evenodd"
          d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
          clip-rule="evenodd"
        />
      </svg> --}}
      <span class="text-xl font-bold">{{ config('app.name') }}</span>
    </div>

    <button
      class="p-2 rounded-lg lg:hidden hover:bg-blue-600 transition"
      id="toggle-sidebar"
    >
      <svg
        class="w-5 h-5"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M4 6h16M4 12h16M4 18h16"
        />
      </svg>
    </button>
  </div>

  <!-- Navigation -->
<nav class="px-3 py-4">
  {{-- 1. Dashboard --}}
  <a
    href="{{ route('admin.dashboard') }}"
    class="flex items-center px-4 py-3 mb-2 rounded-lg transition-all
     {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white font-semibold' : 'text-orange-100 hover:bg-white/15 hover:text-white' }}"
  >
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
    </svg>
    Dashboard
  </a>

  {{-- 2. Jurusan --}}
  <a
    href="{{ route('admin.jurusan.index') }}"
    class="flex items-center px-4 py-3 mb-2 rounded-lg transition-all {{ request()->routeIs('admin.jurusan.*') ? 'bg-white/20 text-white font-semibold' : 'text-orange-100 hover:bg-white/15 hover:text-white' }}"
  >
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M4.263 15.541A1.75 1.75 0 0 1 3.5 14V8.571c0-.429.157-.843.442-1.161l5-5.6a1.75 1.75 0 0 1 2.616 0l5 5.6c.285.318.442.732.442 1.161V14a1.75 1.75 0 0 1-.763 1.541L12 18.167l-7.737-2.626Z" />
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.167V21m0 0h-3m3 0h3" />
    </svg>
    Jurusan
  </a>

  {{-- 3. Kategori --}}
  <a
    href="{{ route('admin.kategori.index') }}"
    class="flex items-center px-4 py-3 mb-2 rounded-lg transition-all {{ request()->routeIs('admin.kategori.*') ? 'bg-white/20 text-white font-semibold' : 'text-orange-100 hover:bg-white/15 hover:text-white' }}"
  >
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a1.43 1.43 0 0 0 2.022 0l4.319-4.319a1.43 1.43 0 0 0 0-2.022l-9.581-9.581A2.25 2.25 0 0 0 9.568 3Z" />
      <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
    </svg>
    Kategori
  </a>

  {{-- 4. Barang (Inventaris) --}}
  <a
    href="{{ route('admin.barang.index') }}"
    class="flex items-center px-4 py-3 mb-2 rounded-lg transition-all {{ request()->routeIs('admin.barang.*') ? 'bg-white/20 text-white font-semibold' : 'text-orange-100 hover:bg-white/15 hover:text-white' }}"
  >
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
    </svg>
    Data Barang
  </a>

  {{-- 5. Peminjaman --}}
  <a
    href="{{ route('admin.pinjaman.index') }}"
    class="flex items-center px-4 py-3 mb-2 rounded-lg transition-all {{ request()->routeIs('admin.pinjaman.*') ? 'bg-white/20 text-white font-semibold' : 'text-orange-100 hover:bg-white/15 hover:text-white' }}"
  >
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 0A48.536 48.536 0 0 1 12 3m0 0c-2.917 0-5.747.294-8.5.862m0 0A2.25 2.25 0 0 0 1.5 6.108V16.5A2.25 2.25 0 0 0 3.75 18.75h.007m0-12.642L3.13 6.11" />
    </svg>
    Peminjaman Barang
  </a>
</nav>

  <!-- User Info -->
  <div class="absolute bottom-0 left-0 right-0 flex items-center px-4 py-4">
    <div
      class="w-10 h-10 bg-white text-blue-600 rounded-full flex items-center justify-center font-bold"
    >
      AD
    </div>
    <div class="ml-3">
      <p class="text-sm font-semibold">Admin</p>
      {{--
      <p class="text-xs text-blue-200">{{ auth()->user()->email }}</p>
      --}}
    </div>
  </div>
</aside>

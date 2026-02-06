{{--
    Sidebar Refactored — Config-Driven
    Menu diambil dari config('navigasi') berdasarkan role user.
    Setiap item dirender oleh x-komponen.navigasi.sidebar-item
--}}
@props(['active' => null])

@php
    $user = Auth::user();
    $role = $user->peran->nm_peran ?? 'Pengguna';
    
    // Resolve role key untuk config navigasi
    $roleKey = match(true) {
        str_contains(strtolower($role), 'pimpinan')   => 'pimpinan',
        str_contains(strtolower($role), 'admin')       => 'admin',
        strtolower($role) === 'verifikator'            => 'verifikator',
        strtolower($role) === 'eksekutor'              => 'eksekutor',
        default                                        => 'pengguna',
    };
    
    $menus = config("navigasi.{$roleKey}", []);
@endphp

<div {{ $attributes->merge(['class' => 'flex h-screen flex-col bg-white border-r border-gray-200']) }}>
    {{-- Logo & Brand --}}
    <div class="flex h-16 items-center border-b border-gray-200 px-6">
        <a href="{{ route('home') }}" class="flex items-center gap-3 transition hover:opacity-80">
            <img src="{{ asset('images/logo-unila.png') }}" alt="Logo Unila" class="h-10 w-10 rounded-lg shadow-md">
            <div>
                <h1 class="text-lg font-bold text-myunila">DomainTIK</h1>
                <p class="text-xs text-gray-500">UPA TIK Universitas Lampung</p>
            </div>
        </a>
    </div>

    {{-- Navigation Menu (config-driven) --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4">
        <div class="space-y-1">
            @foreach($menus as $menu)
                <x-komponen.navigasi.sidebar-item 
                    :route="$menu['route']" 
                    :judul="$menu['judul']" 
                    :ikon="$menu['ikon']"
                    :badge="$menu['badge_key'] ?? null"
                    :badgeColor="$menu['badge_color'] ?? 'warning'" />
            @endforeach
        </div>
    </nav>

    {{-- User Profile & Logout --}}
    <div class="border-t border-gray-200 p-4">
        <div class="mb-3 flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-myunila-100 text-myunila">
                <span class="text-sm font-bold">{{ substr($user->nm, 0, 2) }}</span>
            </div>
            <div class="flex-1 overflow-hidden">
                <p class="truncate text-sm font-medium text-gray-900">{{ $user->nm }}</p>
                <p class="truncate text-xs text-gray-500">{{ $role }}</p>
            </div>
        </div>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50">
                <x-icon name="logout" class="h-5 w-5" />
                <span>Keluar</span>
            </button>
        </form>
    </div>
</div>

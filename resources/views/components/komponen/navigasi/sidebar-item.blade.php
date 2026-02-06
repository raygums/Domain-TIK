{{--
    Komponen: x-navigasi.sidebar-item
    
    Item sidebar reusable untuk navigasi.
    
    Props:
    - route (required): Nama route Laravel
    - judul (required): Label menu
    - ikon: Nama ikon (dari x-icon)
    - badge: Teks badge (misal: jumlah notifikasi)
    - badgeColor: Warna badge (warning, danger, success, info)
--}}
@props([
    'route',
    'judul',
    'ikon' => 'document-text',
    'badge' => null,
    'badgeColor' => 'warning',
])

@php
    $routeExists = $route && \Illuminate\Support\Facades\Route::has($route);
    $active = $route ? request()->routeIs($route) || request()->routeIs($route . '.*') : false;
    
    $badgeClasses = match($badgeColor) {
        'warning' => 'bg-warning-light text-warning',
        'danger'  => 'bg-danger-light text-danger',
        'success' => 'bg-success-light text-success',
        'info'    => 'bg-info-light text-info',
        default   => 'bg-gray-100 text-gray-600',
    };
@endphp

@if($routeExists)
    <a href="{{ route($route) }}" 
       class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
              {{ $active 
                  ? 'bg-myunila-50 text-myunila' 
                  : 'text-gray-700 hover:bg-gray-50 hover:text-myunila' }}">
        <x-icon :name="$ikon" class="h-5 w-5 flex-shrink-0 
            {{ $active ? 'text-myunila' : 'text-gray-400 group-hover:text-myunila' }}" />
        <span class="flex-1">{{ $judul }}</span>
        @if($badge)
            <span class="rounded-full {{ $badgeClasses }} px-2 py-0.5 text-xs font-semibold">{{ $badge }}</span>
        @endif
    </a>
@else
    <div class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-400 cursor-not-allowed opacity-50">
        <x-icon :name="$ikon" class="h-5 w-5 flex-shrink-0" />
        <span class="flex-1">{{ $judul }}</span>
        <span class="text-xs bg-gray-100 px-2 py-0.5 rounded-full">Segera</span>
    </div>
@endif

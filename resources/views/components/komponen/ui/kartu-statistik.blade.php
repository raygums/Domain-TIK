{{--
    Komponen: x-ui.kartu-statistik
    
    Kartu dashboard untuk menampilkan metrik/statistik.
    
    Props:
    - judul (required): Label metrik
    - angka (required): Nilai/angka yang ditampilkan
    - ikon: Nama ikon (dari komponen x-icon)
    - warna: Tema warna (myunila, warning, success, danger, info, purple, gray)
    - keterangan: Teks kecil di bawah angka
    - tautan: URL jika kartu bisa diklik
--}}
@props([
    'judul',
    'angka',
    'ikon' => 'document-text',
    'warna' => 'myunila',
    'keterangan' => null,
    'tautan' => null,
])

@php
    $warnaConfig = match($warna) {
        'myunila' => ['bg' => 'bg-myunila-50', 'text' => 'text-myunila', 'angka' => 'text-gray-900'],
        'warning' => ['bg' => 'bg-warning-light', 'text' => 'text-warning', 'angka' => 'text-warning'],
        'success' => ['bg' => 'bg-success-light', 'text' => 'text-success', 'angka' => 'text-success'],
        'danger'  => ['bg' => 'bg-danger-light', 'text' => 'text-danger', 'angka' => 'text-danger'],
        'info'    => ['bg' => 'bg-info-light', 'text' => 'text-info', 'angka' => 'text-info'],
        'purple'  => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'angka' => 'text-purple-600'],
        'gray'    => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'angka' => 'text-gray-900'],
        default   => ['bg' => 'bg-myunila-50', 'text' => 'text-myunila', 'angka' => 'text-gray-900'],
    };
    
    $tag = $tautan ? 'a' : 'div';
@endphp

<{{ $tag }} 
    @if($tautan) href="{{ $tautan }}" @endif
    {{ $attributes->merge([
        'class' => 'overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md'
            . ($tautan ? ' group cursor-pointer' : '')
    ]) }}
>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500">{{ $judul }}</p>
            <p class="mt-1 text-2xl font-bold {{ $warnaConfig['angka'] }}">
                {{ is_numeric($angka) ? number_format($angka) : $angka }}
            </p>
            @if($keterangan)
                <p class="mt-1 text-xs text-gray-500">{{ $keterangan }}</p>
            @endif
        </div>
        <div class="rounded-xl {{ $warnaConfig['bg'] }} p-3">
            <x-icon :name="$ikon" class="h-7 w-7 {{ $warnaConfig['text'] }}" />
        </div>
    </div>
    
    {{-- Slot untuk konten tambahan (link, badge, dll) --}}
    @if(!$slot->isEmpty())
        <div class="mt-3">
            {{ $slot }}
        </div>
    @endif
</{{ $tag }}>

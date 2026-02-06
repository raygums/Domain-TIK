{{--
    Komponen: x-ui.badge-status
    
    Badge dinamis yang otomatis menentukan warna berdasarkan status pengajuan.
    
    Props:
    - status (required): Nama status (Draft, Diajukan, Disetujui Verifikator, dll)
--}}
@props([
    'status',
])

@php
    $statusLower = strtolower($status ?? '');
    
    $badgeClass = match(true) {
        str_contains($statusLower, 'selesai')                => 'bg-success-light text-success',
        str_contains($statusLower, 'disetujui')              => 'bg-info-light text-info',
        str_contains($statusLower, 'ditolak')                => 'bg-danger-light text-danger',
        str_contains($statusLower, 'dikerjakan')             => 'bg-info-light text-info',
        str_contains($statusLower, 'diajukan')               => 'bg-warning-light text-warning',
        str_contains($statusLower, 'draft')                  => 'bg-gray-100 text-gray-700',
        str_contains($statusLower, 'menunggu')               => 'bg-warning-light text-warning',
        str_contains($statusLower, 'aktif')                  => 'bg-success-light text-success',
        str_contains($statusLower, 'nonaktif') || str_contains($statusLower, 'non-aktif') => 'bg-danger-light text-danger',
        default                                              => 'bg-gray-100 text-gray-800',
    };
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium $badgeClass"
]) }}>
    {{ $status ?? '-' }}
</span>

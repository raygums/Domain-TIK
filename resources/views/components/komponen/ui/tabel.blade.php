{{--
    Komponen: x-ui.tabel
    
    Wrapper tabel responsif dengan header dan empty state.
    
    Props:
    - judul: Judul tabel di header
    - kosong: Apakah data kosong (untuk menampilkan empty state)
    - pesanKosong: Pesan jika data kosong
    - ikonKosong: Ikon untuk empty state
--}}
@props([
    'judul' => null,
    'kosong' => false,
    'pesanKosong' => 'Belum ada data untuk ditampilkan.',
    'ikonKosong' => 'document-text',
    'aksi' => null,
])

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
    @if($judul)
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">{{ $judul }}</h2>
                @if($aksi)
                    {{ $aksi }}
                @endif
            </div>
        </div>
    @endif

    @if($kosong)
        <div class="p-12 text-center">
            <x-icon :name="$ikonKosong" class="mx-auto h-16 w-16 text-gray-300" />
            <h3 class="mt-4 text-lg font-medium text-gray-900">{{ $pesanKosong }}</h3>
            @if(isset($slotKosong))
                <div class="mt-2 text-gray-500">{{ $slotKosong }}</div>
            @endif
        </div>
    @else
        <div class="overflow-x-auto">
            {{ $slot }}
        </div>
    @endif
</div>

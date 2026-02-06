{{--
    Komponen: x-formulir.bagian (Section wrapper untuk form)
    
    Props:
    - title (required): Judul section
    - subtitle: Sub-judul
    - number: Nomor section (akan muncul sebagai badge)
    - headerBg: CSS class background header (default: bg-myunila-50)
--}}
@props([
    'title',
    'subtitle' => null,
    'number' => null,
    'headerBg' => 'bg-myunila-50',
])

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-200 {{ $headerBg }} px-6 py-4">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-gray-900">
            @if($number)
                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-myunila text-xs font-bold text-white">{{ $number }}</span>
            @endif
            {{ $title }}
        </h2>
        @if($subtitle)
            <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
        @endif
    </div>
    <div class="p-6">
        {{ $slot }}
    </div>
</div>

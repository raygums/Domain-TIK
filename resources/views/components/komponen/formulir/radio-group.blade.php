{{--
    Komponen: x-formulir.radio-group
    
    Props:
    - name (required): Nama field
    - label: Label group
    - options: Array asosiatif [value => label]
    - required: Apakah field wajib
    - value: Default value (otomatis fallback ke old())
    - inline: Tampilkan inline (default true)
--}}
@props([
    'name',
    'label' => null,
    'options' => [],
    'required' => false,
    'value' => null,
    'inline' => true,
])

@php
    $selectedValue = old($name, $value);
@endphp

<div {{ $attributes->only('class') }}>
    @if($label)
        <label class="mb-2 block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-error">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $inline ? 'flex gap-4 flex-wrap' : 'space-y-2' }}">
        @foreach($options as $optValue => $optLabel)
            <label class="flex items-center gap-2 cursor-pointer">
                <input 
                    type="radio" 
                    name="{{ $name }}" 
                    value="{{ $optValue }}"
                    {{ $required ? 'required' : '' }}
                    {{ (string) $selectedValue === (string) $optValue ? 'checked' : '' }}
                    {{ $attributes->except(['class'])->merge([
                        'class' => 'h-4 w-4 border-gray-300 text-myunila focus:ring-myunila'
                    ]) }}
                >
                <span class="text-sm text-gray-700">{{ $optLabel }}</span>
            </label>
        @endforeach
    </div>

    @error($name)
        <p class="mt-1 text-sm text-error">{{ $message }}</p>
    @enderror
</div>

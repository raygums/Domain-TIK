{{--
    Komponen: x-formulir.select
    
    Props:
    - name (required): Nama field
    - label: Label field
    - options: Array asosiatif [value => label] atau Collection
    - placeholder: Teks opsi default (kosong)
    - required: Apakah field wajib
    - hint: Teks bantuan
    - disabled: Apakah field disabled
    - value: Default value (otomatis fallback ke old())
--}}
@props([
    'name',
    'label' => null,
    'options' => [],
    'placeholder' => 'Pilih opsi...',
    'required' => false,
    'hint' => null,
    'disabled' => false,
    'value' => null,
])

@php
    $inputId = $attributes->get('id', $name);
    $selectedValue = old($name, $value);
    $hasError = $errors->has($name);
    
    // Normalize options: support Collection & array
    $normalizedOptions = $options instanceof \Illuminate\Support\Collection 
        ? $options->toArray() 
        : (array) $options;
@endphp

<div {{ $attributes->only('class') }}>
    @if($label)
        <label for="{{ $inputId }}" class="mb-1 block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-error">*</span>
            @endif
        </label>
    @endif

    <select
        name="{{ $name }}"
        id="{{ $inputId }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->except(['class', 'id'])->merge([
            'class' => 'form-input w-full rounded-lg border-gray-300 py-2.5 shadow-sm transition focus:border-myunila focus:ring-myunila sm:text-sm'
                . ($hasError ? ' border-error ring-error/20' : '')
                . ($disabled ? ' bg-gray-100 cursor-not-allowed' : '')
        ]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($normalizedOptions as $optValue => $optLabel)
            <option value="{{ $optValue }}" {{ (string) $selectedValue === (string) $optValue ? 'selected' : '' }}>
                {{ $optLabel }}
            </option>
        @endforeach
    </select>

    @if($hint)
        <p class="mt-1 text-sm text-gray-500">{!! $hint !!}</p>
    @endif

    @error($name)
        <p class="mt-1 text-sm text-error">{{ $message }}</p>
    @enderror
</div>

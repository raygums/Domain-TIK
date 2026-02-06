{{--
    Komponen: x-formulir.input
    
    Props:
    - name (required): Nama field
    - label: Label field
    - type: Tipe input (text, email, tel, date, number, password)
    - placeholder: Placeholder text
    - required: Apakah field wajib
    - hint: Teks bantuan di bawah input (support HTML)
    - disabled: Apakah field disabled
    - readonly: Apakah field readonly
    - value: Default value (otomatis fallback ke old())
    
    Semua attributes tambahan (data-*, id, dll) akan di-forward ke <input>.
--}}
@props([
    'name',
    'label' => null,
    'type' => 'text',
    'placeholder' => '',
    'required' => false,
    'hint' => null,
    'disabled' => false,
    'readonly' => false,
    'value' => null,
])

@php
    $inputId = $attributes->get('id', $name);
    $inputValue = old($name, $value);
    $hasError = $errors->has($name);
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

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $inputId }}"
        value="{{ $inputValue }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $readonly ? 'readonly' : '' }}
        {{ $attributes->except(['class', 'id'])->merge([
            'class' => 'form-input w-full rounded-lg border-gray-300 py-2.5 shadow-sm transition focus:border-myunila focus:ring-myunila sm:text-sm'
                . ($hasError ? ' border-error ring-error/20' : '')
                . ($disabled ? ' bg-gray-100 cursor-not-allowed' : '')
        ]) }}
    >

    @if($hint)
        <p class="mt-1 text-sm text-gray-500">{!! $hint !!}</p>
    @endif

    @error($name)
        <p class="mt-1 text-sm text-error">{{ $message }}</p>
    @enderror
</div>

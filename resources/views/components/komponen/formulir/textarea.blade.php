{{--
    Komponen: x-formulir.textarea
    
    Props:
    - name (required): Nama field
    - label: Label field
    - placeholder: Placeholder text
    - required: Apakah field wajib
    - hint: Teks bantuan
    - rows: Jumlah baris (default 4)
    - disabled: Apakah field disabled
    - value: Default value (otomatis fallback ke old())
--}}
@props([
    'name',
    'label' => null,
    'placeholder' => '',
    'required' => false,
    'hint' => null,
    'rows' => 4,
    'disabled' => false,
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

    <textarea
        name="{{ $name }}"
        id="{{ $inputId }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->except(['class', 'id'])->merge([
            'class' => 'form-input w-full rounded-lg border-gray-300 py-2.5 shadow-sm transition focus:border-myunila focus:ring-myunila sm:text-sm'
                . ($hasError ? ' border-error ring-error/20' : '')
                . ($disabled ? ' bg-gray-100 cursor-not-allowed' : '')
        ]) }}
    >{{ $inputValue }}</textarea>

    @if($hint)
        <p class="mt-1 text-sm text-gray-500">{!! $hint !!}</p>
    @endif

    @error($name)
        <p class="mt-1 text-sm text-error">{{ $message }}</p>
    @enderror
</div>

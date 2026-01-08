@props([
    'name',
    'id' => null,
    'value' => '',
    'label' => '',
    'required' => false,
    'disabled' => false,
    'placeholder' => 'Pilih opsi',
    'options' => [],
    'error' => null,
])

@php
    $inputId = $id ?? $name;
    $hasError = $error || $errors->has($name);
    $selectedValue = old($name, $value);
@endphp

<div>
    @if($label)
        <label for="{{ $inputId }}" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            {{ $label }}
            @if($required)
                <span class="text-error-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        <select 
            name="{{ $name }}"
            id="{{ $inputId }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            {{ $attributes->merge([
                'class' => 'shadow-theme-xs h-11 w-full appearance-none rounded-lg border bg-transparent px-4 py-2.5 pr-11 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:bg-gray-900 dark:text-white/90 ' . 
                ($hasError 
                    ? 'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-700' 
                    : 'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800'
                ) .
                ($disabled ? ' bg-gray-100 cursor-not-allowed dark:bg-gray-800' : '')
            ]) }}
        >
            @if($placeholder)
                <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">{{ $placeholder }}</option>
            @endif
            @foreach($options as $optValue => $optLabel)
                <option 
                    value="{{ $optValue }}" 
                    class="text-gray-700 dark:bg-gray-900 dark:text-gray-300"
                    {{ (string)$selectedValue === (string)$optValue ? 'selected' : '' }}
                >
                    {{ $optLabel }}
                </option>
            @endforeach
            {{ $slot }}
        </select>
        <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
            <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
    </div>
    
    @error($name)
        <p class="mt-1.5 text-sm text-error-500">{{ $message }}</p>
    @enderror
</div>

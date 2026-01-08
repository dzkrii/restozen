@props([
    'name',
    'id' => null,
    'value' => '',
    'placeholder' => '',
    'label' => '',
    'required' => false,
    'disabled' => false,
    'rows' => 4,
    'error' => null,
    'hint' => null,
])

@php
    $inputId = $id ?? $name;
    $hasError = $error || $errors->has($name);
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
    
    <textarea 
        name="{{ $name }}"
        id="{{ $inputId }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        {{ $attributes->merge([
            'class' => 'shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 ' . 
            ($hasError 
                ? 'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-700' 
                : 'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800'
            ) .
            ($disabled ? ' bg-gray-100 cursor-not-allowed dark:bg-gray-800' : '')
        ]) }}
    >{{ old($name, $value) }}</textarea>
    
    @if($hint && !$hasError)
        <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
    
    @error($name)
        <p class="mt-1.5 text-sm text-error-500">{{ $message }}</p>
    @enderror
</div>

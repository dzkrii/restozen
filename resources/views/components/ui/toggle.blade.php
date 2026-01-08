@props([
    'name',
    'id' => null,
    'checked' => false,
    'label' => '',
    'disabled' => false,
])

@php
    $inputId = $id ?? $name;
@endphp

<label for="{{ $inputId }}" class="flex cursor-pointer items-center gap-3 text-sm font-medium text-gray-700 dark:text-gray-400 select-none">
    <div class="relative">
        <input 
            type="checkbox"
            name="{{ $name }}"
            id="{{ $inputId }}"
            value="1"
            @if(old($name, $checked)) checked @endif
            @if($disabled) disabled @endif
            class="sr-only peer"
            {{ $attributes }}
        />
        <div class="block h-6 w-11 rounded-full bg-gray-200 transition-colors peer-checked:bg-brand-500 peer-disabled:opacity-50 dark:bg-gray-700"></div>
        <div class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow-md transition-transform peer-checked:translate-x-5"></div>
    </div>
    @if($label)
        <span>{{ $label }}</span>
    @endif
</label>

@props([
    'variant' => 'default', // default, success, warning, error
])

@php
    $variantClasses = match($variant) {
        'success' => 'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-400',
        'error' => 'bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-400',
        'warning' => 'bg-warning-50 text-warning-700 dark:bg-warning-500/15 dark:text-warning-400',
        'info' => 'bg-brand-50 text-brand-700 dark:bg-brand-500/15 dark:text-brand-400',
        default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium $variantClasses"]) }}>
    {{ $slot }}
</span>

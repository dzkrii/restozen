@props([
    'variant' => 'primary', // primary, secondary, success, danger, warning, outline
    'size' => 'md', // sm, md, lg
    'type' => 'button',
    'href' => null,
    'disabled' => false,
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 font-medium transition-colors focus:outline-hidden focus:ring-3 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-sm rounded-lg',
        'md' => 'px-4 py-2.5 text-sm rounded-lg',
        'lg' => 'px-6 py-3 text-base rounded-lg',
        default => 'px-4 py-2.5 text-sm rounded-lg',
    };
    
    $variantClasses = match($variant) {
        'primary' => 'bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600 focus:ring-brand-500/30',
        'secondary' => 'bg-gray-100 text-gray-700 shadow-theme-xs hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 focus:ring-gray-500/30',
        'success' => 'bg-success-500 text-white shadow-theme-xs hover:bg-success-600 focus:ring-success-500/30',
        'danger' => 'bg-error-500 text-white shadow-theme-xs hover:bg-error-600 focus:ring-error-500/30',
        'warning' => 'bg-warning-500 text-white shadow-theme-xs hover:bg-warning-600 focus:ring-warning-500/30',
        'outline' => 'border border-gray-300 bg-white text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800 focus:ring-gray-500/30',
        'outline-primary' => 'border border-brand-500 bg-transparent text-brand-500 hover:bg-brand-50 dark:hover:bg-brand-500/10 focus:ring-brand-500/30',
        'outline-danger' => 'border border-error-500 bg-transparent text-error-500 hover:bg-error-50 dark:hover:bg-error-500/10 focus:ring-error-500/30',
        'ghost' => 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 focus:ring-gray-500/30',
        default => 'bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600 focus:ring-brand-500/30',
    };
    
    $classes = "$baseClasses $sizeClasses $variantClasses";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" @if($disabled) disabled @endif {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif

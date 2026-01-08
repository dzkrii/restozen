@props([
    'variant' => 'success', // success, error, warning, info
])

@php
    $variantClasses = match($variant) {
        'success' => 'bg-success-50 border-success-200 text-success-700 dark:bg-success-500/10 dark:border-success-500/20 dark:text-success-400',
        'error' => 'bg-error-50 border-error-200 text-error-700 dark:bg-error-500/10 dark:border-error-500/20 dark:text-error-400',
        'warning' => 'bg-warning-50 border-warning-200 text-warning-700 dark:bg-warning-500/10 dark:border-warning-500/20 dark:text-warning-400',
        'info' => 'bg-brand-50 border-brand-200 text-brand-700 dark:bg-brand-500/10 dark:border-brand-500/20 dark:text-brand-400',
        default => 'bg-success-50 border-success-200 text-success-700 dark:bg-success-500/10 dark:border-success-500/20 dark:text-success-400',
    };
    
    $iconClasses = match($variant) {
        'success' => 'text-success-500',
        'error' => 'text-error-500',
        'warning' => 'text-warning-500',
        'info' => 'text-brand-500',
        default => 'text-success-500',
    };
@endphp

<div {{ $attributes->merge(['class' => "mb-4 rounded-lg border p-4 text-sm $variantClasses"]) }} role="alert">
    <div class="flex items-start gap-3">
        <span class="{{ $iconClasses }} mt-0.5 flex-shrink-0">
            @if($variant === 'success')
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            @elseif($variant === 'error')
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            @elseif($variant === 'warning')
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            @else
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            @endif
        </span>
        <div>{{ $slot }}</div>
    </div>
</div>

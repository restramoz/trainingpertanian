@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-bold rounded-lg transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-safety-orange focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none cursor-pointer text-center';

    $sizes = [
        'sm' => 'px-3.5 py-1.5 text-xs sm:text-sm gap-1.5 min-h-[38px]',
        'md' => 'px-5 py-2.5 text-sm sm:text-base gap-2 min-h-[44px]',
        'lg' => 'px-7 py-3 text-base sm:text-lg gap-2.5 min-h-[48px]',
    ];

    $variants = [
        'primary' => 'bg-safety-orange hover:bg-burnt-brick text-industry-black hover:text-white shadow-md active:bg-dark-mahogany font-bold',
        'secondary' => 'bg-white hover:bg-neutral-100 text-dark-mahogany font-bold shadow-sm border-2 border-dark-mahogany active:bg-neutral-200',
        'outline' => 'bg-transparent hover:bg-dark-mahogany hover:text-white text-dark-mahogany font-bold border-2 border-dark-mahogany active:bg-dark-mahogany',
        'white' => 'bg-white hover:bg-neutral-100 text-industry-black font-semibold shadow-sm border border-neutral-300',
        'wa' => 'bg-safety-orange hover:bg-burnt-brick text-industry-black hover:text-white shadow-md active:bg-dark-mahogany font-bold',
        'dark' => 'bg-industry-black hover:bg-dark-mahogany text-white font-bold shadow-md border border-neutral-700 active:bg-industry-black',
    ];

    $sizeClasses = $sizes[$size] ?? $sizes['md'];
    $variantClasses = $variants[$variant] ?? $variants['primary'];
    $classes = "{$baseClasses} {$sizeClasses} {$variantClasses}";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif

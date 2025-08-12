@props(['color' => 'blue', 'size' => 'base'])

@php
    $sizes = [
        'sm' => 'px-4 py-2 text-sm',
        'base' => 'px-6 py-3 text-base',
        'lg' => 'px-8 py-4 text-lg'
    ];

    $colors = [
        'blue' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
        'green' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
        'red' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500'
    ];
@endphp

<button type="submit" {{ $attributes->merge([
    'class' => "inline-flex items-center rounded-lg shadow-md text-white focus:outline-none focus:ring-2 {$colors[$color]} {$sizes[$size]}"
]) }}>
    {{ $slot }}
</button>

@props(['prioridad'])

@php
    $estilos = [
        'leve' => 'bg-gray-100 text-gray-800',
        'moderado' => 'bg-orange-100 text-orange-800',
        'grave' => 'bg-red-100 text-red-800',
    ];

    $etiquetas = [
        'leve' => __('Leve'),
        'moderado' => __('Moderado'),
        'grave' => __('Grave'),
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '.($estilos[$prioridad] ?? 'bg-gray-100 text-gray-800')]) }}>
    {{ $etiquetas[$prioridad] ?? $prioridad }}
</span>

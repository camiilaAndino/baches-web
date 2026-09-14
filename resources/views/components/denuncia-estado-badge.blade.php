@props(['estado'])

@php
    $estilos = [
        'pendiente' => 'bg-amber-100 text-amber-800',
        'en_proceso' => 'bg-blue-100 text-blue-800',
        'resuelta' => 'bg-green-100 text-green-800',
    ];

    $etiquetas = [
        'pendiente' => __('Pendiente'),
        'en_proceso' => __('En proceso'),
        'resuelta' => __('Resuelta'),
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '.($estilos[$estado] ?? 'bg-gray-100 text-gray-800')]) }}>
    {{ $etiquetas[$estado] ?? $estado }}
</span>

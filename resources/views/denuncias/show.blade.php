<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
    @endpush

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Denuncia') }} #{{ $denuncia->id }}
            </h2>

            <a href="{{ route('denuncias.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                {{ __('Volver al listado') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-md px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white shadow-sm rounded-lg p-4">
                        @if ($denuncia->fotos->isEmpty())
                            <div class="h-64 flex items-center justify-center text-sm text-gray-400 bg-gray-50 rounded-md">
                                {{ __('Sin fotos') }}
                            </div>
                        @else
                            <div x-data="{ activa: 0, fotos: {{ $denuncia->fotos->pluck('foto')->map(fn ($f) => asset('imagenes_denuncias/'.$f))->toJson() }} }">
                                <img :src="fotos[activa]" alt="" class="w-full h-64 sm:h-96 object-cover rounded-md bg-gray-100">

                                @if ($denuncia->fotos->count() > 1)
                                    <div class="mt-3 flex gap-2 overflow-x-auto">
                                        <template x-for="(foto, index) in fotos" :key="index">
                                            <button type="button" @click="activa = index"
                                                class="shrink-0 h-16 w-16 rounded-md overflow-hidden ring-2"
                                                :class="activa === index ? 'ring-indigo-500' : 'ring-transparent opacity-70 hover:opacity-100'">
                                                <img :src="foto" alt="" class="h-full w-full object-cover">
                                            </button>
                                        </template>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Tipo de denuncia') }}</p>
                            <p class="text-gray-900 font-medium">{{ $denuncia->tipoDenuncia->nombre }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">{{ __('Descripción') }}</p>
                            <p class="text-gray-900">{{ $denuncia->descripcion }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">{{ __('Ubicación') }}</p>
                            <p class="text-gray-900">{{ $denuncia->direccion ?? __('Sin referencia') }}</p>
                            <p class="text-xs text-gray-400 mb-2">{{ $denuncia->latitud }}, {{ $denuncia->longitud }}</p>
                            <div id="mapa-denuncia" class="w-full h-64 rounded-md z-0"></div>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">{{ __('Reportado por') }}</p>
                            <p class="text-gray-900">{{ $denuncia->usuario->name ?? __('Anónimo') }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">{{ __('Prioridad') }}</p>
                            <x-denuncia-prioridad-badge :prioridad="$denuncia->prioridad" class="text-sm px-3 py-1" />
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">{{ __('Fecha de reporte') }}</p>
                            <p class="text-gray-900 text-sm">{{ $denuncia->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-6 space-y-3">
                        <p class="text-sm text-gray-500">{{ __('Estado') }}</p>

                        @foreach (\App\Models\Denuncia::ESTADOS as $estado)
                            <form action="{{ route('denuncias.estado', $denuncia) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="estado" value="{{ $estado }}">

                                @if ($denuncia->estado === $estado)
                                    <div class="w-full flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                        {{ __(ucfirst(str_replace('_', ' ', $estado))) }}
                                    </div>
                                @else
                                    <button type="submit"
                                        class="w-full text-left rounded-md px-3 py-2 text-sm font-medium text-gray-600 border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                        {{ __(ucfirst(str_replace('_', ' ', $estado))) }}
                                    </button>
                                @endif
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            const mapa = L.map('mapa-denuncia').setView([{{ $denuncia->latitud }}, {{ $denuncia->longitud }}], 17);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 19,
            }).addTo(mapa);

            L.marker([{{ $denuncia->latitud }}, {{ $denuncia->longitud }}]).addTo(mapa);
        </script>
    @endpush
</x-app-layout>

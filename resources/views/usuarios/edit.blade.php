<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('usuarios.update', $user) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    @include('usuarios._form')

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('usuarios.index') }}">
                            <x-secondary-button type="button">{{ __('Cancelar') }}</x-secondary-button>
                        </a>
                        <x-primary-button>{{ __('Guardar cambios') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

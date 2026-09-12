<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar rol') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if ($role->esProtegido())
                    <div class="bg-amber-50 border border-amber-200 text-amber-700 text-sm rounded-md px-4 py-3 mb-6">
                        {{ __('Este es un rol predefinido del sistema y no se puede modificar.') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('roles.update', $role) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <fieldset @disabled($role->esProtegido())>
                        @include('roles._form')
                    </fieldset>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('roles.index') }}">
                            <x-secondary-button type="button">{{ __('Cancelar') }}</x-secondary-button>
                        </a>
                        @unless ($role->esProtegido())
                            <x-primary-button>{{ __('Guardar cambios') }}</x-primary-button>
                        @endunless
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

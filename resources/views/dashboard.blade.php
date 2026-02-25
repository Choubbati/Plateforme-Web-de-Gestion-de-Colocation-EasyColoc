<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Messages --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Case 1: no active colocation --}}
            @if (!$membership)
                <div class="bg-white p-6 rounded shadow">
                    <p class="mb-4 text-gray-700">
                        Vous n’avez pas encore de colocation active.
                    </p>

                    <a href="{{ route('colocations.create') }}"
                       class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Créer une colocation
                    </a>
                </div>

                {{-- Case 2: active colocation --}}
            @else
                <div class="bg-white p-6 rounded shadow">
                    <h3 class="text-lg font-bold mb-2">
                        Votre colocation active
                    </h3>

                    <p class="mb-1">
                        <strong>Nom :</strong> {{ $colocation->name }}
                    </p>

                    <p class="mb-4">
                        <strong>Votre rôle :</strong> {{ ucfirst($membership->role) }}
                    </p>

                    <span class="inline-block px-4 py-2 bg-gray-200 rounded text-gray-700">
                        Accès à la colocation <a href="{{ route('colocations.show', $colocation) }}"
                                                  class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
    Voir la colocation
</a>
                    </span>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>

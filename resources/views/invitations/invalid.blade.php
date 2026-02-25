<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Invitation
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <p class="text-red-700">{{ $message }}</p>

                <a href="{{ route('dashboard') }}" class="inline-block mt-4 px-4 py-2 bg-gray-200 rounded">
                    Retour Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

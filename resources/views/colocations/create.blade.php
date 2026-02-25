<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
        <h1 class="text-xl font-bold mb-4">Créer une colocation</h1>

        <form method="POST" action="{{ route('colocations.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-1">Nom</label>
                <input name="name" class="w-full border rounded p-2" required>
                @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Créer
            </button>
        </form>
    </div>
</x-guest-layout>

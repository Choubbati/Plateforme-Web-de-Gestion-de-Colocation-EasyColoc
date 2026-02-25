<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Colocation : {{ $colocation->name }}
            </h2>

            <a href="{{ route('dashboard') }}"
               class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300">
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-bold mb-4">Membres</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Nom</th>
                            <th class="text-left py-2">Email</th>
                            <th class="text-left py-2">Rôle</th>
                            <th class="text-left py-2">Réputation</th>
                            <th class="text-left py-2">Rejoint le</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($members as $member)
                            <tr class="border-b">
                                <td class="py-2">{{ $member->name }}</td>
                                <td class="py-2">{{ $member->email }}</td>
                                <td class="py-2">{{ ucfirst($member->pivot->role) }}</td>
                                <td class="py-2">{{ $member->reputation_score }}</td>
                                <td class="py-2">
                                    {{ optional($member->pivot->joined_at)->format('Y-m-d') }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if ($colocation->owner_id === auth()->id())
                        <div class="mt-8 bg-white p-6 rounded shadow">
                            <h3 class="text-lg font-bold mb-3">Inviter un membre</h3>

                            <form method="POST" action="{{ route('invitations.store', $colocation) }}">
                                @csrf
                                <div class="flex gap-3">
                                    <input type="email" name="email" required
                                           class="border rounded p-2 w-full"
                                           placeholder="email@example.com">

                                    <button class="px-4 py-2 bg-blue-600 text-white rounded">
                                        Inviter
                                    </button>
                                </div>
                                @error('email') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                            </form>

                            <p class="text-sm text-gray-500 mt-3">
                                (Pour l’instant, le lien s’affiche dans le message “success”.)
                            </p>
                        </div>
                    @endif
                </div>

                <div class="mt-6 text-gray-600">
                    <p><strong>Status :</strong> {{ $colocation->status }}</p>
                    <p><strong>Owner ID :</strong> {{ $colocation->owner_id }}</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

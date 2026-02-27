<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">
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

            {{-- Membres --}}
            <div class="bg-white p-6 rounded shadow mb-6">
                <h3 class="text-lg font-bold mb-4">Membres</h3>

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
                    @forelse ($colocation->activeMembers as $member)
                        <tr class="border-b">
                            <td class="py-2">{{ $member->name }}</td>
                            <td class="py-2">{{ $member->email }}</td>
                            <td class="py-2">{{ ucfirst($member->pivot->role) }}</td>
                            <td class="py-2">{{ $member->reputation_score }}</td>
                            <td class="py-2">
                                {{ optional($member->pivot->joined_at)->format('Y-m-d') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-3 text-center text-gray-500">
                                Aucun membre
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if ($myMembership)
                <div class="bg-white p-6 rounded shadow mt-6">
                    <h3 class="text-lg font-bold mb-3">Balances</h3>

                    <div class="text-sm text-gray-600 mb-4">
                        <p><strong>Total dépenses :</strong> {{ number_format($totalSpent, 2) }} MAD</p>
                        <p><strong>Part par membre :</strong> {{ number_format($share, 2) }} MAD</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Membre</th>
                                <th class="text-left py-2">Payé</th>
                                <th class="text-left py-2">Part</th>
                                <th class="text-left py-2">Solde</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($balances as $row)
                                <tr class="border-b">
                                    <td class="py-2">{{ $row['user']->name }}</td>
                                    <td class="py-2">{{ number_format($row['paid'], 2) }} MAD</td>
                                    <td class="py-2">{{ number_format($row['share'], 2) }} MAD</td>
                                    <td class="py-2">
                                        @if ($row['balance'] > 0)
                                            <span class="text-green-700 font-semibold">
                                    +{{ number_format($row['balance'], 2) }} MAD
                                </span>
                                        @elseif ($row['balance'] < 0)
                                            <span class="text-red-700 font-semibold">
                                    {{ number_format($row['balance'], 2) }} MAD
                                </span>
                                        @else
                                            <span class="text-gray-600">0.00 MAD</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Invitation (غير Owner) --}}
            @if ($myMembership && $myMembership->role === 'owner')
                <div class="bg-white p-6 rounded shadow mb-6">
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
                        @error('email')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </form>
                </div>
            @endif
            @if ($myMembership)
                <div class="bg-white p-6 rounded shadow mb-6">
                    <h3 class="font-bold mb-3">Catégories</h3>

                    <form method="POST" action="{{ route('categories.store', $colocation) }}" class="flex gap-3">
                        @csrf
                        <input name="name" required class="border rounded p-2 w-full" placeholder="Ex: Loyer">
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Ajouter</button>
                    </form>

                    @error('name')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror

                    <div class="mt-4">
                        @forelse ($colocation->categories as $cat)
                            <div class="flex items-center justify-between border-b py-2">
                                <span>{{ $cat->name }}</span>

                                <form method="POST" action="{{ route('categories.destroy', $cat) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 text-sm">Supprimer</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-gray-500">Aucune catégorie.</p>
                        @endforelse
                    </div>
                </div>
            @endif

            {{-- Ajouter une dépense (Owner + Member) --}}
            @if ($myMembership)
                <div class="bg-white p-6 rounded shadow">
                    <h3 class="font-bold mb-3">Ajouter une dépense</h3>

                    <form method="POST"
                          action="{{ route('expenses.store', $colocation) }}"
                          class="grid grid-cols-2 gap-3">
                        @csrf

                        <input name="title" placeholder="Titre"
                               class="border p-2 rounded col-span-2" required>

                        <input type="number" step="0.01" name="amount"
                               placeholder="Montant" class="border p-2 rounded" required>

                        <input type="date" name="expense_date"
                               class="border p-2 rounded" required>

                        <select name="category_id"
                                class="border p-2 rounded col-span-2" required>
                            @forelse ($colocation->categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @empty
                                <option disabled selected>Aucune catégorie</option>
                            @endforelse
                        </select>

                        <button
                            class="bg-green-600 text-white px-4 py-2 rounded col-span-2
                                   @if($colocation->categories->isEmpty()) opacity-50 cursor-not-allowed @endif"
                            @if($colocation->categories->isEmpty()) disabled @endif
                        >
                            Ajouter
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>

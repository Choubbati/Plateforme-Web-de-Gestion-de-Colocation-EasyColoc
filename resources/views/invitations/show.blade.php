<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Invitation
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <p class="mb-4">
                    Vous avez été invité à rejoindre la colocation
                    <strong>{{ $invitation->colocation->name }}</strong>.
                </p>

                <div class="flex gap-3">
                    <form method="POST" action="{{ route('invitations.accept', $invitation->token) }}">
                        @csrf
                        <button class="px-4 py-2 bg-green-600 text-white rounded">
                            Accepter
                        </button>
                    </form>

                    <form method="POST" action="{{ route('invitations.refuse', $invitation->token) }}">
                        @csrf
                        <button class="px-4 py-2 bg-red-600 text-white rounded">
                            Refuser
                        </button>
                    </form>
                </div>

                <p class="mt-4 text-sm text-gray-500">
                    Expire le: {{ $invitation->expires_at->format('Y-m-d H:i') }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>

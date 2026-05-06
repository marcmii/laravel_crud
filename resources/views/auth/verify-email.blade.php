<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Gracies per registrar-te. Abans de continuar, verifica el teu correu electronic fent clic a l'enllac que t'hem enviat. Si no l'has rebut, te'n podem enviar un altre.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            Hem enviat un nou enllac de verificacio al teu correu electronic.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    Reenviar correu de verificacio
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Tancar sessio
            </button>
        </form>
    </div>
</x-guest-layout>

<x-layouts.guest-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="flex flex-col justify-center h-screen items-center">

        <div class="main-card w-200">

            <p class="text-2xl font-bold text-center text-green-700 my-6">Password definida com sucesso.</p>

            <div class="text-center">
                <a href="{{ route('home') }}" class="btn"><i class="fa-solid-fa-check-me-2"></i>Voltar</a>
            </div>

        </div>

    </div>

</x-layouts.guest-layout>

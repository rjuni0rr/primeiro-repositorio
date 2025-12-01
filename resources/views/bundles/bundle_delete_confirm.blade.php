<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="main-card overflow-auto">
        <p class="title-2">Eliminar Bundle</p>

        <hr class="my-4">

        <div class="text-center">
            <p class="text-slate-600 mb-4 text-center">Tem a certeza que deseja eliminar este bundle??</p>

            <p class="text-lg text-zinc-600 font-bold mb-4 text-center">{{ $bundle->name }}</p>

            <p class="text-sm text-slate-400 bold mb-4 text-center">{{ $bundle->hash_code }}</p>

            <p class="text-sm text-slate-400 mb-6 text-center">Esta operação é reversivel.</p>
        </div>

        <div class="flex gap-4 justify-center">
            <a href="{{ route('bundle.delete.confirm', ['id' => Crypt::encrypt($bundle->id)]) }}" class="btn-red !px-8">Sim, quero eliminar</a>
            <a href="{{ route('bundle.home') }}" class="btn !px-8">Não</a>
        </div>

    </div>


</x-layouts.auth-layout>

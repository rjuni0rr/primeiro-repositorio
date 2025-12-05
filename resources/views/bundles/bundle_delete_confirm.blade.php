<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle  }}">

    <div class="main-card overflow-auto">

        <p class="title-2">Eliminar bundle</p>

        <hr class="my-4">

        <div class="text-center">

            <p class="text-slate-600 mb-4">Tem a certeza que deseja eliminar este bundle</p>

            <p class="text-lg text-zinc-600 font-bold mb-4">{{ $bundle->name }}</p>

            <p class="text-sm text-slate-400 mb-6">Esta operação é reversível</p>

        </div>

        <div class="flex justify-center gap-4">
            <a href="{{ route('bundles.home') }}" class="btn !px-8">Não</a>
            <a href="{{ route('bundles.delete.confirm', ['id' => Crypt::encrypt($bundle->id)]) }}" class="btn-red !px-8">Sim</a>
        </div>

    </div>

</x-layouts.auth-layout>

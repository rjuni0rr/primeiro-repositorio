<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="main-card overflow-auto">
        <p class="title-2">Eliminar fila de espera</p>

        <hr class="my-4">

        <p class="text-slate-600 mb-4 text-center">Tem a certeza que deseja eliminar a fila de espera?</p>

        <p class="text-lg text-zinc-600 font-bold mb-4 text-center">{{ $queue->name }}</p>

        <p class="text-sm text-slate-400 bold mb-4 text-center">{{ $queue->hash_code }}</p>

        <p class="text-sm text-slate-400 mb-6 text-center">Esta operação é reversivel.</p>

        <div class="flex gap-4 justify-center">
            <a href="{{ route('queue.delete.confirm', ['id' => Crypt::encrypt($queue->id)]) }}" class="btn-red !px-8">Sim, quero eliminar</a>
            <a href="{{ route('home') }}" class="btn !px-8">Não</a>
        </div>

    </div>


</x-layouts.auth-layout>

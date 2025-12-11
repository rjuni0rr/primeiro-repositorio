<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="main-card overflow-auto">

        <div class="flex justify-between items-center">
            <p class="title-3">{{ $queue->description }}</p>
            <p class="title-3">Empresa: <strong>{{ $company->company_name }}</strong></p>
        </div>

        <hr class="my-4">

        <div class="flex gap-6 mb-4">
            <p class="title-3">Serviço: <strong>{{ $queue->service_name }}</strong></p>
            <spa> | </spa>
            <p class="title-3">Balcão: <strong>{{ $queue->service_desk }}</strong></p>
        </div>


        <div class="flex justify-center gap-6">
            <div class="w-1/4 rounded-xl border-1 border-slate-400 text-center p-4">
                <p class="title-3">Última senha chamada:</p>
                @if(empty($lastTicket))
                    <p class="text-slate-400">Não existe senha anterior</p>
                @else
                    <p class="text-6xl font-bold">{{ $lastTicket->queue_ticket_number }}</p>
                @endif
            </div>

            <div class="w-1/4 rounded-xl border-1 border-slate-400 text-center p-4">
                <p class="title-3">Proxima senha:</p>
                @if(empty($nextTicket))
                    <p class="text-slate-400">Não existe senha anterior</p>
                @else
                    <p class="text-6xl font-bold">{{ $nextTicket->queue_ticket_number }}</p>
                @endif
            </div>

            <div class="flex justify-center items-center w-1/4 rounded-xl border-1 border-slate-400 text-center p-4">
                @if(empty($nextTicket))
                    <span class="btn opacity-20 cursor-none !p-4">Chamar</span>
                @else
                    <a href="#" class="btn !p-4 !text-4xl">Chamar</a>
                @endif
            </div>
        </div>

    </div>
</x-layouts.auth-layout>

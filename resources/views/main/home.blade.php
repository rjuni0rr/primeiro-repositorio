<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    @if(session()->has('message'))
        <div class="bg-green-800 text-white p-2 rounded-lg w-full mb-4" id="home_message">
            {{ session('message') }}
        </div>
    @endif

    <div class="main-card overflow-auto">

        <p class="title-2">Filas de espera</p>

        <hr class="mt-2 mb-4">

        <div class="mb-4">
            <a href="#" class="btn"><i class="far fa-plus me-2"></i>Criar nova fila...</a>
        </div>

        @if(@$queues->count() === 0)
            <div class="text-center my-12 text-gray-500">
                <p class="text-lg">Sem registros encontrados</p>
                <p class="text-sm">Adicione novos registros <a href="#" class="link">clicando aqui</a> ou no botão acima</p>
            </div>
        @else
            <table id="tabela">
                <thead class="bg-zinc-700 text-white">
                <tr>
                    <th class="text-xs w-2/14">Nome</th>
                    <th class="text-xs w-2/14">Serviço</th>
                    <th class="text-xs w-2/14">Balcão</th>
                    <th class="text-xs text-center w-1/14">Estado</th>
                    <th class="text-xs text-center w-1/14">Tickets</th>
                    <th class="text-xs text-center w-1/14">Ignorados</th>
                    <th class="text-xs text-center w-1/14">Não atendidos</th>
                    <th class="text-xs text-center w-1/14">Atendidos</th>
                    <th class="text-xs text-center w-1/14">Em espera</th>
                    <th class="text-xs text-center w-2/14">Opções</th>
                </tr>
                </thead>

                <tbody>
                @foreach($queues as $queue)
                    <tr>
                        <td class="border-1 border-slate-300">{{ $queue->name }}</td>
                        <td class="border-1 border-slate-300">{{ $queue->service_name }}</td>
                        <td class="border-1 border-slate-300">{{ $queue->service_desk }}</td>
                        <td class="border-1 border-slate-300">{!! getQueueStateIcon($queue->status) !!}</td>
                        <td class="border-1 border-slate-300">{{ $queue->total_tickets }}</td>
                        <td class="border-1 border-slate-300">{{ $queue->total_dismissed }}</td>
                        <td class="border-1 border-slate-300">{{ $queue->total_not_attended }}</td>
                        <td class="border-1 border-slate-300">{{ $queue->total_called }}</td>
                        <td class="border-1 border-slate-300">{{ $queue->total_waiting }}</td>
                        <td class="border-1 border-slate-300 text-right">
                            <a href="{{ route('queue.details', ['id' => Crypt::encrypt($queue->id)]) }}" class="btn-white" title="Detalhes"><i class="fa-solid fa-bars"></i></a>
                            <a href="#" class="btn-white" title="Editar"><i class="fa-regular fa-pen-to-square"></i></a>
                            <a href="#" class="btn-white" title="Duplicar"><i class="fa-regular fa-clone"></i></a>
                            <a href="#" class="btn-red" title="Deletar"><i class="fa-regular fa-trash-can"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif


    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function (){
            const messageElement = document.querySelector("#home_message");
            if(messageElement){
                setTimeout(()=>{
                    messageElement.remove();
                }, 3000);
            }

            $('#tabela').DataTable({
                language: {
                    url: "{{ asset('assets/datatables/pt-PT.json') }}"
                }
            });
        });
    </script>

</x-layouts.auth-layout>

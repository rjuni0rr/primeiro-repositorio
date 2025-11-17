<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    @if(session()->has('message'))
        <div class="bg-green-800 text-white p-2 rounded-lg w-full mb-4" id="home_message">
            {{ session('message') }}
        </div>
    @endif

    <div class="main-card overflow-auto">

        <table id="tabela">
            <thead class="bg-zinc-700 text-white">
                <tr>
                    <th class="text-xs w-3/14">Nome</th>
                    <th class="text-xs w-3/14">Serviço</th>
                    <th class="text-xs w-2/14">Balcão</th>
                    <th class="text-xs w-1/14">Estado</th>
                    <th class="text-xs text-center w-1/14">Tickets</th>
                    <th class="text-xs text-center w-1/14">Ignorados</th>
                    <th class="text-xs text-center w-1/14">Não atendidos</th>
                    <th class="text-xs text-center w-1/14">Atendidos</th>
                    <th class="text-xs text-center w-1/14">Em espera</th>
                </tr>
            </thead>
            <tbody>
                @foreach($queues as $queue)
                    <tr>
                        <td>{{ $queue->name }}</td>
                        <td>{{ $queue->service_name }}</td>
                        <td>{{ $queue->service_desk }}</td>
                        <td>{{ $queue->status }}</td>
                        <td>{{ $queue->total_tickets }}</td>
                        <td>{{ $queue->total_dismissed }}</td>
                        <td>{{ $queue->total_not_attended }}</td>
                        <td>{{ $queue->total_called }}</td>
                        <td>{{ $queue->total_waiting }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

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

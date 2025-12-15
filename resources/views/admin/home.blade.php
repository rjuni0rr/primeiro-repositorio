<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="main-card overflow-auto">

        <div class="flex justify-between">
            <p class="title-3">Clientes</p>
        </div>

        <hr class="my-4">

        <div class="mb-4">
            <a href="#" class="btn"><i class="far fa-plus me-2"></i>Novo cliente</a>
        </div>

        @if($clients->count() === 0)
            <div class="text-center my-12 text-gray-500">
                <p class="text-lg">Não existem clientes.</p>
                <p class="text-sm">Adicione novos clientes <a href="#" class="link">clicando aqui</a> ou no botão acima</p>
            </div>
        @else
            <table id="table-clients">
                <thead class="bg-zinc-700 text-white">
                <tr>
                    <th class="text-xs">Logo</th>
                    <th class="text-xs">Nome da Empresa</th>
                    <th class="text-xs">Email</th>
                    <th class="text-xs">Telefone</th>
                    <th class="text-xs">Estado</th>
                    <th class="text-xs">Usuários</th>
                    <th class="text-xs">Cliente desde</th>
                    <th class="text-xs"></th>
                </tr>
                </thead>

                <tbody>
                @foreach($clients as $client)
                    <tr class="{{ ($client->status === 'inactive' || $client->deleted_at) ? 'text-red-500' : ''}}">
                        <td class="w-1/16">[logo]</td>
                        <td class="w-1/16">{{ $client->company_name }}</td>
                        <td class="w-1/16">{{ $client->email }}</td>
                        <td class="w-1/16">{{ $client->phone }}</td>
                        <td class="w-1/16 text-center">{!! getClientStatusIcon(($client)) !!}</td>
                        <td class="w-1/16">{{ $client->users_count }}</td>
                        <td class="w-1/16">{{ $client->created_at }}</td>
                        <td class="w-1/16">[ações]</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        @endif

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function (){
            $('#table-clients').DataTable({
                language: {
                    url: "{{ asset('assets/datatables/pt-PT.json') }}"
                }
            });
        });
    </script>


</x-layouts.auth-layout>

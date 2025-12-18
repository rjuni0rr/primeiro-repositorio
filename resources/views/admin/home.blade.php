<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="main-card overflow-auto">

        <div class="flex justify-between">
            <p class="title-3">Clientes</p>
        </div>

        <hr class="my-4">

        <div class="mb-4">
            <a href="{{ route('admin.company.create') }}" class="btn"><i class="far fa-plus me-2"></i>Novo cliente</a>
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
                    <tr class="{{ ($client->status === 'inactive' || $client->deleted_at) ? 'text-red-500 opacity-25' : ''}}">
                        <td class="w-5/100">
                            <img src="{{ getCompanyLogo($client->company_logo) }}" class="w-10 h-10 {{ (($client->status === 'inactive' || $client->deleted_at) ? 'grayscale-100' : '') }}" />
                        </td>
                        <td class="w-20/100">{{ $client->company_name }}</td>
                        <td class="w-20/100"><i class="fa-solid fa-envelope me-2"></i>{{ $client->email }}</td>
                        <td class="w-10/100"><i class="fa-solid fa-phone me-2"></i>{{ $client->phone }}</td>
                        <td class="w-10/100 text-center">{!! getClientStatusIcon(($client)) !!}</td>
                        <td class="w-10/100"><i class="fa-solid fa-users me-2"></i>{{ $client->users_count }}</td>
                        <td class="w-10/100">{{ $client->created_at }}</td>
                        <td class="w-15/100">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.company.details', ['id' => Crypt::encrypt($client->id)]) }}" class="btn" title="Detalhes"><i class="fa-solid fa-circle-info"></i></a>
                            </div>
                        </td>
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

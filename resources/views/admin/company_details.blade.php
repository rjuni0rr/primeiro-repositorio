<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="main-card overflow-auto">

        <div class="flex justify-between">
            <p class="title-3">Detalhes de cliente</p>
            <a href="{{ route('admin.home') }}" class="btn"><i class="fa-solid fa-arrow-left me-2"></i>Voltar</a>
        </div>

        <hr class="my-4">

        {{-- Company Details --}}
        <div class="flex justify-between items-center">
            <p class="title-2">{{ $company->company_name }}</p>
            <p><i class="fa-solid fa-phone me-2"></i>{{ $company->phone }}</p>
            <p><i class="fa-solid fa-envelope me-2"></i>{{ $company->email }}</p>
        </div>

        <hr class="my-4">


        {{-- Users --}}

        <p class="title-3 mb-4">Usuários</p>

        @if($users->count() === 0)
            <div class="text-center my-12 text-gray-500">
                <p class="text-lg">Não existem usuários para este cliente.</p>
            </div>
        @else
            <table id="table-users">
                <thead class="bg-black text-white">
                    <tr>
                        <th class="text-xs">Email</th>
                        <th class="text-xs">Estado</th>
                        <th class="text-xs">Perfil</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="w-50/100">{{ $user->email }}</td>
                            <td class="w-25/100">{!! getUserStatus($user) !!}</td>
                            <td class="w-25/100">{!! GetUserRole($user->role) !!}  </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        @endif


        {{-- Queues --}}

        <p class="title-3 mb-4">Filas de espera</p>
        @if($queues->count() === 0)
            <div class="text-center my-12 text-gray-500">
                <p class="text-lg">Não existem filas para este cliente.</p>
            </div>
        @else
            <table id="table-queues">
                <thead class="bg-black text-white">
                    <tr>
                        <th class="text-xs">Nome</th>
                        <th class="text-xs">Serviço</th>
                        <th class="text-xs">Total</th>
                        <th class="text-xs">Dispensadas</th>
                        <th class="text-xs">Não atendidas</th>
                        <th class="text-xs">Em espera</th>
                        <th class="text-xs">Chamadas</th>

                    </tr>
                </thead>
                <tbody>

                @foreach($queues as $queue)
                    <tr>
                        <td class="w-25/100">{{ $queue->name }}</td>
                        <td class="w-25/100">{{ $queue->service_name }}</td>
                        <td class="w-10/100">{{ $queue->total_tickets }}</td>
                        <td class="w-10/100">{{ $queue->total_dismissed }}</td>
                        <td class="w-10/100">{{ $queue->total_not_attended }}</td>
                        <td class="w-10/100">{{ $queue->total_waiting }}</td>
                        <td class="w-10/100">{{ $queue->total_called }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function (){
            $('#table-users').DataTable({
                language: {
                    url: "{{ asset('assets/datatables/pt-PT.json') }}"
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function (){
            $('#table-queues').DataTable({
                language: {
                    url: "{{ asset('assets/datatables/pt-PT.json') }}"
                }
            });
        });
    </script>

</x-layouts.auth-layout>

<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="main-card overflow-auto">

        <div class="flex justify-between">
            <p class="title-3">Gestão de usuários</p>
            <p class="title-3">Empresa: <strong>{{ Auth::user()->company->company_name }}</strong></p>
        </div>

        <hr class="my-4">

        <div class="mb-4">
            <a href="{{ route('client.admin.create') }}" class="btn"><i class="fa fa-plus me-2"></i>Novo usuário</a>
        </div>


        <table id="table-users">
            <thead class="bg-black text-white">
                <tr>
                    <th class="text-xs">Email</th>
                    <th class="text-xs">Perfil</th>
                    <th class="text-xs">Estado</th>
                    <th class="text-xs">Último login</th>
                    <th class="text-xs">Ações</th>
                </tr>
            </thead>
            @foreach($users as $user)
                <tr>
                    <td class="w-4/12">{{ $user->email }}</td>
                    <td class="w-1/12">{!! getUserRoleIcon($user->role) !!}</td>
                    <td class="w-3/12">{!! getUserCurrentState($user) !!}</td>
                    <td class="w-2/12">{{ $user->last_login ? $user->last_login : 'Login nunca efetuado' }}</td>
                    <td class="w-2/12">
                        @if($user->deleted_at === null)
                            <div class="flex w-full justify-end gap-2">
                                {!! implode("", getUserAvailableActions($user)) !!}
                            </div>
                        @else
                            <div class="flex w-full justify-end gap-2">
                                <a href="{{ route('client.admin.user.restore', ['id' => Crypt::encrypt($user->id)]) }}" class="btn-green" title="Restaurar cliente"><i class="fa-solid fa-rotate-left"></i></a>
                                <a href="{{ route('client.admin.user.perm.delete', ['id' => Crypt::encrypt($user->id)]) }}" class="btn-red" title="Deletar Permanente"><i class="fa-regular fa-trash-can"></i></a>
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
            <tbody>

            </tbody>
        </table>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function (){
            $('#table-users').DataTable({
                language: {
                    url: "{{ asset('assets/datatables/pt-PT.json') }}"
                }
            });
        });
    </script>

</x-layouts.auth-layout>

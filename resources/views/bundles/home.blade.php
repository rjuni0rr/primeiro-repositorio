<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="main-card overflow-auto">

        <div class="flex justify-between items-center">
            <p class="title-2">Bundle de filas</p>
        </div>

        <hr class="my-4">
        <div class="mb-4">
            <a href="{{ route('bundles.create') }}" class="btn"><i class="far fa-plus me-2"></i>Criar novo bundle</a>
        </div>

        @if($bundles->isEmpty())
            <p class="text-slate-400 text-center my-12">Nenhum bundle encontrado.</p>
        @else
            <table id="table-bundles">
                <thead class="bg-black text-white">
                    <tr>
                        <th class="w-[35%]">Nome</th>
                        <th class="w-[20%]">Número de filas</th>
                        <th class="w-[25%]">Credenciais</th>
                        <th class="w-[20%]"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($bundles as $bundle)
                    <tr>
                        <td>{{ $bundle->name }}</td>
                        <td>{{ count(json_decode($bundle->queues)) }}</td>
                        <td>{{ $bundle->credential_username }}</td>
                        <td>[acoes]</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <script>

        $(document).ready(function (){
            $('#table-bundles').DataTable({
                language: {
                    url: "{{ asset('assets/datatables/pt-PT.json') }}"
                }
            });
        });


    </script>

</x-layouts.auth-layout>

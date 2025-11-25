<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="main-card overflow-auto">
        <div class="flex justify-between items-center">
            <p class="title-2">Criar novo bundle</p>
            <a href="{{ route('home') }}" class="btn"><i class="fa-solid fa-arrow-left me-2"></i>Voltar</a>
        </div>

        <hr class="my-4">

        <div class="flex justify-between gap-10">

            <div class="w-full">

                <form action="#" method="post">

                    @csrf

                    <div class="mb-4">
                        <label for="bundle_name" class="label">Nome do bundle</label>
                        <input type="text" id="bundle_name" name="bundle_name" class="input w-full" placeholder="Nome do bundle">
                    </div>

                    <div class="flex justify-between gap-4">
                        <div class="mb-4 w-full">
                            <label for="credential_username" class="label">Credencial username</label>
                            <div class="flex gap-2">
                                <input type="text" id="credential_username" name="credential_username" class="input w-full" placeholder="Credential user">
                                <button id="btn_generate_credential_username" class="btn"><i class="fa-solid fa-rotate"></i></button>
                            </div>
                        </div>

                        <div class="mb-4 w-full">
                            <label for="credential_password" class="label">Credencial senha</label>
                            <div class="flex gap-2">
                                <input type="text" id="credential_password" name="credential_password" class="input w-full" placeholder="Credential password">
                                <button id="btn_generate_credential_password" class="btn"><i class="fa-solid fa-rotate"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="title-3 mb-2">FIlas de espera do bundle</p>
                        <div class="main-card p-4">

                            <p class="text-center text-slate-400">Não existem filas de espera</p>

                        </div>
                    </div>

                    <button type="submit" class="btn"><i class="fa-solid fa-check me-2"></i>Criar bundle</button>

                </form>

            </div>

            <div class="w-full">
                <p class="text-slate-600 font-bold">Filas de espera</p>
                @if($queues->isEmpty())
                    <p class="text-slate-400 text-center mt-12">Não existem filas de espera.</p>
                @else
                    <table id="table-queues">
                        <thead class="bg-black text-white">
                            <tr>
                                <th></th>
                                <th>Nome</th>
                                <th>Serviço</th>
                                <th>Balcão</th>
                                <th>Estado</th>
                                <th>Pré-visualização</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($queues as $queue)
                                <tr>
                                    <td><button class="btn"><i class="fa-solid fa-circle-plus"></i></button></td>
                                    <td>{{ $queue->name }}</td>
                                    <td>{{ $queue->service_name }}</td>
                                    <td>{{ $queue->service_desk }}</td>
                                    <td><span class="me-2">{!! getQueueStateIcon($queue->status) !!}</span>{{ getQueueStateText($queue->status) }}</td>
                                    <td>{!! getQueuePreview($queue) !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

            </div>

        </div>

    </div>

    <script>
        $(document).ready(function (){
            $('#table-queues').DataTable({
                language: {
                    url: "{{ asset('assets/datatables/pt-PT.json') }}"
                }
            });
        });
    </script>

</x-layouts.auth-layout>

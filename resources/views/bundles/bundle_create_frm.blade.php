<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="main-card overflow-auto">
        <div class="flex justify-between items-center">
            <p class="title-2">Criar novo bundle</p>
            <a href="{{ route('bundles.home') }}" class="btn"><i class="fa-solid fa-arrow-left me-2"></i>Voltar</a>
        </div>

        <hr class="my-4">

        <div class="flex justify-between gap-10">

            <div class="w-full">

                <form action="{{ route('bundles.create.submit') }}" method="post">

                    @csrf

                    <input type="hidden" name="queues_list" value="{{ old('queues_list') }}">

                    <div class="mb-4">
                        <label for="bundle_name" class="label">Nome do bundle</label>
                        <input type="text" id="bundle_name" name="bundle_name" class="input w-full" placeholder="Nome do bundle" value="{{ old('bundle_name') }}">
                        {!! showValidationError('bundle_name', $errors) !!}
                    </div>

                    <div class="flex justify-between gap-4">
                        <div class="mb-4 w-full">
                            <label for="credential_username" class="label">Credencial username</label>
                            <div class="flex gap-2">
                                <input type="text" id="credential_username" name="credential_username" class="input w-full" placeholder="Credential user" value="{{ old('credential_username') }}">
                                <button type="button" id="btn_generate_credential_username" class="btn"><i class="fa-solid fa-rotate"></i></button>
                            </div>
                            {!! showValidationError('credential_username', $errors) !!}
                        </div>

                        <div class="mb-4 w-full">
                            <label for="credential_password" class="label">Credencial senha</label>
                            <div class="flex gap-2">
                                <input type="text" id="credential_password" name="credential_password" class="input w-full" placeholder="Credential password" {{ old('credential_password') }}>
                                <button type="button" id="btn_generate_credential_password" class="btn"><i class="fa-solid fa-rotate"></i></button>
                            </div>
                            {!! showValidationError('credential_password', $errors) !!}
                        </div>
                    </div>
                    <div class="mb-4">
                        <p class="title-3 mb-2">Filas de espera do bundle</p>
                        <div class="main-card p-4 !bg-slate-100" id="div_queues"></div>
                        {!! showValidationError('queues_list', $errors) !!}
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
                                    <td><button class="btn" id="btn_queue" data-queue-hash-code="{{ $queue->hash_code }}" data-queue-name="{{ $queue->name }}"><i class="fa-solid fa-circle-plus"></i></button></td>
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

        // queues management
        let queues = getQueueListFromInputHidden();

        renderQueues(queues);

        document.querySelectorAll("#btn_queue").forEach(button => {
            button.addEventListener('click', function () {
                const queueHashCode = this.getAttribute('data-queue-hash-code');
                const queueName = this.getAttribute('data-queue-name');

                // check if the queue already exists in the bundle
                if(queues.some(queue => queue.hash_code === queueHashCode)) {
                    // remove a queue do bundle, considerando que queues e igual a todas as queues do bundle, exceto a que é diferente
                    queues = queues.filter(queue => queue.hash_code !== queueHashCode);
                } else {
                    // check if the limit is ok
                    if (queues.length == 8) {
                        return;
                    }

                    queues.push({
                        'hash_code': queueHashCode,
                        'name': queueName,
                    });
                }

                renderQueues(queues);

            });
        });

        function renderQueues(queues){
            let html = '';
            if (queues.length === 0){
                html = '<p class="text-slate-400 text-center">Não existem filas de espera.</p>';
            } else {
                queues.forEach(queue => {
                    html += '<div class="flex bg-white justify-between items-center p-2 mb-1 rounded-lg border-gray-300">';
                    html += `<span class="font-bold">${queue.name}</span><i class="text-red-500 cursor-pointer fa-regular fa-trash-can" onclick="deleteFromQueue('${queue.hash_code}')"></i>`;
                    html += '</div>';
                });
            }

            document.querySelector('#div_queues').innerHTML = html;

            // update the hidden input with the JSON string of queues
            document.querySelector('input[name="queues_list"]').value = JSON.stringify(queues);

        }

        function deleteFromQueue(hash_code){
            queues = queues.filter(q => q.hash_code !== hash_code)
            renderQueues(queues);
        }

        function getQueueListFromInputHidden() {
            const queueListInput = document.querySelector('input[name="queues_list"]');
            if (queueListInput) {
                try {
                    return JSON.parse(queueListInput.value);
                } catch (e) {
                    return [];
                }
            }
            return [];
        }

        // credential generation
        document.querySelector("#btn_generate_credential_username").addEventListener('click', function () {
            fetch("{{ route('bundles.generate.credential.value', ['num_chars' => 64]) }}")
                .then(response => response.json())
                .then(data => {
                    document.querySelector("#credential_username").value = data.hash;
                })
                .catch(error => console.error('Error: ', error));
        });

        document.querySelector("#btn_generate_credential_password").addEventListener('click', function () {
            fetch("{{ route('bundles.generate.credential.value', ['num_chars' => 64]) }}")
                .then(response => response.json())
                .then(data => {
                    document.querySelector("#credential_password").value = data.hash;
                })
                .catch(error => console.error('Error: ', error));
        });

    </script>

</x-layouts.auth-layout>

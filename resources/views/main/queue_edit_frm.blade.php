<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="main-card overflow-auto">
        <div class="flex justify-between items-center">
            <p class="title-2">Editar fila de espera</p>
            <a href="{{ route('home') }}" class="btn"><i class="fa-solid fa-arrow-left me-2"></i>Voltar</a>
        </div>

        <hr class="my-4">

        <div class="flex gap-4">
            <div class="w-1/2">

                <form action="{{ route('queue.edit.submit') }}" method="POST" novalidate>

                    @csrf

                    <input type="hidden" name="queue_id" value="{{ Crypt::encrypt($queue->id) }}">

                    <div class="mb-4">
                        <label for="name" class="label">Nome da fila</label>
                        <input type="text" name="name" id="name" class="input w-full" placeholder="Nome da fila" value="{{ old('name', $queue->name) }}">
                        {!! showValidationError('name', $errors) !!}
                        {!! showServerError() !!}
                    </div>
                    <div class="mb-4">
                        <label for="description" class="label">Descrição</label>
                        <input type="text" name="description" id="description" class="input w-full" placeholder="Descrição da fila" value="{{ old('description', $queue->description) }}">
                        {!! showValidationError('description', $errors) !!}
                    </div>

                    <div class="flex gap-4 mb-4">
                        <div class="w-1/2">
                            <label for="service" class="label">Serviço</label>
                            <input type="text" name="service" id="service" class="input w-full" placeholder="Serviço" value="{{ old('service', $queue->service_name) }}">
                            {!! showValidationError('service', $errors) !!}
                        </div>
                        <div class="w-1/2">
                            <label for="desk" class="label">Balcão de atendimento</label>
                            <input type="text" name="desk" id="desk" class="input w-full" placeholder="Balcão de atendimento" value="{{ old('desk', $queue->service_desk) }}">
                            {!! showValidationError('desk', $errors) !!}
                        </div>
                    </div>

                    <div class="flex gap-4 mb-4">

                        <div class="w-full">
                            <label for="prefix" class="label">Prefixo</label>
                            <select name="prefix" id="prefix" class="input w-full">
                                <option value="{{ old('prefix', $queue->queue_prefix) }}" {{ $queue->queue_prefix === '-' ? 'selected' : '' }}>Sem prefixo</option>
                                @php
                                    $prefixes = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
                                    $queuePrefix = old('prefix', $queue->queuePrefix);
                                @endphp
                                @foreach($prefixes as $prefix)
                                    <option value="{{ $prefix }}" {{ $prefix === $queue->queuePrefix ? 'selected' : '' }}>{{ $prefix }}</option>
                                @endforeach
                            </select>
                            {!! showValidationError('prefix', $errors) !!}
                        </div>


                        <div class="w-full">
                            <label for="status" class="label">Estado</label>
                            @php
                                $queueStatus = old('status', $queue->status)
                            @endphp

                            <select name="status" id="status" class="input w-full">
                                <option value="active" {{ $queueStatus === 'active' ? 'selected' : '' }}>Ativo</option>
                                <option value="inactive" {{ $queueStatus === 'inactive' ? 'selected' : '' }}>Inativo</option>
                                <option value="done" {{ $queueStatus === 'done' ? 'selected' : '' }}>Finalizada</option>
                            </select>
                            {!! showValidationError('status', $errors) !!}
                        </div>

                    </div>

                    <div class="mb-4">
                        <p class="label">Código de hash</p>
                        <div class="flex gap-2">
                            <p class="input bg-slate-100 w-full">{{ $queue->hash_code }}</p>
                        </div>
                    </div>

                    <div class="main-card flex !p-4 mb-4">

                        <div class="w-1/2">
                            <div class="mb-4">
                                <label class="label">Prefíxo - Cor de fundo</label>
                                <input type="text" class="input text-zinc-900" name="color_1" id="color_1" value="{{ old('color_1', $queueColors['prefix_bg_color']) }}">
                                {!! showValidationError('color_1', $errors) !!}
                            </div>
                            <div>
                                <label class="label">Prefíxo - Cor de texto</label>
                                <input type="text" class="input text-zinc-900" name="color_2" id="color_2" value="{{ old('color_2', $queueColors['prefix_text_color']) }}">
                                {!! showValidationError('color_2', $errors) !!}
                            </div>
                        </div>

                        <div class="w-1/2">
                            <div class="mb-4">
                                <label class="label">Número - Cor de fundo</label>
                                <input type="text" class="input text-zinc-900" name="color_3" id="color_3" value="{{ old('color_3', $queueColors['number_bg_color']) }}">
                                {!! showValidationError('color_3', $errors) !!}
                            </div>
                            <div>
                                <label class="label">Número - Cor de texto</label>
                                <input type="text" class="input text-zinc-900" name="color_4" id="color_4" value="{{ old('color_4', $queueColors['number_text_color']) }}">
                                {!! showValidationError('color_4', $errors) !!}
                            </div>
                        </div>

                    </div>

                    <button type="submit" class="btn"><i class="fa-solid fa-check me-2"></i>Atualizar fila</button>

                </form>

            </div>

            <div class="flex w-1/2 justify-center items-center">
                <div id="color_preview" class="flex main-card !bg-slate-200">
                    <p id="example_prefix" class="rounded-tl-2xl rounded-bl-2xl text-center text-9xl font-bold p-6" style="background-color: #0d3561; color: #fff">A</p>
                    <p id="example_number" class="rounded-tr-2xl rounded-br-2xl text-center text-9xl font-bold p-6" style="background-color: #adb4b9; color: #011020">01</p>
                </div>
            </div>

        </div>

    </div>

    <script>

        const fixedColors = [
            '#ff0000',
            '#660000',
            '#0000ff',
            '#000066',
            '#00ff00',
            '#006600',
            '#ffa800',
            '#aa6600',
            '#ffff00',
            '#666600',
            '#000000',
            '#ffffff',
        ];

        // Add coloris
        Coloris({el: '#color_1', alpha: false, swatches: fixedColors, defaultColor: '{{ old('color_1', $queueColors['prefix_bg_color']) }}'});
        Coloris({el: '#color_1', alpha: false, swatches: fixedColors, defaultColor: '{{ old('color_2', $queueColors['prefix_text_color']) }}'});
        Coloris({el: '#color_1', alpha: false, swatches: fixedColors, defaultColor: '{{ old('color_3', $queueColors['number_bg_color']) }}'});
        Coloris({el: '#color_1', alpha: false, swatches: fixedColors, defaultColor: '{{ old('color_4', $queueColors['number_text_color']) }}'});

        // inputs
        const prefix = document.querySelector("#prefix");
        const total_digits = {{ $queue->queue_total_digits }};
        const color1 = document.querySelector("#color_1");
        const color2 = document.querySelector("#color_2");
        const color3 = document.querySelector("#color_3");
        const color4 = document.querySelector("#color_4");

        // ticket preview elements
        const example_prefix = document.querySelector("#example_prefix");
        const example_number = document.querySelector("#example_number");

        function UpdateTicketPreview() {
            const ticketProperties = {
                hasPrefix: prefix.value !== '-',
                prefix: prefix.value,
                totalDigits: total_digits,
                prefixBackgroundColor: color1.value,
                prefixTextColor: color2.value,
                numberBackgroundColor: color3.value,
                numberTextColor: color4.value,
            };

            // update prefix
            if(ticketProperties.hasPrefix){
                example_prefix.textContent = ticketProperties.prefix;
                example_prefix.style.backgroundColor = ticketProperties.prefixBackgroundColor;
                example_prefix.style.color = ticketProperties.prefixTextColor;
                example_prefix.classList.remove('hidden');
            } else {
                example_prefix.classList.add('hidden');
            }

            // update number
            example_number.textContent = String(1).padStart(ticketProperties.totalDigits, '0');
            example_number.style.backgroundColor = ticketProperties.numberBackgroundColor;
            example_number.style.color = ticketProperties.numberTextColor;

        }

        prefix.addEventListener('change', UpdateTicketPreview);
        color1.addEventListener('change', UpdateTicketPreview);
        color2.addEventListener('change', UpdateTicketPreview);
        color3.addEventListener('change', UpdateTicketPreview);
        color4.addEventListener('change', UpdateTicketPreview);

        // initial call
        UpdateTicketPreview();

    </script>
</x-layouts.auth-layout>

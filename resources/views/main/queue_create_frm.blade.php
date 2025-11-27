<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">
{{--    @php--}}
{{--        if(session()->has('errors')){--}}
{{--            dd(session('errors'));--}}
{{--        }--}}
{{--    @endphp--}}

    <div class="main-card overflow-auto">
        <div class="flex justify-between items-center">
            <p class="title-2">Criar nova fila de espera</p>
            <a href="{{ route('home') }}" class="btn"><i class="fa-solid fa-arrow-left me-2"></i>Voltar</a>
        </div>

        <hr class="my-4">

        <div class="flex gap-4">

            <div class="w-1/2">

                <form action="{{ route('queue.create.submit') }}" method="POST" novalidate>

                    @csrf

                    <input type="hidden" name="hidden_hash_code" value="">

                    <div class="mb-4">
                        <label for="name" class="label">Nome da fila</label>
                        <input type="text" name="name" id="name" class="input w-full" placeholder="Nome da fila" value="{{ old('name') }}">
                        {!! showValidationError('name', $errors) !!}
                        {!! showServerError() !!}
                    </div>

                    <div class="mb-4">
                        <label for="description" class="label">Descrição</label>
                        <input type="text" name="description" id="description" class="input w-full" placeholder="Descrição da fila" value="{{ old('description') }}">
                        {!! showValidationError('description', $errors) !!}
                    </div>

                    <div class="flex gap-4 mb-4">
                        <div class="w-1/2">
                            <label for="service" class="label">Serviço</label>
                            <input type="text" name="service" id="service" class="input w-full" placeholder="Serviço" value="{{ old('service') }}">
                            {!! showValidationError('service', $errors) !!}
                        </div>

                        <div class="w-1/2">
                            <label for="desk" class="label">Balcão de atendimento</label>
                            <input type="text" name="desk" id="desk" class="input w-full" placeholder="Balcão de atendimento" value="{{ old('desk') }}">
                            {!! showValidationError('desk', $errors) !!}
                        </div>
                    </div>

                    <div class="flex gap-4 mb-4">

                        <div class="w-full">
                            <label for="prefix" class="label">Prefixo</label>
                            <select name="prefix" id="prefix" class="input w-full">
                                <option value="-">Sem prefixo</option>
                                @php
                                    $prefixes = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ')
                                @endphp
                                @foreach($prefixes as $prefix)
                                    <option value="{{ $prefix }}" {{ $prefix === 'A' ? 'selected' : '' }}>{{ $prefix }}</option>
                                @endforeach
                            </select>
                            {!! showValidationError('prefix', $errors) !!}
                        </div>

                        <div class="w-full">
                            <label for="total_digits" class="label">Total de dígitos</label>
                            <select name="total_digits" id="total_digits" class="input w-full">
                                <option value="2">00</option>
                                <option value="3">000</option>
                                <option value="4">0000</option>
                            </select>
                            {!! showValidationError('total_digits', $errors) !!}
                        </div>

                        <div class="w-full">
                            <label for="status" class="label">Estado</label>
                            <select name="status" id="status" class="input w-full">
                                <option value="active">Ativo</option>
                                <option value="inactive">Inativo</option>
                            </select>
                            {!! showValidationError('status', $errors) !!}
                        </div>

                    </div>

                    <div class="mb-4">
                        <p class="label">Código de hash</p>
                        <div class="flex gap-2">
                            <p class="input bg-slate-100 w-full" id="hash_code">&nbsp; </p>
                            <button type="button" class="btn" id="btn_hash_code"><i class="fa-solid fa-rotate"></i></button>
                        </div>
                        {!! showValidationError('hidden_hash_code', $errors) !!}
                    </div>

                    <div class="main-card flex !p-4 mb-4">

                        <div class="w-1/2">
                            <div class="mb-4">
                                <label class="label">Prefíxo - Cor de fundo</label>
                                <input type="text" class="input text-zinc-900" name="color_1" id="color_1" value="{{ old('color_1', '#0d3561') }}">
                                {!! showValidationError('color_1', $errors) !!}
                            </div>
                            <div>
                                <label class="label">Prefíxo - Cor de texto</label>
                                <input type="text" class="input text-zinc-900" name="color_2" id="color_2" value="{{ old('color_2', '#ffffff') }}">
                                {!! showValidationError('color_2', $errors) !!}
                            </div>
                        </div>

                        <div class="w-1/2">
                            <div class="mb-4">
                                <label class="label">Número - Cor de fundo</label>
                                <input type="text" class="input text-zinc-900" name="color_3" id="color_3" value="{{ old('color_3', '#adb4b9') }}">
                                {!! showValidationError('color_3', $errors) !!}
                            </div>
                            <div>
                                <label class="label">Número - Cor de texto</label>
                                <input type="text" class="input text-zinc-900" name="color_4" id="color_4" value="{{ old('color_4', '#011020') }}">
                                {!! showValidationError('color_4', $errors) !!}
                            </div>
                        </div>

                    </div>

                    <button type="submit" class="btn"><i class="fa-solid fa-check me-2"></i>Criar nova fila</button>

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
        Coloris({el: '#color_1', alpha: false, swatches: fixedColors, defaultColor: '{{ old('color_1', '#0d3561') }}'});
        Coloris({el: '#color_2', alpha: false, swatches: fixedColors, defaultColor: '{{ old('color_2', '#ffffff') }}'});
        Coloris({el: '#color_3', alpha: false, swatches: fixedColors, defaultColor: '{{ old('color_3', '#adb4b9') }}'});
        Coloris({el: '#color_4', alpha: false, swatches: fixedColors, defaultColor: '{{ old('color_4', '#0d3561') }}'});

        // inputs
        const prefix = document.querySelector("#prefix");
        const total_digits = document.querySelector("#total_digits");
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
                totalDigits: parseInt(total_digits.value),
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

        UpdateTicketPreview();

        prefix.addEventListener('change', UpdateTicketPreview);
        total_digits.addEventListener('change', UpdateTicketPreview);
        color1.addEventListener('change', UpdateTicketPreview);
        color2.addEventListener('change', UpdateTicketPreview);
        color3.addEventListener('change', UpdateTicketPreview);
        color4.addEventListener('change', UpdateTicketPreview);

        function getHashCode (){
            fetch("{{ route('queue.generate.hash') }}")
                .then(response => response.json())
                .then(data => {
                    document.querySelector("#hash_code").textContent = data.hash;
                    document.querySelector('input[name="hidden_hash_code"]').value = data.hash;
                })
                .catch(error =>{
                    alert('Aconteceu um erro na criação do código de hash. Por favor, tente novamente.')
                })
        }
        getHashCode();

        // refresh hash code
        document.querySelector("#btn_hash_code").addEventListener('click', getHashCode);

    </script>

</x-layouts.auth-layout>

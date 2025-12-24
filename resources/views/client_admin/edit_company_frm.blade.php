<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="main-card overflow-auto">

        <div class="flex justify-between">
            <p class="title-3">Dados da empresa</p>
            <a href="{{ route('client.admin.home') }}" class="btn"><i class="fa-solid fa-arrow-left me-2"></i>Voltar</a>
        </div>

        <hr class="my-4">

        {{--    enctype serve para permitir que o form envie imagens   --}}
        <form action="{{ route('client.admin.company.edit.submit') }}" method="POST" enctype="multipart/form-data" novalidate>

            @csrf

            <div class="flex gap-6">

                {{-- company logo --}}
                <div class="main-card w-1/3">
                    <label for="company_logo" class="label">Logo do cliente</label>
                    <input type="file" name="company_logo" id="company_logo" class="input" />
                    <p class="text-sm text-red-500 italic" id="error_message"></p>
                    {!! showValidationError('company_logo', $errors) !!}

                    <div class="flex justify-center mt-8">
                        <img src="#" alt="Logo" id="logo_preview" class="hidden w-[200] h-[200] border-1 border-slate-300">
                    </div>

                </div>

                <div class="main-card w-2/3">

                    <div class="mb-4">
                        <label for="company_name" class="label">Nome da empresa</label>
                        <input type="text" name="company_name" id="company_name" class="input w-full" value="{{ old('company_name') }}"/>
                        {!! showValidationError('company_name', $errors) !!}
                        {!! showServerError() !!}
                    </div>

                    <div class="mb-4">
                        <label for="address" class="label">Endereço</label>
                        <input type="text" name="address" id="address" class="input w-full" value="{{ old('address') }}"/>
                        {!! showValidationError('address', $errors) !!}
                    </div>

                    <div class="flex gap-4 mb-4">

                        <div class="w-1/3">
                            <label for="phone" class="label">Telefone</label>
                            <input type="text" name="phone" id="phone" class="input w-full" value="{{ old('phone') }}"/>
                            {!! showValidationError('phone', $errors) !!}
                        </div>

                        <div class="w-2/3">
                            <label for="email" class="label">Email</label>
                            <input type="email" name="email" id="email" class="input w-full" value="{{ old('email') }}"/>
                            {!! showValidationError('email', $errors) !!}
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-2"></i>Atualizar dados</button>

                </div>

            </div>

        </form>

    </div>

    <script>

        document.querySelector("#company_logo").addEventListener('change', function (event){

            const error_message = document.querySelector("#error_message")
            error_message.textContent = '';

            const [file] = event.target.files;
            const preview = document.querySelector('#logo_preview');

            if (file){
                // valida todo o arquivo e remove todos os pontos exceto o ultimo
                const validTypes = ['image/png', 'image/jpeg'];
                const validExtensions = ['png', 'jpg', 'jpeg'];
                const fileType = file.type;
                const fileExtension = file.name.split('.').pop().toLowerCase();

                // verifica se tudo está correto
                if (!validTypes.includes(fileType) || !validExtensions.includes(fileExtension)){

                    error_message.textContent = "Selecione uma imagem PNG ou JPG.";
                    event.target.value = '';
                    preview.src = "#";
                    preview.classList.add('hidden');

                    return;
                }

                // cria uma nova imagem
                const img = new Image();
                img.onload = function () {

                    // verifica o tamanho da imagem
                    if (img.width === 200 && img.height === 200) {

                        preview.src = URL.createObjectURL(file);
                        preview.classList.remove('hidden');

                    } else {

                        error_message.textContent = "A imagem deve ter exatamente um tamanho de 200x200 pixels.";
                        event.target.value = '';
                        preview.src = "#";
                        preview.classList.add('hidden');

                    }

                };

                img.src = URL.createObjectURL(file);
            } else {

                preview.src = "#";
                preview.classList.add('hidden');

            }

        });

    </script>
</x-layouts.auth-layout>

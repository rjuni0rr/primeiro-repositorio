<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="main-card overflow-auto">

        <div class="flex justify-between">
            <p class="title-3">Novo usuário</p>
            <a href="{{ route('client.admin.home') }}" class="btn"><i class="fa fa-arrow-left mr-2"></i>Voltar</a>
        </div>

        <hr class="my-4">

        <div class="main-card w-200">

            <form action="{{ route('client.admin.create.submit') }}" method="post" novalidate>

                @csrf

                <div class="mb-4">
                    <label for="email" class="label">Email</label>
                    <input type="email" name="email" id="email" class="input w-full" value="{{ old('email') }}">
                    {!! showValidationError('email', $errors) !!}
                    {!! showServerError() !!}
                </div>

                <div class="mb-4">
                    <label for="role" class="label">Perfil</label>
                    <select name="role" id="role" class="input w-full">
                        <option value="client-admin" {{ old('role') === 'client-admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="client-user" {{ old('role', 'client-user') === 'client-user' ? 'selected' : '' }}>Usuário</option>
                    </select>
                    {!! showValidationError('role', $errors) !!}
                </div>

                <button type="submit" class="btn"><i class="fa fa-check mr-2"></i>Criar usuário</button>

            </form>

        </div>

    </div>

</x-layouts.auth-layout>

<x-layouts.guest-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">
    <div class="flex flex-col justify-center h-screen items-center">

        <div class="main-card w-150">

            <div class="flex justify-center items-center mb-8">
                <img src="{{ asset('assets/images/favicon.png') }}" class="w-10 h-10 me-2" alt="Logo">
                <h3 class="text-3xl uppercase">{{ config('app.name') }}</h3>
            </div>

            <div class="text-center mb-8">
                <strong>{{ $user->email }}</strong>, <br>para conclusão do registro, por favor, defina a sua senha de acesso.
            </div>


            <form action="{{ route('define.password.submit') }}" method="post" novalidate>

                @csrf

                <div class="mb-4">
                    <label for="password" class="label">Definir senha</label>
                    <input type="password" class="input w-full" id="password" name="password" placeholder="Definir Senha">
                    {!! showValidationError('password', $errors) !!}
                    {!! showServerError() !!}
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="label">Repetir Senha</label>
                    <input type="password" class="input w-full" id="password_confirmation" name="password_confirmation" placeholder="Repetir Senha">
                    {!! showValidationError('password_confirmation', $errors) !!}
                </div>

                <div class="text-center mb-4">
                    <button type="submit" class="btn w-full">Definir senha</button>
                </div>

            </form>

        </div>

    </div>
</x-layouts.guest-layout>

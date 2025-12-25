<x-layouts.guest-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="flex flex-col justify-center h-screen items-center">

        <div class="main-card w-100">
            <div class="flex justify-center items-center mb-8">
                <img src="{{ asset('assets/images/favicon.png') }}" class="w-10 h-10 me-2" alt="Logo">
                <h3 class="text-3xl uppercase">{{ config('app.name') }}</h3>
            </div>

            <form action="{{ route('recover.password.submit') }}" method="post" novalidate>

                @csrf

                <p class="text-center my-6">Para recuperar a sua senha, por favor, indique o seu email de usuário.</p>

                <div class="mb-4">
                    <label for="username" class="label">Email de usuário</label>
                    <input type="email" class="input w-full" id="username" name="username" placeholder="Usuário" value="{{ old('username') }}">
                    {!! showValidationError('username', $errors) !!}
                    {!! showServerError() !!}
                </div>

                <div class="text-center mb-4">
                    <button type="submit" class="btn w-full">Recuperar senha</button>
                </div>

            </form>

            <div class="text-center">
                Lembrou da senha? <a href="{{ route('login') }}" class="link">Clique aqui</a>
            </div>

        </div>

    </div>

</x-layouts.guest-layout>

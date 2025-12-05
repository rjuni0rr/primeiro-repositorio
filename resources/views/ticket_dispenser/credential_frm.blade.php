<x-layouts.guest-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="flex flex-col justify-center h-screen items-center">

        <div class="flex items-center mb-4">
            <img src="{{ asset('assets/images/ticket_dispenser_logo.png') }}" class="w-20 h-20">
            <p class="text-5xl font-bold text-slate-600">Dispensador</p>
        </div>

        <div class="main-card w-200">

            <form action="{{ route('dispenser.credentials.submit') }}" method="post" class="flex flex-col gap-4">

                @csrf

                <div class="mb-2">
                    <label for="credential_username" class="label">Credential username</label>
                    <input type="text" class="input w-full" name="credential_username" id="credential_username" value="{{ old('credential_username') }}">
                    {!! showValidationError('credential_username', $errors) !!}
                    {!! showServerError() !!}
                </div>

                <div class="mb-2">
                    <label for="credential_password" class="label">Credential password</label>
                    <input type="password" class="input w-full" name="credential_password" id="credential_password">
                    {!! showValidationError('credential_password', $errors) !!}
                </div>

                <button type="submit" class="btn"><i class="fa-solid fa-up-right-from-square me-2"></i>Apresentar filas</button>

            </form>

        </div>

    </div>

</x-layouts.guest-layout>

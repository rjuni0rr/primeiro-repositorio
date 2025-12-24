<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}" flatpickr>

    <div class="main-card overflow-auto">

        <div class="flex flex-col gap-6 my-10">
            <p class="text-center">Indique qual é a data e hora até à qual pertende manter a <strong>conta bloqueada</strong> para o usuário</p>
            <p class="text-center font-bold text-2xl text-blue-500">{{ $user->email }}</p>
        </div>

        <form action="{{ route('client.admin.user.block.submit') }}" method="post" novalidate>

            @csrf

            <input type="hidden" name="user_id" value="{{ Crypt::encrypt($user->id) }}">

            <div class="text-center mb-10">
                <input type="text" class="input w-75 text-center text-xl" name="blocked_until" id="blocked_until" value="{{ old('blocked_until', now()->format('Y-m-d H:i')) }}" placeholder="Indique a data e hora do bloqueio">
                {!! showValidationError('blocked_until', $errors) !!}
            </div>

            <div class="flex justify-center mb-10 gap-4">
                <a href="{{ route('client.admin.home') }}" class="btn !px-8"><i class="fa fa-times me-2"></i>Cancelar</a>
                <button type="submit" class="btn-red" id="btn_block_user !px-8"><i class="fa fa-lock mr-2"></i>Bloquear Conta</button>
            </div>

        </form>

    </div>
    <script>
        flatpickr('#blocked_until', {
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
            minDate: "today",
            time_24hr: true,
            locale: 'pt'
        });
    </script>


</x-layouts.auth-layout>

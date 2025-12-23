<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="main-card overflow-auto">

        <div class="flex flex-col my-12">

            <p class="text-center mb-8">Tem certeza que deseja forçar a definição de senha para o usuário</p>
            <p class="text-center text-3xl font-bold text-blue-400 mb-8">{{ $user->email }}</p>

            <div class="flex justify-center gap-4">
                <a href="{{ route('client.admin.home') }}" class="btn !px-8"><i class="fa fa-times me-2"></i>Não</a>
                <a href="{{ route('client.admin.user.password.reset.confirm', ['id' => Crypt::encrypt($user->id)]) }}" class="btn !px-8"><i class="fa fa-check me-2"></i>Sim</a>
            </div>

        </div>

    </div>

</x-layouts.auth-layout>

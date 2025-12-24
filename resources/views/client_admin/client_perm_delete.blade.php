<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="flex justify-center">
        <div class="main-card w-200 mt-12">
            <div class="text-center p-6">

                <i class="fa-solid fa-triangle-exclamation text-6xl text-red-600 mb-4"></i>
                <p class="title-2 mb-4">Confirmar eliminação permanente do usuário</p>
                <p class="mb-4">Tem certeza que deseja eliminar o usuário <strong>{{ $user->email }}</strong></p>
                <p class="text-sm text-red-600 mb-6">Esta operação é IRREVERSIVEL</p>

                <div class="flex justify-center gap-4">
                    <a href="{{ route('client.admin.home') }}" class="btn"><i class="fa-solid fa-arrow-left me-2"></i>Cancelar</a>
                    <a href="{{ route('client.admin.user.perm.delete.confirm', ['id' => Crypt::encrypt($user->id)]) }}" class="btn-red"><i class="fa-solid fa-trash me-2"></i>Eliminar PERMANENTEMENTE</a>
                </div>
            </div>
        </div>
    </div>


</x-layouts.auth-layout>

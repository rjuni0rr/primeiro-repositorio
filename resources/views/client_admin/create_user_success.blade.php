<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle  }}">

    <div class="main-card overflow-auto">

        <p class="text-3xl font-bold text-center text-green-700 my-6">Novo usuário adicionado com sucesso.</p>
        <p class="text-center text-slate-600 mb-6">Foi enviado um email para <strong>{{ $email }}</strong> com link para conclusão do registro.</p>

        <div class="text-center">
            <a href="{{ route('client.admin.home') }}" class="btn"><i class="fa-solid fa-check me-2"></i>Voltar</a>
        </div>

    </div>

</x-layouts.auth-layout>

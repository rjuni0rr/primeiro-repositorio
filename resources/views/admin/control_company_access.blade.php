<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="main-card overflow-auto">

        <div class="flex justify-between">
            <p class="title-3">{{ $company->status === 'active' ? 'Desativar' : 'Ativar' }} acesso do cliente</p>
            <a href="{{ route('admin.home') }}" class="btn"><i class="fa-solid fa-arrow-left me-2"></i>Voltar</a>
        </div>

        <hr class="my-4">

        <form action="{{ route('admin.company.control.access.submit') }}" method="POST" novalidate>

            @csrf
            <input type="hidden" name="id" value="{{ Crypt::encrypt($company->id) }}">

            @if($company->status === 'active')

                <input type="hidden" name="action" value="disable">

                <p class="mb-4 text-center">Tem a certeza que pretende desabilitar o acesso do cliente <strong>{{ $company->company_name }}</strong>?</p>
                <p class="mb-4 text-red-600 text-center">Ao desabilitar o acesso, todos os usuários associados a este cliente serão desativados e não poderão mais acessar o sistema.</p>
                <div class="text-center">
                    <button type="submit" class="btn btn-danger"><i class="fa-solid fa-user-slash me-2"></i>Desabilitar acesso</button>
                </div>

            @else

                <input type="hidden" name="action" value="enable">

                <p class="mb-4 text-center">Tem a certeza que pretende habilitar o acesso do cliente <strong>{{ $company->company_name }}</strong>?</p>
                <p class="mb-4 text-green-600 text-center">Ao habilitar o acesso, todos os usuários associados a este cliente serão ativados e poderão acessar o sistema.</p>
                <div class="text-center">
                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-user-check me-2"></i>Habilitar acesso</button>
                </div>

            @endif
        </form>
    </div>

</x-layouts.auth-layout>

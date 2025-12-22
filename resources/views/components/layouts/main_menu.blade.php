<div class="flex p-4 px-8 bg-cyan-700">

    <div class="flex gap-4 text-lg">
        @can('sys-admin')

            <a href="{{ route('admin.home') }}" class="btn-white"><i class="fa-solid fa-house me-2"></i>Clientes</a>
            <a href="{{ route('admin.statistics') }}" class="btn-white"><i class="fa-solid fa-chart-column me-2"></i>Estatísticas</a>

        @endcan

        @canany(['client-admin', 'client-user'])

            {!! getCompanyLogoImage(Auth::user()->company->company_logo, 8) !!}

            <a href="{{ route('home') }}" class="btn-white"><i class="fa-solid fa-house me-2"></i>Gestão de filas</a>
            <a href="{{ route('bundles.home') }}" class="btn-white"><i class="fa-solid fa-table-list me-2"></i>Gestão de bundles</a>
            <a href="{{ route('dispenser') }}" class="btn-white" target="_blank"><i class="fa-regular fa-copy me-2"></i>Dispensador</a>
            <a href="{{ route('queues.display') }}" class="btn-white" target="_blank"><i class="fa-solid fa-tv me-2"></i>Apresentador</a>
            <a href="{{ route('caller.home') }}" class="btn-white" target="_blank"><i class="fa-solid fa-share-from-square me-2"></i>Chamadas</a>

            @can('client-admin')
                {{-- funcionalidades do client admin --}}
            @endcan

        @endcanany

    </div>

</div>

<x-layouts.guest-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="main-card m-6">

        <div class="flex justify-center mb-8">
            <img src="{{ asset('assets/images/favicon.png') }}" class="w-10 h-10 me-2" alt="Logo">
            <h3 class="text-3xl uppercase">{{ config('app.name') }}</h3>
        </div>

        <div class="flex justify-between items-center">
            <p class="title-3 mb-4">Changelog</p>
            <a href="{{ route('login') }}" class="btn"><i class="fa fa-arrow-left me-2"></i>Voltar</a>
        </div>

        <hr class="mb-2 mb-4">

        <div class="flex justify-center">
            <div class="w-300 p-6">
                @foreach($changelog as $key=>$log)

                    <div class="flex items-end gap-4 mb-4">
                        <p class="text-xl font-bold">v.{{ $key }}</p>
                        <p class="text-sm italic">{{ $log['date'] }}</p>
                    </div>

                    @foreach($log['items'] as $item)
                        <div class="mb-4 ms-8">

                            @switch($item['type'])
                                @case('fix')
                                    <x-changelog.change-log-fix :item="$item" />
                                    @break
                                @case('new')
                                    <x-changelog.change-log-new :item="$item" />
                                    @break
                                @case('refactor')
                                    <x-changelog.change-log-refactor :item="$item" />
                                    @break
                                @case('security')
                                    <x-changelog.change-log-security :item="$item" />
                                    @break
                                @default
                                    <x-changelog.change-log-new :item="$item" />
                            @endswitch

                        </div>

                    @endforeach

                    <hr class="my-4">


                @endforeach
            </div>
        </div>

    </div>
</x-layouts.guest-layout>

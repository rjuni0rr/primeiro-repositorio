<x-layouts.guest-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="flex flex-col justify-center p-4">

        <div class="text-end mb-4">
            [opções]
        </div>

        <div class="main-card flex gap-4 w-full">

            <div class="flex flex-wrap w-full border-1 border-slate-300 rounded-xl">

                <div class="w-1/2 p-4"><div class="main-card">Fila de espera 1</div></div>
                <div class="w-1/2 p-4"><div class="main-card">Fila de espera 2</div></div>
                <div class="w-1/2 p-4"><div class="main-card">Fila de espera 3</div></div>
                <div class="w-1/2 p-4"><div class="main-card">Fila de espera 4</div></div>
                <div class="w-1/2 p-4"><div class="main-card">Fila de espera 5</div></div>
                <div class="w-1/2 p-4"><div class="main-card">Fila de espera 6</div></div>
                <div class="w-1/2 p-4"><div class="main-card">Fila de espera 7</div></div>
                <div class="w-1/2 p-4"><div class="main-card">Fila de espera 8</div></div>
            </div>

            <div class="flex w-1/4 h-100 border-1 border-slate-300 rounded-xl p-4">
                preview to ticket
            </div>
        </div>
    </div>

</x-layouts.guest-layout>

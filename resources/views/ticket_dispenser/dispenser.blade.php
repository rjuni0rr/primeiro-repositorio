<x-layouts.guest-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="flex flex-col justify-center p-4">

        <div class="text-end mb-4">
            [opções]
        </div>

        <div class="main-card flex gap-4 w-full">
            <div id="queues" class="flex flex-wrap w-full border-1 border-slate-300 rounded-xl"></div>

            <div class="flex w-1/4 h-100 border-1 border-slate-300 rounded-xl p-4">
                [preview to ticket]
            </div>
        </div>

    </div>
    <script>
        const url = "{{ route('dispenser.get.bundle.data', ['credential' => $credential]) }}";
        // const queuesContainer = document.querySelector("#queues");


        async function getBundleData(url){

            try {
                const response = await fetch(url);
                if (!response.ok){
                    throw new Error('Erro ao buscar dados: ' + response.status);
                }
                return await response.json();
            } catch (error){
                console.error(error);
                return null;
            }
        }

        getBundleData(url)
            .then(data => {
                console.log(data);
            });
    </script>
</x-layouts.guest-layout>

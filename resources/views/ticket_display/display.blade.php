<x-layouts.guest-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="flex flex-col justify-center p-4">

        <div class="flex justify-end mb-4">

            <p id="access_options" class="w-10 h-10"></p>

            <i id="btn_options" class="fa-solid fa-gear btn-white p-2 !hidden"></i>
        </div>

        <div class="main-card flex gap-4 w-full">

            <div id="queues" class="flex flex-wrap p-2 w-full border-1 border-slate-300 rounded-xl">
                [filas de espera]
            </div>

        </div>

        {{--    modal    --}}
        <div id="modal" class="fixed inset-0 z-50 flex items-center justify-center bg-[#000000aa]" style="display: none;">

            <div class="bg-white rounded-xl shadow-lg p-6 w-200 hover">

                <p class="title-3">Opções do apresentador</p>

                <hr class="border-slate-300 my-4">

                <div class="flex justify-between gap-4 my-10">

                    <div class="main-card w-full !p-4">
                        <p class="text-slate-500 text-sm mb-4">Intervalo de recarregamento</p>
                        <p class="cursor-pointer p-2 hover:bg-zinc-200" id="refresh_interval_3"><i class="fa-solid fa-clock-rotate-left me-3"></i>3 segundos</p>
                        <p class="cursor-pointer p-2 hover:bg-zinc-200" id="refresh_interval_5"><i class="fa-solid fa-clock-rotate-left me-3"></i>5 segundos</p>
                        <p class="cursor-pointer p-2 hover:bg-zinc-200" id="refresh_interval_10"><i class="fa-solid fa-clock-rotate-left me-3"></i>10 segundos</p>
                    </div>

                    <div class="main-card w-full flex justify-center items-center !p-4">
                        <a href="{{ route('queues.display.credentials') }}" class="btn">Voltar as credenciais</a>
                    </div>

                </div>

                <div class="flex justify-center">
                    <button id="close-modal" class="btn w-40">Fechar</button>
                </div>

            </div>

        </div>

    </div>

    <script>

        let queueInterval = 5000;
        let refreshInterval = 0;
        const url = "{{ route('queues.display.get.bundle.data') }}";
        const queuesContainer = document.querySelector("#queues");

        // first call to fetch data
        getBundleData(url).then(data => {
            render(data);
        });

        async function getBundleData(url) {

            try {

                const response = await fetch(url, {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        credential: '{{ Crypt::encrypt($credential) }}'
                    })
                });
                if(!response.ok) {
                    throw new Error(response);
                }
                return await response.json();

            } catch (error) {
                render(error);
            }

        }

        function render(data) {
            // check if data is an error or not
            if(data.status === 'error') {
                renderError(data);
            } else {
                renderQueues(data.data.queues);
            }
        }

        function renderError(data) {
            console.log(data);
        }

        function renderQueues(queues) {

            console.log('rendering queues');

            const queuesLayout = queues.length <= 4 ? 'w-1/1' : 'w-1/2';

            queuesContainer.innerHTML = '';

            queues.forEach(queue => {

                const colors = JSON.parse(queue.queue_colors);
                colors.bg_ticket = '#000000';
                colors.text_ticket = '#ffffff';

                // check if there are tickets in the queue or if the queue is not active
                if(queue.status !== 'active' || queue.tickets.length === 0) {
                    let bg_color = '#eeeeee';
                    let text_color = '#cccccc';
                    colors.prefix_bg_color = bg_color;
                    colors.prefix_text_color = text_color;
                    colors.number_bg_color = bg_color;
                    colors.number_text_color = text_color;
                    colors.bg_ticket = bg_color;
                    colors.text_ticket = text_color;
                }

                const queueContent = document.createElement('div');
                queueContent.className = `flex ${queuesLayout} gap-2 rounded-xl p-2`;
                queueContent.innerHTML = `

                    <div class="text-center font-mono rounded-xl border-1 border-zinc-800 p-1" style="
                        background-color: ${colors.prefix_bg_color};
                        color: ${colors.prefix_text_color};
                    ">
                        <p class="text-8xl px-4 font-bold">${queue.queue_prefix}</p>
                    </div>



                    <div class="bg-white w-full rounded-xl border-1 border-zinc-800 p-3" style="
                        background-color: ${colors.number_bg_color};
                        color: ${colors.number_text_color};
                    ">
                        <p class="text-2xl font-bold">${queue.service_name}</p>
                        <p class="text-xl font-bold opacity-50">${queue.service_desk}</p>
                    </div>

                    <div class="w-1/3 rounded-xl border-1 border-zinc-800 p-3" style="background-color: ${colors.bg_ticket}">
                        <p class="text-7xl text-center font-mono" style="color: ${colors.text_ticket}">
                            ${queue.tickets.length !== 0 ? queue.tickets[0].queue_ticket_number : ''}
                        </p>
                    </div>
                `;

                queuesContainer.appendChild(queueContent);
            });

        }


        // set interval to fetch data
        refreshInterval = setInterval(() => {
            getBundleData(url).then(data => {
                render(data);
            });
        }, queueInterval);


        // access options
        document.querySelector("#access_options").addEventListener('dblclick', (event) => {
            const btn_options = document.querySelector("#btn_options");
            btn_options.classList.remove("!hidden");
            event.target.classList.add("!hidden");

            setTimeout(() => {
                btn_options.classList.add("!hidden");
                event.target.classList.remove("!hidden");
            }, 3000);
        });

        // open options
        document.querySelector("#btn_options").addEventListener('click', () => {
            document.querySelector("#modal").style.display = "flex";
        });

        // close modal
        document.querySelector("#close-modal").addEventListener('click', () => {
            document.querySelector("#modal").style.display = "none";
        });


        // set refresh interval
        document.querySelectorAll('[id^="refresh_interval_"]').forEach(element => {
            element.addEventListener('click', (event) => {
                const interval = parseInt(event.target.id.split('_').pop());
                queueInterval = interval * 1000;

                // reset the interval to fetch data
                clearInterval(refreshInterval);
                refreshInterval = setInterval(() => {
                    getBundleData(url).then(data => {
                        render(data);
                    });
                }, queueInterval);

                document.querySelector('#modal').style.display = 'none';
            });
        });

    </script>
</x-layouts.guest-layout>

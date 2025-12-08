<x-layouts.guest-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="flex flex-col justify-center p-4">

        <div class="flex justify-end mb-4">

            <p id="access_options" class="w-10 h-10"></p>

            <i id="btn_options" class="fa-solid fa-gear btn-white p-2 !hidden"></i>
        </div>

        <div class="main-card flex gap-4 w-full">

            <div id="queues" class="flex flex-wrap p-2 w-full border-1 border-slate-300 rounded-xl"></div>

            <div id="ticket" class="flex w-1/4 h-100 border-1 border-slate-300 rounded-xl p-4"></div>

        </div>

        {{--    modal    --}}
        <div id="modal" class="fixed inset-0 z-50 flex items-center justify-center bg-[#000000aa]" style="display: none;">

            <div class="bg-white rounded-xl shadow-lg p-6 w-200 hover">

                <p class="title-3">Opções do dispensador</p>

                <hr class="border-slate-300 my-4">

                <div class="flex justify-between gap-4 my-10">

                    <div class="main-card w-full !p-4">
                        <p class="text-slate-500 text-sm mb-4">Intervalo de recarregamento</p>
                        <p class="cursor-pointer p-2 hover:bg-zinc-200" id="refresh_interval_3"><i class="fa-solid fa-clock-rotate-left me-3"></i>3 segundos</p>
                        <p class="cursor-pointer p-2 hover:bg-zinc-200" id="refresh_interval_5"><i class="fa-solid fa-clock-rotate-left me-3"></i>5 segundos</p>
                        <p class="cursor-pointer p-2 hover:bg-zinc-200" id="refresh_interval_10"><i class="fa-solid fa-clock-rotate-left me-3"></i>10 segundos</p>
                    </div>

                    <div class="main-card w-full !p-4">
                        <p class="text-slate-500 text-sm mb-4">Intervalo de ticket</p>
                        <p class="cursor-pointer p-2 hover:bg-zinc-200" id="visible_ticket_3"><i class="fa-solid fa-clock-rotate-left me-3"></i>3 segundos</p>
                        <p class="cursor-pointer p-2 hover:bg-zinc-200" id="visible_ticket_5"><i class="fa-solid fa-clock-rotate-left me-3"></i>5 segundos</p>
                        <p class="cursor-pointer p-2 hover:bg-zinc-200" id="visible_ticket_10"><i class="fa-solid fa-clock-rotate-left me-3"></i>10 segundos</p>
                    </div>
                    <div class="main-card w-full flex justify-center items-center !p-4">
                        <a href="{{ route('dispenser.credentials') }}" class="btn">Voltar as credenciais</a>
                    </div>

                </div>

                <div class="flex justify-center">
                    <button id="close-modal" class="btn w-40">Fechar</button>
                </div>

            </div>

        </div>

    </div>
    <script>

        // time intervals
        let queueInterval = 5000; // 5 segundos
        let TicketInterval = 5000; // 5 segundos
        let refreshInterval = 0;


        {{--const url = "{{ route('dispenser.get.bundle.data', ['credential' => $credential . 'xxx']) }}";--}}
        const url = "{{ route('dispenser.get.bundle.data', ['credential' => $credential]) }}";
        const queuesContainer = document.querySelector("#queues");

        // first call as soon as we enter the view
        getBundleData(url).then(data => {
            render(data);
        });

        // asynchronous call to fetch data
        async function getBundleData(url){
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(
                        {
                            credential: '{{ Crypt::encrypt($credential) }}'
                        }
                    )
                });

                if (!response.ok){
                    throw new Error(response);
                }
                return await response.json();
            } catch (error){
                render(error)
            }
        }

        function render(data){
            if (data.status === 'error') {
                renderError(data);
            } else {
                renderQueues(data.queues);
            }
        }

        function renderError(data){
            queuesContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center w-full text-red-500 p-4 mt-10">
                    <i class="text-3xl fa-solid fa-triangle-exclamation mb-2"></i>
                    <p>Erro: ${data.message} </p>
                </div>`;
        }

        function renderQueues(queues) {
            const queuesLayout = queues.length <= 4 ? 'w-1/1' : 'w-1/2';
            queuesContainer.innerHTML = '';

            queues.forEach(queue => {
                const queueContent = document.createElement('a');
                queueContent.className = `flex gap-2 rounded-xl p-2 ${queuesLayout} hover:bg-black transition-all duration-300`;
                queueContent.style.borderColor = queue.colors.prefix_bg_color;
                queueContent.id = queue.hash_code;

                // include html
                queueContent.innerHTML = `
                    <div class="text-center font-mono rounded-xl border-1 border-zinc-800 p-1"
                    style="background-color: ${ queue.colors.prefix_bg_color }; color: ${ queue.colors.prefix_text_color };">
                        <p class="text-8xl px-4 font-bold">${ queue.prefix }</p>
                    </div>

                    <div class="bg-slate-100 w-full rounded-xl border-1 border-zinc-800 p-3 " style="background-color: ${ queue.colors.number_bg_color }; color: ${ queue.colors.number_text_color };">
                        <p class="text-3xl font-bold text-slate-700">${ queue.service }</p>
                        <p class="text-2xl font-bold text-slate-400">${ queue.desk }</p>
                    </div>
                `;

                // add click event to queue content

                queueContent.addEventListener('click', () => {
                    getTicket(queue.hash_code);
                });

                queuesContainer.appendChild(queueContent)

            });


        }

        async function getTicket(hash_code){
            const url = "{{ route('dispenser.get.ticket') }}";
            try {
                // throw new Error(response);
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(
                        {
                            hash_code: hash_code
                        }
                    )
                });
                if (!response.ok){
                    throw new Error(response);
                }
                const data = await response.json();
                renderTicket(data.ticket)


            } catch (error) {
                renderTicketError();
            }
        }

        function renderTicketError(){
            const ticketContainer = document.querySelector("#ticket");
            ticketContainer.innerHTML = `
                    <div class="flex flex-col justify-center items-center">
                        <i class="text-6xl text-red-500 fa-solid fa-triangle-exclamation mb-2"></i>
                        <p class="text-center text-red-500">Erro ao obter o ticket.</p>
                        <p class="text-center text-red-500 text-sm">Tente novamente ou solicite informações ao balcão de atentimento.</p>
                    </div>
                `;

            setTimeout(() => {
                ticketContainer.innerHTML = '';
            }, 5000);
        }

        function renderTicket(ticket){
            const ticketContainer = document.querySelector("#ticket");
            ticketContainer.innerHTML = `
                    <div class="bg-slate-100 rounded-xl w-full text-center">
                        <p class="w-full text-8xl font-bold text-slate-800">${ticket.prefix}</p>
                        <p class="w-full text-7xl font-bold text-slate-800">${ticket.number}</p>
                        <p class="w-full text-2xl font-semibold text-slate-600">${ticket.queue_service}</p>
                        <p class="w-full text-xl font-semibold text-slate-500">${ticket.service_desk}</p>
                        <p class="w-full text-sm text-slate-400 mt-2">Emitido em:<br> ${ticket.created_at}</p>
                    </div>
                `;

            setTimeout(() => {
                ticketContainer.innerHTML = '';
            }, 5000);
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
            element.addEventListener('click', (event) =>{
                const interval = parseInt(event.target.id.split('_').pop());
                queueInterval = interval * 1000;

                // reset the internal to fetch data
                clearInterval(refreshInterval);
                refreshInterval = setInterval(() =>{
                    getBundleData(url).then(data => {
                        render(data);
                    });
                }, queueInterval);

                document.querySelector('#modal').style.display = 'none';
            });
        });

        // set visible ticket interval
        document.querySelectorAll('[id^="visible_ticket_"]').forEach(element => {
            element.addEventListener('click', (event) =>{
                const interval = parseInt(event.target.id.split('_').pop());
                ticketInterval = interval * 1000
                document.querySelector('#modal').style.display = 'none';
            });
        });
    </script>
</x-layouts.guest-layout>

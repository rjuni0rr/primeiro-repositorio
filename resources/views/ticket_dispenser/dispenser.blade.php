<x-layouts.guest-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="flex flex-col justify-center p-4">

        <div class="text-end mb-4">
            [opções]
        </div>

        <div class="main-card flex gap-4 w-full">
            <div id="queues" class="flex flex-wrap p-2 w-full border-1 border-slate-300 rounded-xl"></div>

            <div class="flex w-1/4 h-100 border-1 border-slate-300 rounded-xl p-4">[preview to ticket]</div>
        </div>

    </div>
    <script>
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

            async function getTicket(hash_code){
                const url = "{{ route('dispenser.get.ticket') }}";

                try {
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
                    console.log(data);
                } catch (error) {
                    console.log(error);
                }
            }
        }

    </script>
</x-layouts.guest-layout>

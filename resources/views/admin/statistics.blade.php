<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}" apexcharts>

    <div class="main-card overflow-auto">

        <div class="flex justify-between">
            <p class="title-3">Estatísticas</p>
            <a href="{{ route('admin.home') }}" class="btn"><i class="fas fa-arrow-left me-2"></i>Voltar</a>
        </div>

        <hr class="my-4">

        <div class="flex gap-4 mb-4">

            <div class="main-card w-1/2 p-6">
                <p class="title-1">Clientes ativos e inativos</p>
                <p class="title-3">Total: <strong>{{ $statsCompanies['total'] }}</strong></p>
                <div id="chart_1"></div>
            </div>

            <div class="main-card w-1/2 p-6">
                <p class="title-1">Usuários por estado</p>
                <p class="title-3">Total global: <strong>{{ $statsUsersByState['total'] }}</strong></p>
                <div id="chart_2"></div>
            </div>
        </div>

        <div class="flex gap-4">

            <div class="main-card w-1/2 p-6">
                <p class="title-1">Todos os tickets por estado atual</p>
                <p class="title-3">Total: <strong>{{ $statsAllTicketsByStatus['total'] }}</strong></p>
                <div id="chart_3"></div>
            </div>

            <div class="main-card w-1/2 p-6">
                <p class="title-1">Usuários por tipo</p>
                <p class="title-3">Total: <strong>{{ $statsAllUsersByRole['total'] }}</strong></p>
                <div id="chart_4"></div>
            </div>
        </div>
    </div>
    <script>

        let chart_1 = new ApexCharts(document.querySelector("#chart_1"), {
            chart: {
                type: "donut",
                height: 300,
                toolbar: {
                    show: true,
                }
            },
            series: [
                {{ $statsCompanies['active'] }},
                {{ $statsCompanies['inactive'] }},
            ],
            labels: ['Ativos', 'Inativos'],
            colors: ["#00AA00", "#AA0000"]
        });
        chart_1.render();


        let chart_2 = new ApexCharts(document.querySelector("#chart_2"), {
            chart: {
                type: "bar",
                height: 300,
                toolbar: {
                    show: true,
                }
            },
            plotOptions: {
                bar: {
                    distributed: true,
                }
            },
            series: [{
                name: 'Usuários',
                data: [{{ implode(',', array_slice($statsUsersByState, 1)) }}]
            }],
            xaxis: {
                categories: ['Activos', 'Inativos', 'Bloqueados', 'Sem senha']
            },
            colors: ["#00AA00", "#AA0000", "#CCCC00", "#AAAAAA"],
        });
        chart_2.render();


        let chart_3 = new ApexCharts(document.querySelector("#chart_3"), {
            chart: {
                type: "bar",
                height: 300,
                toolbar: {
                    show: true,
                }
            },
            plotOptions: {
                bar: {
                    distributed: true,
                    horizontal: true
                }
            },
            series: [{
                name: 'Tickets',
                data: [{{ implode(',', array_slice($statsAllTicketsByStatus, 1)) }}]
            }],
            xaxis: {
                categories: ['Em espera', 'Chamados', 'Não atendidos', 'Dispensados']
            },
            colors: ["#0000AA", "#00AA00", "#AA0000", "#AAAAAA"],
        });
        chart_3.render();

        let chart_4 = new ApexCharts(document.querySelector("#chart_4"), {
            chart: {
                type: "bar",
                height: 300,
                toolbar: {
                    show: true,
                }
            },
            plotOptions: {
                bar: {
                    distributed: true,
                    horizontal: true
                }
            },
            series: [{
                name: 'Usuários',
                data: [{{ implode(',', array_slice($statsAllUsersByRole, 1)) }}]
            }],
            xaxis: {
                categories: ['Usuários', 'Administradores']
            },
            colors: ["#0000AA", "#00AA00"],
        });
        chart_4.render();
    </script>


</x-layouts.auth-layout>

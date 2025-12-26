<x-layouts.guest-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="main-card m-10">

        <div class="flex justify-center mb-10">
            <img src="{{ asset('assets/images/favicon.png') }}" class="w-10 h-10 me-2" alt="Logo">
            <h3 class="text-3xl uppercase">{{ config('app.name') }}</h3>
        </div>

        <hr class="my-4">

        <div class="flex justify-center">

            <div class="w-200">

                <p class="title-1 w-200 mb-6">Termos e Condições</p>

                <div class="mb-6">
                    <p class="title-3 mb-4">1. Aceitação dos Termos</p>
                    <p>Ao aceder e utilizar o serviço de gestão de filas de espera (a "Aplicação"), concorda em ficar vinculado a estes Termos e Condições ("Termos"). Se não concordar com qualquer parte dos Termos, não deve utilizar a Aplicação.</p>
                </div>

                <div class="mb-6">
                    <p class="title-3 mb-4">2. O Serviço</p>
                    <p>A Aplicação fornece uma ferramenta digital para a gestão e visualização de filas de espera em diversos serviços. O serviço é disponibilizado "como está" e não garantimos a sua disponibilidade, precisão ou pontualidade ininterrupta. A responsabilidade pela gestão efetiva e pelo atendimento final dos utentes pertence exclusivamente à entidade que utiliza a Aplicação (o "Prestador de Serviço").</p>
                </div>

                <div class="mb-6">
                    <p class="title-3 mb-4">3. Responsabilidade do Utilizador</p>
                    <p>O utilizador (seja o Prestador de Serviço ou o utente) é responsável por manter a confidencialidade das credenciais de acesso, se aplicável.</p>
                    <p>O utilizador concorda em não utilizar a Aplicação para fins ilegais ou não autorizados.</p>
                </div>

                <div class="mb-6">
                    <p class="title-3 mb-4">4. Limitação de Responsabilidade</p>
                    <p>A Aplicação é uma ferramenta auxiliar. Em nenhuma circunstância seremos responsáveis por quaisquer atrasos, perdas, danos (diretos, indiretos ou consequenciais) ou inconvenientes resultantes da utilização ou da incapacidade de utilizar a Aplicação, incluindo erros na gestão da fila ou no tempo de espera.</p>
                </div>

                <div class="mb-6">
                    <p class="title-3 mb-4">5. Privacidade</p>
                    <p>O tratamento de dados pessoais é regido pela nossa Política de Privacidade (disponível separadamente), que o utilizador deve consultar.
                </div>

                <div class="mb-6">
                    <p class="title-3 mb-4">6. Alterações</p>
                    <p>Reservamo-nos o direito de modificar ou substituir estes Termos a qualquer momento. É da sua responsabilidade verificar os Termos periodicamente. O uso continuado da Aplicação após a publicação de quaisquer alterações constitui aceitação dessas alterações.</p>
                </div>

                <div class="mb-6">
                    <p>Este texto é um modelo básico e não tem valor legal. Se a sua aplicação avançar, deve consultar um profissional jurídico para redigir termos e condições que cumpram a legislação aplicável (como o RGPD) e protejam adequadamente o seu negócio.</p>
                </div>

            </div>

        </div>

        <div class="flex justify-center">
            <a href="{{ route('login') }}" class="btn !px-10">Voltar</a>
        </div>

    </div>
</x-layouts.guest-layout>


<p>Bem vindo ao {{ config('app.name') }}</p>
<p>Foi concluido o registro de novo cliente <strong>{{ $company_name }}</strong></p>
<p>Para conclusão do seu registro, como administrador do sistem, por favor, clique no link abaixo.</p>
<p>
    <a href="{{ route('conclude.registration', ['code' => Crypt::encrypt($code)]) }}">Concluir registro</a>
</p>
<p>Este link vai estar disponível até <strong>{{ now()->addMinutes(config('constants.MAIL_NEW_CLIENT_CODE_EXPIRATION')) }}</strong></p>








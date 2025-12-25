<p>Esta é uma mensagem eviado por {{ config('app.name') }}</p>
<p>Para recuperar a sua senha de usuário, por favor clique no link abaixo</p>
<p>
    <a href="{{ route('recover.password.define.new', ['code' => Crypt::encrypt($code)]) }}">Recuperar senha</a>
</p>
<p>Este link vai estar disponível até <strong>{{ now()->addMinutes(config('constants.MAIL_NEW_CLIENT_CODE_EXPIRATION')) }}</strong></p>








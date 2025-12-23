
<p>Foi solicitada a redefinição de senha por parte do administrador do sistema</p>
<p>Para conclusão do processo, por favor, clique no link abaixo.</p>
<p>
    <a href="{{ route('password.reset', ['code' => Crypt::encrypt($code)]) }}">Redefinir senha</a>
</p>
<p>Este link vai estar disponível até <strong>{{ now()->addMinutes(config('constants.MAIL_NEW_CLIENT_CODE_EXPIRATION')) }}</strong></p>

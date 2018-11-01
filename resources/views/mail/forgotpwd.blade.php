<html>
<body>
    <h1>Rádio Estúdio Brasil - Recuperação de senha</h1>
    <p>Olá {{ $user->name }},</p>
    <p>Você solicitou uma troca de senha no nosso site, para criar uma senha nova acesse:</p>
    <p><a href="http://www.radioestudiobrasil.com.br/admin#/trocarSenha?token={{ $user->forgot_pwd_token }}">Trocar a senha</a></p>
</body>
</html>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Redefinição de senha — Vitalfy</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #f4f6f8; font-family: 'Segoe UI', Arial, sans-serif; color: #374151; }
        .wrapper { max-width: 580px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 36px 40px; text-align: center; }
        .header img { height: 36px; margin-bottom: 8px; }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: 700; letter-spacing: -0.3px; }
        .body { padding: 40px 40px 32px; }
        .greeting { font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 12px; }
        .text { font-size: 14px; line-height: 1.7; color: #4b5563; margin-bottom: 16px; }
        .btn-wrap { text-align: center; margin: 28px 0; }
        .btn { display: inline-block; background: #2563eb; color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 600; padding: 14px 36px; border-radius: 8px; letter-spacing: 0.2px; }
        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 24px 0; }
        .link-fallback { font-size: 12px; color: #9ca3af; line-height: 1.6; }
        .link-fallback a { color: #3b82f6; word-break: break-all; }
        .expiry { background: #fef9c3; border-left: 3px solid #f59e0b; padding: 10px 14px; border-radius: 4px; font-size: 13px; color: #78350f; margin-bottom: 24px; }
        .footer { background: #f9fafb; padding: 20px 40px; text-align: center; border-top: 1px solid #e5e7eb; }
        .footer p { font-size: 12px; color: #9ca3af; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Vitalfy</h1>
        </div>

        <div class="body">
            <p class="greeting">Olá, {{ $userName }}!</p>

            <p class="text">
                Recebemos uma solicitação para redefinir a senha da sua conta Vitalfy.
                Clique no botão abaixo para criar uma nova senha.
            </p>

            <div class="expiry">
                ⏱ Este link é válido por <strong>60 minutos</strong>. Após esse prazo, será necessário solicitar um novo link.
            </div>

            <div class="btn-wrap">
                <a href="{{ $resetUrl }}" class="btn">Redefinir minha senha</a>
            </div>

            <p class="text">
                Se você não solicitou a redefinição de senha, ignore este e-mail.
                Sua senha permanecerá a mesma e nenhuma alteração será feita.
            </p>

            <hr class="divider" />

            <p class="link-fallback">
                Se o botão não funcionar, copie e cole o link abaixo no seu navegador:<br />
                <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
            </p>
        </div>

        <div class="footer">
            <p>
                Vitalfy — Plataforma de transcrição e documentação clínica<br />
                Este é um e-mail automático. Por favor, não responda.
            </p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bem-vindo(a) à Vitalfy</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #f4f6f8; font-family: 'Segoe UI', Arial, sans-serif; color: #374151; }
        .wrapper { max-width: 580px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 36px 40px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: 700; letter-spacing: -0.3px; }
        .header p { color: rgba(255,255,255,0.85); font-size: 14px; margin-top: 6px; }
        .body { padding: 40px 40px 32px; }
        .greeting { font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 12px; }
        .text { font-size: 14px; line-height: 1.7; color: #4b5563; margin-bottom: 16px; }
        .btn-wrap { text-align: center; margin: 28px 0; }
        .btn { display: inline-block; background: #2563eb; color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 600; padding: 14px 36px; border-radius: 8px; letter-spacing: 0.2px; }
        .expiry { background: #fef9c3; border-left: 3px solid #f59e0b; padding: 10px 14px; border-radius: 4px; font-size: 13px; color: #78350f; margin-bottom: 24px; }
        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 24px 0; }
        .features { background: #f8fafc; border-radius: 8px; padding: 20px 24px; margin-bottom: 24px; }
        .features-title { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        .feature-item { font-size: 13px; color: #4b5563; line-height: 1.6; padding: 4px 0; }
        .feature-item span { color: #2563eb; margin-right: 8px; font-weight: 700; }
        .link-fallback { font-size: 12px; color: #9ca3af; line-height: 1.6; }
        .link-fallback a { color: #3b82f6; word-break: break-all; }
        .footer { background: #f9fafb; padding: 20px 40px; text-align: center; border-top: 1px solid #e5e7eb; }
        .footer p { font-size: 12px; color: #9ca3af; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Vitalfy</h1>
            <p>Documentação clínica com inteligência artificial</p>
        </div>

        <div class="body">
            <p class="greeting">Olá, {{ $userName }}!</p>

            <p class="text">
                Você acaba de dar o primeiro passo para transformar a forma como documenta suas consultas.
            </p>

            <p class="text">
                A Vitalfy foi criada para médicos que querem focar no paciente — não no teclado.
                Com inteligência artificial treinada para o contexto clínico, você transforma
                qualquer consulta em um documento estruturado em minutos.
            </p>

            <p class="text">Antes de começar, confirme seu endereço de e-mail:</p>

            <div class="expiry">
                ⏱ Este link é válido por <strong>60 minutos</strong>. Após esse prazo, acesse a plataforma e solicite um novo link de verificação.
            </div>

            <div class="btn-wrap">
                <a href="{{ $verificationUrl }}" class="btn">Confirmar meu e-mail</a>
            </div>

            <div class="features">
                <p class="features-title">O que te espera na plataforma</p>
                <p class="feature-item"><span>✦</span>30+ templates por especialidade médica</p>
                <p class="feature-item"><span>✦</span>Transcrição em português com identificação de locutores</p>
                <p class="feature-item"><span>✦</span>Geração de documentos clínicos com IA</p>
                <p class="feature-item"><span>✦</span>Insights automáticos: red flags, diagnósticos, CID-10 e mais</p>
                <p class="feature-item"><span>✦</span>10 transcrições gratuitas por mês para começar</p>
            </div>

            <hr class="divider" />

            <p class="link-fallback">
                Se o botão não funcionar, copie e cole o link abaixo no seu navegador:<br />
                <a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a>
            </p>

            <hr class="divider" />

            <p class="text" style="font-size: 13px; color: #6b7280;">
                Se você não criou esta conta, ignore este e-mail. Nenhuma ação será necessária.
            </p>
        </div>

        <div class="footer">
            <p>
                Vitalfy — Plataforma de documentação clínica com IA<br />
                Este é um e-mail automático. Por favor, não responda.
            </p>
        </div>
    </div>
</body>
</html>

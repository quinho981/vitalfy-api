<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Você está a 2 transcrições do seu limite mensal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #f4f6f8; font-family: 'Segoe UI', Arial, sans-serif; color: #374151; }
        .wrapper { max-width: 580px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 36px 40px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: 700; letter-spacing: -0.3px; }
        .header p { color: rgba(255,255,255,0.9); font-size: 14px; margin-top: 6px; }
        .body { padding: 40px 40px 32px; }
        .greeting { font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 12px; }
        .text { font-size: 14px; line-height: 1.7; color: #4b5563; margin-bottom: 16px; }
        .btn-wrap { text-align: center; margin: 28px 0; }
        .btn { display: inline-block; background: #2563eb; color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 600; padding: 14px 36px; border-radius: 8px; letter-spacing: 0.2px; }
        .warning-bar { background: #fff7ed; border: 1px solid #fed7aa; border-radius: 8px; padding: 18px 20px; margin-bottom: 24px; }
        .warning-bar-title { font-size: 15px; font-weight: 700; color: #9a3412; margin-bottom: 6px; }
        .progress-bg { background: #e5e7eb; border-radius: 999px; height: 10px; margin: 10px 0; overflow: hidden; }
        .progress-fill { background: linear-gradient(90deg, #f59e0b, #d97706); height: 10px; border-radius: 999px; width: 80%; }
        .progress-label { font-size: 12px; color: #9a3412; font-weight: 600; }
        .benefits { background: #f8fafc; border-radius: 8px; padding: 20px 24px; margin-bottom: 24px; }
        .benefits-title { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 12px; }
        .benefit-item { font-size: 13px; color: #4b5563; line-height: 1.6; padding: 4px 0; }
        .benefit-item span { color: #2563eb; margin-right: 8px; font-weight: 700; }
        .price-note { text-align: center; font-size: 13px; color: #6b7280; margin-bottom: 24px; line-height: 1.6; }
        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 24px 0; }
        .footer { background: #f9fafb; padding: 20px 40px; text-align: center; border-top: 1px solid #e5e7eb; }
        .footer p { font-size: 12px; color: #9ca3af; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Vitalfy</h1>
            <p>Aviso de limite mensal</p>
        </div>

        <div class="body">
            <p class="greeting">Olá, {{ $userName }}!</p>

            <div class="warning-bar">
                <p class="warning-bar-title">Você utilizou 8 de 10 transcrições gratuitas este mês.</p>
                <div class="progress-bg">
                    <div class="progress-fill"></div>
                </div>
                <p class="progress-label">Restam apenas 2 transcrições.</p>
            </div>

            <p class="text">
                Não pare no meio de uma consulta por causa de um limite.
                Com o Vitalfy Pro, você documenta sem interrupções — quantas consultas precisar, quando precisar.
            </p>

            <div class="benefits">
                <p class="benefits-title">Com o Vitalfy Pro você tem:</p>
                <p class="benefit-item"><span>✦</span>Transcrições ilimitadas — sem bloqueios mensais</p>
                <p class="benefit-item"><span>✦</span>Refinamento de documentos com IA (clareza, técnico, SOAP)</p>
                <p class="benefit-item"><span>✦</span>Suporte prioritário</p>
                <p class="benefit-item"><span>✦</span>Acesso a todos os templates especializados</p>
            </div>

            <div class="btn-wrap">
                <a href="{{ $appUrl }}/subscription" class="btn">Fazer upgrade agora</a>
            </div>

            <p class="price-note">
                Planos a partir de <strong>R$ 97/mês</strong>.<br />
                Menos do que o custo de 2 consultas particulares.
            </p>

            <hr class="divider" />

            <p class="text" style="font-size: 13px; color: #6b7280; text-align: center;">
                Se preferir aguardar, suas transcrições gratuitas renovam no dia 1 do próximo mês.<br />
                Mas não deixe isso interromper seu atendimento.
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

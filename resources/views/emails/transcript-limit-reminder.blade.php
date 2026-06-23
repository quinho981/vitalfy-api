<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Seu resumo Vitalfy</title>
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
        .stats-row { display: flex; gap: 16px; margin: 24px 0; }
        .stat-card { flex: 1; background: #f8fafc; border-radius: 8px; padding: 18px; text-align: center; border: 1px solid #e5e7eb; }
        .stat-value { font-size: 28px; font-weight: 800; color: #1d4ed8; line-height: 1; }
        .stat-desc { font-size: 12px; color: #6b7280; margin-top: 6px; line-height: 1.4; }
        .benefits { background: #f8fafc; border-radius: 8px; padding: 20px 24px; margin-bottom: 24px; }
        .benefits-title { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 12px; }
        .benefit-item { font-size: 13px; color: #4b5563; line-height: 1.6; padding: 4px 0; }
        .benefit-item span { color: #2563eb; margin-right: 8px; font-weight: 700; }
        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 24px 0; }
        .footer { background: #f9fafb; padding: 20px 40px; text-align: center; border-top: 1px solid #e5e7eb; }
        .footer p { font-size: 12px; color: #9ca3af; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Vitalfy</h1>
            <p>Seu resumo da primeira semana</p>
        </div>

        <div class="body">
            <p class="greeting">Olá, {{ $userName }}!</p>

            <p class="text">
                Você está há uma semana na Vitalfy. Aqui está um resumo do que aconteceu na sua conta:
            </p>

            <div class="stats-row">
                <div class="stat-card">
                    <p class="stat-value">{{ $transcriptsUsed }}</p>
                    <p class="stat-desc">transcrições realizadas<br />este mês</p>
                </div>
                <div class="stat-card">
                    <p class="stat-value">{{ $remaining }}</p>
                    <p class="stat-desc">transcrições gratuitas<br />restantes este mês</p>
                </div>
            </div>

            @if($transcriptsUsed > 0)
            <p class="text">
                Você já está documentando melhor e economizando tempo com a Vitalfy.
                Para nunca mais se preocupar com limites mensais e ter acesso a todos os recursos avançados:
            </p>

            <div class="btn-wrap">
                <a href="{{ $appUrl }}/subscription" class="btn">Fazer upgrade para o Pro</a>
            </div>
            @else
            <p class="text">
                Suas 10 transcrições gratuitas ainda estão esperando por você.
                Comece hoje — é mais simples do que parece e você vai sentir a diferença já na primeira consulta.
            </p>

            <div class="btn-wrap">
                <a href="{{ $appUrl }}/upload" class="btn">Criar minha primeira transcrição</a>
            </div>
            @endif

            <div class="benefits">
                <p class="benefits-title">Com o Vitalfy Pro você tem:</p>
                <p class="benefit-item"><span>✦</span>Transcrições ilimitadas — sem interrupções no seu fluxo</p>
                <p class="benefit-item"><span>✦</span>Refinamento de documentos com IA (clareza, linguagem técnica, formato SOAP)</p>
                <p class="benefit-item"><span>✦</span>Suporte prioritário</p>
                <p class="benefit-item"><span>✦</span>Acesso a todos os 30+ templates especializados</p>
            </div>

            <hr class="divider" />

            <p class="text" style="font-size: 13px; color: #6b7280; text-align: center;">
                Dúvidas? Responda a este e-mail — nossa equipe está aqui para ajudar.
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

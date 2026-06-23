<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Você tem 10 documentações clínicas esperando por você</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #f4f6f8; font-family: 'Segoe UI', Arial, sans-serif; color: #374151; }
        .wrapper { max-width: 580px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 36px 40px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: 700; letter-spacing: -0.3px; }
        .body { padding: 40px 40px 32px; }
        .greeting { font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 12px; }
        .text { font-size: 14px; line-height: 1.7; color: #4b5563; margin-bottom: 16px; }
        .btn-wrap { text-align: center; margin: 28px 0; }
        .btn { display: inline-block; background: #2563eb; color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 600; padding: 14px 36px; border-radius: 8px; letter-spacing: 0.2px; }
        .secondary-link { text-align: center; margin-top: -16px; margin-bottom: 24px; }
        .secondary-link a { font-size: 13px; color: #6b7280; text-decoration: underline; }
        .stat-box { background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-radius: 10px; padding: 24px; margin-bottom: 24px; text-align: center; }
        .stat-number { font-size: 36px; font-weight: 800; color: #1d4ed8; line-height: 1; }
        .stat-label { font-size: 13px; color: #3b82f6; margin-top: 6px; font-weight: 500; }
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
        </div>

        <div class="body">
            <p class="greeting">Olá, {{ $userName }}!</p>

            <p class="text">
                Você ainda não criou sua primeira transcrição na Vitalfy — e está tudo bem.
                Mas eu quero te contar o que está esperando por você.
            </p>

            <div class="stat-box">
                <p class="stat-number">1–3h</p>
                <p class="stat-label">economizadas em documentação por dia de atendimento</p>
            </div>

            <p class="text">
                Médicos que usam a Vitalfy deixam de gastar horas digitando prontuários para
                focar no que realmente importa: o cuidado com o paciente.
            </p>

            <div class="benefits">
                <p class="benefits-title">O que a Vitalfy faz por você automaticamente:</p>
                <p class="benefit-item"><span>✦</span>Transcreve o áudio com identificação de locutor</p>
                <p class="benefit-item"><span>✦</span>Gera o documento clínico no template da sua especialidade</p>
                <p class="benefit-item"><span>✦</span>Identifica red flags e alertas clínicos automaticamente</p>
                <p class="benefit-item"><span>✦</span>Sugere diagnósticos diferenciais e códigos CID-10</p>
                <p class="benefit-item"><span>✦</span>Exporta em PDF profissional com um clique</p>
            </div>

            <p class="text">
                Você ainda tem <strong>10 transcrições gratuitas</strong> disponíveis este mês.
                Não deixe para depois o que pode te economizar horas hoje.
            </p>

            <div class="btn-wrap">
                <a href="{{ $appUrl }}/upload" class="btn">Começar agora — é gratuito</a>
            </div>

            <div class="secondary-link">
                <a href="{{ $appUrl }}/subscription">Conhecer o plano Pro</a>
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

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Seu primeiro documento clínico foi gerado</title>
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
        .secondary-link { text-align: center; margin-top: -16px; margin-bottom: 24px; }
        .secondary-link a { font-size: 13px; color: #6b7280; text-decoration: underline; }
        .milestone { background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-radius: 10px; padding: 20px 24px; margin-bottom: 24px; text-align: center; }
        .milestone-icon { font-size: 32px; margin-bottom: 8px; }
        .milestone-text { font-size: 15px; font-weight: 600; color: #065f46; }
        .milestone-sub { font-size: 13px; color: #047857; margin-top: 4px; }
        .generated-items { background: #f8fafc; border-radius: 8px; padding: 20px 24px; margin-bottom: 24px; }
        .generated-title { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 12px; }
        .generated-item { font-size: 13px; color: #4b5563; line-height: 1.6; padding: 4px 0; }
        .generated-item span { color: #059669; margin-right: 8px; font-weight: 700; }
        .pro-tip { background: #fef9c3; border-left: 3px solid #f59e0b; padding: 12px 16px; border-radius: 4px; font-size: 13px; color: #78350f; margin-bottom: 24px; line-height: 1.6; }
        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 24px 0; }
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

            <div class="milestone">
                <p class="milestone-icon">🎉</p>
                <p class="milestone-text">Sua primeira transcrição foi criada!</p>
                <p class="milestone-sub">A Vitalfy processou a consulta automaticamente.</p>
            </div>

            <p class="text">
                A Vitalfy transcreveu a consulta, estruturou o documento clínico
                e gerou insights automáticos — tudo em questão de minutos.
            </p>

            <div class="generated-items">
                <p class="generated-title">O que foi gerado para você:</p>
                <p class="generated-item"><span>✓</span>Documento clínico estruturado no template da sua especialidade</p>
                <p class="generated-item"><span>✓</span>Análise de red flags e alertas clínicos</p>
                <p class="generated-item"><span>✓</span>Diagnósticos diferenciais sugeridos</p>
                <p class="generated-item"><span>✓</span>Códigos CID-10 relacionados</p>
                <p class="generated-item"><span>✓</span>Exames e condutas recomendadas pela IA</p>
            </div>

            <div class="btn-wrap">
                <a href="{{ $appUrl }}/transcripts/{{ $transcriptId }}" class="btn">Ver minha transcrição</a>
            </div>

            <div class="pro-tip">
                💡 <strong>Dica Pro:</strong> Com o plano Pro, você pode refinar o documento com IA — ajustando
                para linguagem técnica, formato SOAP ou com instruções completamente personalizadas.
            </div>

            <div class="secondary-link">
                <a href="{{ $appUrl }}/subscription">Conhecer o plano Pro</a>
            </div>

            <hr class="divider" />

            <p class="text" style="font-size: 13px; color: #6b7280; text-align: center;">
                Continue documentando — cada consulta registrada é um paciente melhor cuidado.
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

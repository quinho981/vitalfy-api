<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sua consulta documentada em menos de 3 minutos</title>
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
        .steps { margin: 24px 0; }
        .step { display: flex; align-items: flex-start; padding: 14px 0; border-bottom: 1px solid #f3f4f6; }
        .step:last-child { border-bottom: none; }
        .step-number { background: #2563eb; color: #ffffff; font-size: 12px; font-weight: 700; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-right: 14px; margin-top: 1px; }
        .step-content { font-size: 14px; color: #374151; line-height: 1.5; }
        .step-content strong { display: block; font-weight: 600; color: #111827; margin-bottom: 2px; }
        .step-content span { color: #6b7280; font-size: 13px; }
        .highlight { background: #eff6ff; border-left: 3px solid #2563eb; padding: 12px 16px; border-radius: 4px; font-size: 13px; color: #1e40af; margin-bottom: 24px; line-height: 1.6; }
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
                Você criou sua conta na Vitalfy ontem.
                Agora é o momento certo para fazer sua primeira transcrição —
                é mais simples do que parece.
            </p>

            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <strong>Grave ou carregue o áudio da consulta</strong>
                        <span>Use o microfone diretamente na plataforma ou envie um arquivo de áudio existente.</span>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <strong>A IA transcreve e estrutura o documento</strong>
                        <span>Escolha o template da sua especialidade e deixe a Vitalfy organizar tudo automaticamente.</span>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <strong>Revise, edite e exporte em PDF</strong>
                        <span>O documento gerado é editável. Ajuste o que precisar e exporte com um clique.</span>
                    </div>
                </div>
            </div>

            <div class="highlight">
                Você tem <strong>10 transcrições gratuitas</strong> disponíveis este mês.
                Comece com aquela consulta que ainda está fresca na memória.
            </div>

            <div class="btn-wrap">
                <a href="{{ $appUrl }}/upload" class="btn">Criar minha primeira transcrição</a>
            </div>

            <hr class="divider" />

            <p class="text" style="font-size: 13px; color: #6b7280; text-align: center;">
                Um médico que documenta bem consulta melhor.<br />
                A Vitalfy garante que você nunca mais perca um detalhe clínico.
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

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>N.O.I.R - Acesso ao Sistema</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>

    <canvas id="noir-bg"></canvas>

    <header class="navbar">
        <div class="nav-container">
            <div class="logo">N.O.I.R</div>
            <nav>
                <ul class="nav-links">
                    <li><a href="home">Início</a></li>
                    <li><a href="organizacao">A Organização</a></li>
                    <li><a href="protocolos">Protocolos</a></li>
                    <li><a href="arquivos">Arquivos</a></li>
                    <li><a href="sistema" class="nav-accent">Acessar Sistema</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero">
        <h1>ACESSO AO SISTEMA</h1>
        <p class="hero-subtitle">
            AUTENTICAÇÃO • AUTORIZAÇÃO • RASTREAMENTO
        </p>
        <div class="divider"></div>
        <p class="hero-description">
            Toda tentativa de acesso é registrada.
        </p>
    </section>

    <section class="section">
        <div class="two-columns">
            <div>
                <h2>Terminal de Autenticação</h2>
                <p>
                    Este sistema é restrito a agentes autorizados da N.O.I.R.
                    Tentativas indevidas resultarão em monitoramento ativo.
                </p>
                <p>
                    Caso você não possua credenciais, encerre a sessão imediatamente.
                </p>
            </div>

            <div class="status-box">
                <p><strong>SISTEMA:</strong> ONLINE</p>
                <p><strong>CRIPTOGRAFIA:</strong> ATIVA</p>
                <p><strong>RASTREAMENTO:</strong> HABILITADO</p>
            </div>
        </div>
    </section>

    <section class="section section-dark">
        <h2>Credenciais</h2>

        <form class="status-box" method="POST" action="#">

            <p>
                <label for="agent">Identificação do Agente</label><br>
                <input type="text" id="agent" name="agent" placeholder="AGT-███"
                       style="width:100%; padding:0.5rem; margin-top:0.3rem;
                       background:#0a0a0a; border:1px solid #2a2a2a; color:#fff;">
            </p>

            <p style="margin-top:1rem;">
                <label for="key">Chave de Acesso</label><br>
                <input type="password" id="key" name="key" placeholder="••••••••"
                       style="width:100%; padding:0.5rem; margin-top:0.3rem;
                       background:#0a0a0a; border:1px solid #2a2a2a; color:#fff;">
            </p>

            <button class="btn" type="submit" style="margin-top:1.5rem;">
                Iniciar Sessão
            </button>
        </form>
    </section>

    <section class="section section-warning">
        <h2>Aviso</h2>
        <p>
            Ao prosseguir, você concorda com o monitoramento completo
            de suas ações, decisões e impacto na linha temporal.
        </p>
        <p>
            A N.O.I.R observa.
        </p>
    </section>

    <footer class="footer">
        <p>© N.O.I.R — Todos os direitos reservados</p>
        <p>Este sistema opera sob protocolos de sigilo</p>
        <p class="version">v0.1-pre-collapse</p>
    </footer>

    <script src="{{ asset('js/noir-bg.js') }}"></script>
</body>
</html>

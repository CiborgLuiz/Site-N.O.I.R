<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>N.O.I.R - Pagina inicial</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/home.css')
</head>
<body>

    <canvas id="noir-bg"></canvas>

    <header class="navbar">
        <div class="nav-container">
            <div class="logo">N.O.I.R</div>
            <nav>
                <ul class="nav-links">
                    <li><a href="home" class="nav-accent">Início</a></li>
                    <li><a href="organizacao">A Organização</a></li>
                    <li><a href="protocolos">Protocolos</a></li>
                    <li><a href="arquivos">Arquivos</a></li>
                    <li><a href="sistema">Acessar Sistema</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero">
        <h1>N.O.I.R</h1>
        <p class="hero-subtitle">
            NÚCLEO DE OPERAÇÕES E INVESTIGAÇÕES DE RUPTURAS
        </p>
        <div class="divider"></div>
        <p class="hero-description">
            Monitorando eventos anômalos desde antes do colapso.
        </p>
    </section>

    <section class="section">
        <div class="two-columns">
            <div>
                <h2>Quem somos</h2>
                <p>
                    A N.O.I.R é uma organização independente criada para identificar,
                    conter e investigar rupturas na realidade conhecidas e desconhecidas.
                </p>
                <p>
                    Desde o evento classificado como <strong>[REDACTED]</strong>,
                    operamos em sigilo absoluto.
                </p>
            </div>

            <div class="status-box">
                <p><strong>STATUS OPERACIONAL:</strong> ATIVO</p>
                <p><strong>NÍVEL DE SIGILO:</strong> MÁXIMO</p>
                <p><strong>LINHA TEMPORAL:</strong> INSTÁVEL</p>
            </div>
        </div>
    </section>

    <section class="section section-dark">
        <h2>Situação Global</h2>

        <div class="cards">
            <div class="card">
                <h3>Rupturas Detectadas</h3>
                <p>NÍVEL: ELEVADO</p>
                <p>STATUS: MONITORAMENTO</p>
            </div>

            <div class="card">
                <h3>Integridade da Realidade</h3>
                <p>ESTADO: COMPROMETIDO</p>
            </div>

            <div class="card">
                <h3>Atividade Anômala</h3>
                <p>TENDÊNCIA: CRESCENTE</p>
            </div>

            <div class="card">
                <h3>Protocolos Ativos</h3>
                <p>CLASSIFICAÇÃO: SIGILOSO</p>
            </div>
        </div>
    </section>

    <section class="section">
        <h2>Divisões da N.O.I.R</h2>

        <div class="cards">
            <div class="card">
                <h3>Observação</h3>
                <p>Monitoramento contínuo de eventos fora do padrão.</p>
            </div>

            <div class="card">
                <h3>Contenção</h3>
                <p>Isolamento e neutralização de rupturas.</p>
            </div>

            <div class="card">
                <h3>Arquivos Temporais</h3>
                <p>Registro e análise de linhas do tempo instáveis.</p>
            </div>

            <div class="card">
                <h3>Operações de Campo</h3>
                <p>Agentes enviados para zonas afetadas.</p>
            </div>
        </div>
    </section>

    <section class="section section-warning">
        <h2>Comunicado Oficial</h2>
        <p>
            A população não está autorizada a interferir em eventos classificados.
            Caso presencie fenômenos incomuns, <strong>não investigue</strong>.
        </p>
        <p>
            A N.O.I.R está observando.
        </p>

        <a href="protocolos" class="btn">Ler Protocolos</a>
    </section>

    <footer class="footer">
        <p>© N.O.I.R — Todos os direitos reservados</p>
        <p>Este sistema opera sob protocolos de sigilo</p>
        <p class="version">v0.1-pre-collapse</p>
    </footer>

    @vite('resources/js/noir-bg.js')
</body>
</html>

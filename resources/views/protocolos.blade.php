<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>N.O.I.R - Protocolos</title>
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
                    <li><a href="home">Início</a></li>
                    <li><a href="organizacao">A Organização</a></li>
                    <li><a href="protocolos" class="nav-accent">Protocolos</a></li>
                    <li><a href="arquivos">Arquivos</a></li>
                    <li><a href="sistema">Acessar Sistema</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero">
        <h1>PROTOCOLOS</h1>
        <p class="hero-subtitle">
            DIRETRIZES • CONTENÇÃO • EMERGÊNCIA
        </p>
        <div class="divider"></div>
        <p class="hero-description">
            Procedimentos existem para impedir o colapso.
        </p>
    </section>

    <section class="section">
        <div class="two-columns">
            <div>
                <h2>Sobre os Protocolos</h2>
                <p>
                    Os protocolos da N.O.I.R definem as ações obrigatórias
                    diante de eventos anômalos, rupturas temporais,
                    entidades desconhecidas ou falhas na realidade.
                </p>
                <p>
                    O descumprimento de um protocolo resulta em
                    consequências irreversíveis.
                </p>
            </div>

            <div class="status-box">
                <p><strong>VERSÃO ATUAL:</strong> 0.1</p>
                <p><strong>AUTORIZAÇÃO:</strong> RESTRITA</p>
                <p><strong>ACESSO:</strong> MONITORADO</p>
            </div>
        </div>
    </section>

    <section class="section section-dark">
        <h2>Níveis de Protocolo</h2>

        <div class="cards">
            <div class="card">
                <h3>Nível 0 — Observação</h3>
                <p>Evento anômalo detectado sem impacto imediato.</p>
                <p>Ação: Monitorar.</p>
            </div>

            <div class="card">
                <h3>Nível 1 — Alerta</h3>
                <p>Anomalia confirmada com risco localizado.</p>
                <p>Ação: Isolamento parcial.</p>
            </div>

            <div class="card">
                <h3>Nível 2 — Contenção</h3>
                <p>Ruptura ativa ou entidade hostil.</p>
                <p>Ação: Intervenção direta.</p>
            </div>

            <div class="card">
                <h3>Nível 3 — Colapso</h3>
                <p>Falha crítica da realidade.</p>
                <p>Ação: Protocolo FINAL.</p>
            </div>
        </div>
    </section>

    <section class="section">
        <h2>Procedimentos Padrão</h2>

        <div class="cards">
            <div class="card">
                <h3>Isolamento</h3>
                <p>
                    Nenhum civil deve permanecer em áreas afetadas.
                    Registros públicos devem ser apagados.
                </p>
            </div>

            <div class="card">
                <h3>Silenciamento</h3>
                <p>
                    Informações vazadas devem ser classificadas
                    como ficção, erro técnico ou fenômeno natural.
                </p>
            </div>

            <div class="card">
                <h3>Registro</h3>
                <p>
                    Todo evento deve ser arquivado, mesmo que
                    não exista mais.
                </p>
            </div>

            <div class="card">
                <h3>Autorização</h3>
                <p>
                    Ações de alto risco exigem aprovação
                    da Diretoria Central.
                </p>
            </div>
        </div>
    </section>

    <section class="section section-warning">
        <h2>Protocolo FINAL</h2>
        <p>
            Em caso de colapso irreversível,
            a prioridade deixa de ser a preservação.
        </p>
        <p>
            A realidade pode ser sacrificada.
        </p>

        <a href="sistema" class="btn">Solicitar Autorização</a>
    </section>

    <footer class="footer">
        <p>© N.O.I.R — Todos os direitos reservados</p>
        <p>Este sistema opera sob protocolos de sigilo</p>
        <p class="version">v0.1-pre-collapse</p>
    </footer>

    <script src="{{ asset('js/noir-bg.js') }}"></script>
</body>
</html>

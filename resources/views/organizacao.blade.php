<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>N.O.I.R - A Organização</title>
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
                    <li><a href="organizacao" class="nav-accent">A Organização</a></li>
                    <li><a href="protocolos">Protocolos</a></li>
                    <li><a href="arquivos">Arquivos</a></li>
                    <li><a href="sistema">Acessar Sistema</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero">
        <h1>A ORGANIZAÇÃO</h1>
        <p class="hero-subtitle">
            ESTRUTURA • ORIGEM • PROPÓSITO
        </p>
        <div class="divider"></div>
        <p class="hero-description">
            Nem todos os mundos deveriam continuar existindo.
        </p>
    </section>

    <section class="section">
        <div class="two-columns">
            <div>
                <h2>Origem da N.O.I.R</h2>
                <p>
                    A N.O.I.R surgiu após eventos que não puderam ser explicados,
                    contidos ou apagados. Quando governos falharam e registros
                    foram destruídos, uma iniciativa paralela nasceu.
                </p>
                <p>
                    Oficialmente, a N.O.I.R não existe.
                    Extraoficialmente, ela sempre esteve observando.
                </p>
            </div>

            <div class="status-box">
                <p><strong>FUNDAÇÃO:</strong> [DADOS OCULTOS]</p>
                <p><strong>AUTORIDADE:</strong> AUTÔNOMA</p>
                <p><strong>EXISTÊNCIA:</strong> NÃO RECONHECIDA</p>
            </div>
        </div>
    </section>

    <section class="section section-dark">
        <h2>Nosso Propósito</h2>

        <div class="cards">
            <div class="card">
                <h3>Identificar</h3>
                <p>Detectar rupturas na realidade antes que se tornem irreversíveis.</p>
            </div>

            <div class="card">
                <h3>Conter</h3>
                <p>Isolar eventos, entidades e zonas instáveis.</p>
            </div>

            <div class="card">
                <h3>Investigar</h3>
                <p>Compreender o que não deveria ser compreendido.</p>
            </div>

            <div class="card">
                <h3>Arquivar</h3>
                <p>Registrar tudo. Mesmo o que nunca será lido.</p>
            </div>
        </div>
    </section>

    <section class="section">
        <h2>Estrutura Interna provocado</h2>

        <div class="cards">
            <div class="card">
                <h3>Diretoria Central</h3>
                <p>Decisões estratégicas e autorizações de alto risco.</p>
            </div>

            <div class="card">
                <h3>Divisão de Operações</h3>
                <p>Agentes de campo, contenção direta e evacuações.</p>
            </div>

            <div class="card">
                <h3>Pesquisa & Anomalias</h3>
                <p>Estudo de entidades, artefatos e fenômenos.</p>
            </div>

            <div class="card">
                <h3>Arquivos Temporais</h3>
                <p>Linhas do tempo alternativas e eventos apagados.</p>
            </div>
        </div>
    </section>

    <section class="section section-warning">
        <h2>Aviso</h2>
        <p>
            Caso você esteja lendo este conteúdo sem autorização,
            sua presença já foi registrada.
        </p>
        <p>
            A N.O.I.R não esquece.
        </p>

        <a href="home" class="btn">Retornar ao Inicio</a>
    </section>

    <footer class="footer">
        <p>© N.O.I.R — Todos os direitos reservados</p>
        <p>Este sistema opera sob protocolos de sigilo</p>
        <p class="version">v0.1-pre-collapse</p>
    </footer>

    <script src="{{ asset('js/noir-bg.js') }}"></script>
</body>
</html>

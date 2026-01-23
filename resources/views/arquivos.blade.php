<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>N.O.I.R - Arquivos</title>
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
                    <li><a href="arquivos" class="nav-accent">Arquivos</a></li>
                    <li><a href="sistema">Acessar Sistema</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero">
        <h1>ARQUIVOS</h1>
        <p class="hero-subtitle">REGISTROS • ENTIDADES • EVENTOS</p>
        <div class="divider"></div>
        <p class="hero-description">
            O acesso a estas informações é restrito.
        </p>
    </section>

    <section class="section">
        <div class="two-columns">
            <div>
                <h2>Banco de Dados Anômalo</h2>
                <p>
                    Os arquivos da N.O.I.R contêm registros de entidades,
                    eventos e falhas que não deveriam existir.
                </p>
                <p>
                    Parte do conteúdo encontra-se criptografado
                    e protegido por protocolos internos.
                </p>
            </div>

            <div class="status-box">
                <p><strong>STATUS:</strong> BLOQUEADO</p>
                <p><strong>CRIPTOGRAFIA:</strong> ATIVA</p>
                <p><strong>ACESSO:</strong> RESTRITO</p>
            </div>
        </div>
    </section>

    <section class="section section-dark">
        <h2>Desbloquear Arquivos</h2>

        @if(session('error'))
            <p style="color:#ff6b6b;">{{ session('error') }}</p>
        @endif

        <form method="POST" action="{{ route('arquivos.unlock') }}" class="status-box">
            @csrf

            <label for="password">Senha de Acesso</label>
            <input type="password"
                   name="password"
                   id="password"
                   placeholder="********************"
                   style="width:100%; margin-top:0.5rem; padding:0.5rem;
                   background:#0a0a0a; border:1px solid #2a2a2a; color:#fff;">

            <button type="submit" class="btn" style="margin-top:1.5rem;">
                Desbloquear
            </button>
        </form>
    </section>

    <footer class="footer">
        <p>© N.O.I.R — Todos os direitos reservados</p>
        <p class="version">v0.1-pre-collapse</p>
    </footer>

    <script src="{{ asset('js/noir-bg.js') }}"></script>
</body>
</html>

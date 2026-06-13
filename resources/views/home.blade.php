<!DOCTYPE html>
<html lang="pt-BR">
<head>
    
    <!-- Básico -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->

    <meta name="description" content="@yield('description', 'N.O.I.R é uma experiência única de Minecraft com mistérios, entidades, anomalias e eventos que desafiam a realidade.')">

    <meta name="keywords" content="Minecraft, N.O.I.R, SMP, Servidor Minecraft, Horror, Mistério, Survival, Modpack">
    <meta name="author" content="Equipe N.O.I.R SMP">

    <!-- Open Graph (Discord, WhatsApp, Facebook, etc) -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="N.O.I.R SMP">
    <meta property="og:title" content="@yield('og_title', 'N.O.I.R SMP')">
    <meta property="og:description" content="@yield('og_description', 'Uma experiência de Minecraft onde a realidade nem sempre é o que parece.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/noir-preview.png') }}">

    <!-- Twitter/X -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'N.O.I.R SMP')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Uma experiência de Minecraft onde a realidade nem sempre é o que parece.')">
    <meta name="twitter:image" content="{{ asset('images/noir-preview.png') }}">

    <!-- Cor do navegador -->
    <meta name="theme-color" content="#4B0082">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Fonte -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Estilo Global dos Links -->
    <style>
        a {
            color: #9d6cff;
            text-decoration: none;
            transition: all .25s ease;
        }

        a:hover {
            color: #c3a6ff;
            text-shadow: 0 0 8px rgba(157,108,255,.6);
        }

        a:active {
            color: #ffffff;
        }

        a.special-link {
            display: inline-block;
            padding: 6px 12px;
            border: 1px solid rgba(157,108,255,.3);
            border-radius: 8px;
            backdrop-filter: blur(10px);
            transition: all .25s ease;
        }

        a.special-link:hover {
            background: rgba(157,108,255,.15);
            border-color: #9d6cff;
            transform: translateY(-2px);
        }
    </style>
    @stack('head')
    <title>N.O.I.R - Página Inicial</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @vite('resources/css/home.css')
</head>
<body
    class="noir-loading"
    style="--noir-logo-image: url('/logo/logo.png');"
>
    @include('partials.site-loader')

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
            Fundada em 1947 após as primeiras aparições.
            Operando nas sombras para preservar a continuidade da realidade.
        </p>
    </section>

    <section class="section">
        <div class="two-columns">
            <div>
                <h2>Origem</h2>
                <p>
                    A N.O.I.R foi idealizada por <strong>Phillip Müller</strong>,
                    membro influente da elite política francesa, após um evento
                    anômalo conhecido como <strong>“Caso do Pescador”</strong>
                    ganhar repercussão internacional.
                </p>
                <p>
                    Convencido de que a humanidade não sobreviveria
                    a novas rupturas da realidade,
                    Müller investiu 15 bilhões para criar
                    uma estrutura independente capaz de conter o desconhecido.
                </p>
                <p>
                    Hoje, sob a liderança de <strong>Oliver Müller</strong>,
                    a organização continua operando com o mesmo objetivo:
                    preservar o que restou da humanidade.
                </p>
            </div>

            <div class="status-box">
                <p><strong>DIRETORIA ATUAL:</strong> OLIVER MÜLLER</p>
                <p><strong>STATUS OPERACIONAL:</strong> ATIVO</p>
                <p><strong>NÍVEL DE SIGILO:</strong> MÁXIMO</p>
                <p><strong>INTEGRIDADE DA REALIDADE:</strong> INSTÁVEL</p>
            </div>
        </div>
    </section>

    <section class="section section-dark">
        <h2>Situação Global</h2>

        <div class="cards">
            <div class="card">
                <h3>Rupturas Ativas</h3>
                <p>NÍVEL: ELEVADO</p>
                <p>TENDÊNCIA: CRESCENTE</p>
            </div>

            <div class="card">
                <h3>Entidades Catalogadas</h3>
                <p>TOTAL: CLASSIFICADO</p>
                <p>CLASSE MÁXIMA: SETIS</p>
            </div>

            <div class="card">
                <h3>Portais Dimensionais</h3>
                <p>STATUS: EXPERIMENTAL</p>
                <p>SUPERVISÃO: NOIR LABS</p>
            </div>

            <div class="card">
                <h3>Protocolos Existenciais</h3>
                <p>PRONTIDÃO: ATIVA</p>
                <p>AUTORIZAÇÃO: DIRETORIA CENTRAL</p>
            </div>
        </div>
    </section>

    <section class="section">
        <h2>Estrutura Operacional</h2>

        <div class="cards">
            <div class="card">
                <h3>N.O.I.R LABS</h3>
                <p>
                    Pesquisa científica avançada e estudo dimensional.
                    Liderado por Florent Durand.
                </p>
            </div>

            <div class="card">
                <h3>N.O.I.R CONTAINMENT</h3>
                <p>
                    Operações de captura e isolamento de entidades.
                    Liderado por Simon Bertrand.
                </p>
            </div>

            <div class="card">
                <h3>N.O.I.R SECURITY</h3>
                <p>
                    Controle de informação, silenciamento
                    e proteção interna.
                </p>
            </div>

            <div class="card">
                <h3>DIRETORIA CENTRAL</h3>
                <p>
                    Tomada de decisões estratégicas
                    e ativação de protocolos irreversíveis.
                </p>
            </div>
        </div>
    </section>

    <section class="section section-warning">
        <h2>Comunicado Oficial</h2>
        <p>
            Caso presencie fenômenos não identificados,
            distorções espaciais, falhas temporais
            ou entidades fora do padrão biológico conhecido,
            <strong>não interaja</strong>.
        </p>
        <p>
            A contenção será realizada.
            A memória pode ser ajustada.
        </p>

        <a href="protocolos" class="btn">Acessar Protocolos Oficiais</a>
    </section>

    <footer class="footer">
        <p>© N.O.I.R - Diretoria Central</p>
        <p>Fundada por Phillip Müller - 1947</p>
        <p class="version">v1.0-operational</p>
    </footer>

    @vite('resources/js/site.js')
    @vite('resources/js/noir-bg.js')
</body>
</html>

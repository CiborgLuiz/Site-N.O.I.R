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
    <title>N.O.I.R - A Organização</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @vite('resources/css/home.css')
</head>
<body
    class="noir-loading"
    style="--noir-logo-image: url('/images/logo.png');"
>
    @include('partials.site-loader')

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
            FUNDAÇÃO • ESTRUTURA • CONTINUIDADE
        </p>
        <div class="divider"></div>
        <p class="hero-description">
            Criada para impedir o colapso da realidade.
            Mantida para garantir que ela continue existindo.
        </p>
    </section>

    <section class="section">
        <div class="two-columns">
            <div>
                <h2>Fundação</h2>
                <p>
                    A N.O.I.R foi fundada em 1947 por <strong>Phillip Müller</strong>,
                    após o evento conhecido como <strong>Incidente do Pescador</strong>
                    expor publicamente uma ruptura na realidade.
                </p>
                <p>
                    Percebendo que governos não possuíam estrutura,
                    preparo ou discrição suficientes para lidar com
                    ameaças além da compreensão humana,
                    Müller utilizou sua influência política e 15 bilhões
                    de sua fortuna pessoal para estabelecer
                    uma organização autônoma e independente.
                </p>
                <p>
                    Oficialmente, a N.O.I.R não existe.
                    Extraoficialmente, ela opera há décadas
                    em todas as regiões do mundo.
                </p>
            </div>

            <div class="status-box">
                <p><strong>FUNDAÇÃO:</strong> 1947</p>
                <p><strong>FUNDADOR:</strong> PHILLIP MÜLLER</p>
                <p><strong>DIRETOR ATUAL:</strong> OLIVER MÜLLER</p>
                <p><strong>STATUS:</strong> OPERACIONAL</p>
            </div>
        </div>
    </section>

    <section class="section section-dark">
        <h2>Liderança Atual</h2>

        <div class="two-columns">
            <div>
                <p>
                    Após a morte de Phillip Müller em 1976,
                    a organização passou por um período de
                    reestruturação interna.
                </p>
                <p>
                    Anos depois, <strong>Oliver Müller</strong>,
                    preparado desde a juventude para assumir
                    a Diretoria Central, consolidou o comando
                    aos 22 anos após provar sua capacidade
                    em situações críticas de escalonamento máximo.
                </p>
                <p>
                    Sob sua liderança, os protocolos foram
                    modernizados e o nível de intervenção
                    tornou-se mais direto.
                </p>
            </div>

            <div class="status-box">
                <p><strong>POLÍTICA ATUAL:</strong> CONTENÇÃO ATIVA</p>
                <p><strong>PROTOCOLO EXISTENCIAL:</strong> PRONTO</p>
                <p><strong>CLASSE MÁXIMA:</strong> SETIS</p>
            </div>
        </div>
    </section>

    <section class="section">
        <h2>Estrutura Operacional</h2>

        <div class="cards">

            <div class="card">
                <h3>DIRETORIA CENTRAL</h3>
                <p>
                    Responsável por decisões estratégicas globais,
                    autorizações irreversíveis e ativação
                    de protocolos existenciais.
                </p>
            </div>

            <div class="card">
                <h3>N.O.I.R LABS</h3>
                <p>
                    Liderado por Florent Durand.
                    Pesquisa científica avançada,
                    estudo dimensional e desenvolvimento
                    do Projeto Portais.
                </p>
            </div>

            <div class="card">
                <h3>N.O.I.R CONTAINMENT</h3>
                <p>
                    Liderado por Simon Bertrand.
                    Operações de campo, captura de entidades
                    e contenção de alto risco.
                </p>
            </div>

            <div class="card">
                <h3>N.O.I.R SECURITY</h3>
                <p>
                    Contrainteligência, silenciamento,
                    controle de informação e proteção interna.
                </p>
            </div>

        </div>
    </section>

    <section class="section section-dark">
        <h2>Nosso Propósito</h2>

        <div class="cards">
            <div class="card">
                <h3>Identificar</h3>
                <p>Detectar rupturas antes que se tornem irreversíveis.</p>
            </div>

            <div class="card">
                <h3>Conter</h3>
                <p>Isolar entidades e eventos classificados.</p>
            </div>

            <div class="card">
                <h3>Compreender</h3>
                <p>Estudar o fenômeno sem permitir que ele nos estude.</p>
            </div>

            <div class="card">
                <h3>Preservar</h3>
                <p>Garantir a continuidade da realidade conhecida.</p>
            </div>
        </div>
    </section>

    <section class="section section-warning">
        <h2>Declaração Oficial</h2>
        <p>
            A existência da N.O.I.R não será reconhecida.
            Eventos classificados serão negados,
            distorcidos ou apagados.
        </p>
        <p>
            Caso você tenha acessado este conteúdo sem autorização,
            sua atividade já foi registrada.
        </p>

        <a href="home" class="btn">Retornar ao Início</a>
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

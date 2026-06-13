<!DOCTYPE html>
<html lang="pt-BR">
<head>
    
    <!-- Básico -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->

    <meta name="description" content="@yield('description', 'N.O.I.R é uma experiência única de Minecraft com mistérios, entidades, anomalias e eventos que desafiam a realidade.')">

    <meta name="keywords" content="Minecraft, N.O.I.R, SMP, Servidor Minecraft, Horror, Mistério, Survival, Modpack">
    <meta name="author" content="Equipe N.O.I.R">

    <!-- Open Graph (Discord, WhatsApp, Facebook, etc) -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="N.O.I.R">
    <meta property="og:title" content="@yield('og_title', 'N.O.I.R')">
    <meta property="og:description" content="@yield('og_description', 'Uma experiência de Minecraft onde a realidade nem sempre é o que parece.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/noir-preview.png') }}">

    <!-- Twitter/X -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'N.O.I.R')">
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
    <title>N.O.I.R - Terminal</title>
    @vite('resources/css/terminal.css')
    <link rel="icon" href="{{ asset('favicon.ico') }}">
</head>
<body>
    <canvas id="matrix-bg" aria-hidden="true"></canvas>
    <div class="scanlines" aria-hidden="true"></div>

    <div class="terminal-wrapper">
        <div class="terminal-window" id="terminal-window">
            <div class="terminal-titlebar">
                <div class="title-left glitch-text" data-text="NOIR//TERMINAL">NOIR//TERMINAL</div>
                <div class="title-right">SECURE LINK • ACCESS MONITORED</div>
            </div>

            <div class="terminal-header">
                <div class="terminal-logo">
<pre class="ascii-logo">███╗   ██╗    ██████╗    ██╗   ██████╗ 
████╗  ██║   ██╔═══██╗   ██║   ██╔══██╗
██╔██╗ ██║   ██║   ██║   ██║   ██████╔╝
██║╚██╗██║   ██║   ██║   ██║   ██╔══██╗
██║ ╚████║██╗╚██████╔╝██╗██║██╗██║  ██║
╚═╝  ╚═══╝╚═╝ ╚═════╝ ╚═╝╚═╝╚═╝╚═╝  ╚═╝</pre>
                    <div class="ascii-subtitle">Neural Observation &amp; Interference Registry</div>
                    <div class="ascii-subtitle">Secure Terminal Interface</div>
                </div>

                <div class="system-info">
                    <div class="system-title">oliver@noir-terminal</div>
                    <div class="system-grid">
                        <div class="system-row">
                            <span class="label">OS</span>
                            <span class="value">NOIR OS (secure build)</span>
                        </div>
                        <div class="system-row">
                            <span class="label">Kernel</span>
                            <span class="value">6.18-noir</span>
                        </div>
                        <div class="system-row">
                            <span class="label">Uptime</span>
                            <span class="value">unknown</span>
                        </div>
                        <div class="spacer"></div>
                        <div class="system-row">
                            <span class="label">WM</span>
                            <span class="value">NoirOS interface</span>
                        </div>
                        <div class="system-row">
                            <span class="label">Terminal</span>
                            <span class="value">NoirShell v1.0</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="terminal-screen" id="terminal-screen" tabindex="0" role="textbox" aria-label="NOIR terminal">
                <div class="terminal-output" id="terminal-output">
                    <div class="line dim">Digite help para a lista de comandos.</div>
                </div>

                <div class="terminal-input-line">
                    <span class="prompt">&gt;</span>
                    <span class="input-text" id="input-text"></span>
                    <span class="cursor" id="cursor">█</span>
                </div>

                <input id="hidden-input" class="hidden-input" autocomplete="off" autocapitalize="off" spellcheck="false">
            </div>
        </div>
    </div>

    @vite('resources/js/terminal.js')
</body>
</html>

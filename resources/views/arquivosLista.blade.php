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

    <title>N.O.I.R — Arquivos</title>
    @vite('resources/css/arquivos.css')
    <link rel="icon" href="{{ asset('favicon.ico') }}">
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
                    <li><a href="/home">Início</a></li>
                    <li><a href="/organizacao">A Organização</a></li>
                    <li><a href="/protocolos">Protocolos</a></li>
                    <li><a href="/arquivos" class="nav-accent">Arquivos</a></li>
                    <li><a href="/sistema">Acessar Sistema</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero hero-small">
        <h1>ARQUIVOS</h1>
        <p class="hero-subtitle">BASE DE DADOS CONFIDENCIAL</p>
        <div class="divider"></div>
        <p class="hero-description">
            Classificação ativa por nível de risco.
        </p>
    </section>

    <section class="section section-dark">

        @php
            function normalizeSlug($text)
            {
                $text = strtolower($text);
                $text = str_replace(['í'], ['i'], $text);
                return $text;
            }

            $order = [
                'neutralizado' => 1,
                'seguro' => 2,
                'totin' => 3,
                'isop' => 4,
                'denus' => 5,
                'setis' => 6,
            ];

            $sortedArchives = $archives->sortBy(function ($archive) use ($order) {
                $slug = normalizeSlug($archive->classification);
                return $order[$slug] ?? 99;
            });
        @endphp

        <div class="archive-grid">
            @foreach ($sortedArchives as $archive)
                @php
                    $classSlug = normalizeSlug($archive->classification);
                @endphp

                <div class="archive-card class-{{ $classSlug }}">
                    <div class="archive-image">
                        <img src="{{ $archive->image_url }}" alt="{{ $archive->name }}">
                    </div>

                    <div class="archive-info">
                        <h3>{{ $archive->name }}</h3>
                        <p class="archive-id">ID: {{ $archive->identifier }}</p>
                        <span class="archive-classification">
                            {{ strtoupper($archive->classification) }}
                        </span>
                        <p class="archive-description">
                            {{ $archive->description }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

    </section>

    <footer class="footer">
        <p>© N.O.I.R - Diretoria Central</p>
        <p>Fundada por Phillip Müller - 1947</p>
        <p class="version">v1.0-operational</p>
    </footer>

    <div id="setis-alert">⚠ NÍVEL DE RISCO MÁXIMO</div>

    <audio id="audio-neutralizado" src="{{ asset('sounds/neutralizado.mp3') }}" preload="auto" loop></audio>
    <audio id="audio-seguro" src="{{ asset('sounds/seguro.mp3') }}" preload="auto" loop></audio>
    <audio id="audio-totin" src="{{ asset('sounds/totin.mp3') }}" preload="auto" loop></audio>
    <audio id="audio-isop" src="{{ asset('sounds/isop.mp3') }}" preload="auto" loop></audio>
    <audio id="audio-denus" src="{{ asset('sounds/denus.mp3') }}" preload="auto" loop></audio>
    <audio id="audio-setis" src="{{ asset('sounds/setis.mp3') }}" preload="auto" loop></audio>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            let audioContext = new(window.AudioContext || window.webkitAudioContext)();
            let activeNode = null;
            let deactivateTimer = null;

            const audioMap = {
                neutralizado: "audio-neutralizado",
                seguro: "audio-seguro",
                totin: "audio-totin",
                isop: "audio-isop",
                denus: "audio-denus",
                setis: "audio-setis"
            };

            const nodes = {};

            function setupAudio(type) {

                if (nodes[type]) return nodes[type];

                const audioEl = document.getElementById(audioMap[type]);
                const source = audioContext.createMediaElementSource(audioEl);
                const gain = audioContext.createGain();

                gain.gain.value = 0;

                source.connect(gain);
                gain.connect(audioContext.destination);

                nodes[type] = {
                    audioEl,
                    gain
                };

                return nodes[type];
            }

            function activate(type) {

                if (!audioMap[type]) return;

                if (activeNode) deactivate();

                document.body.classList.remove(
                    "neutralizado-active",
                    "seguro-active",
                    "totin-active",
                    "isop-active",
                    "denus-active",
                    "setis-active"
                );

                document.body.classList.add(type + "-active");

                const node = setupAudio(type);

                if (deactivateTimer) {
                    clearTimeout(deactivateTimer);
                    deactivateTimer = null;
                }

                node.gain.gain.cancelScheduledValues(audioContext.currentTime);
                node.audioEl.playbackRate = 0.98 + Math.random() * 0.04;

                node.gain.gain.setValueAtTime(0, audioContext.currentTime);
                node.gain.gain.linearRampToValueAtTime(0.25, audioContext.currentTime + 1);

                node.audioEl.play().catch(() => {});

                activeNode = node;
            }

            function deactivate() {

                if (!activeNode) return;

                const node = activeNode;
                activeNode = null;

                node.gain.gain.cancelScheduledValues(audioContext.currentTime);
                node.gain.gain.linearRampToValueAtTime(0, audioContext.currentTime + 0.5);

                if (deactivateTimer) {
                    clearTimeout(deactivateTimer);
                }
                deactivateTimer = setTimeout(() => {
                    node.audioEl.pause();
                    node.audioEl.currentTime = 0;
                }, 600);

                document.body.classList.remove(
                    "neutralizado-active",
                    "seguro-active",
                    "totin-active",
                    "isop-active",
                    "denus-active",
                    "setis-active"
                );
            }

            document.querySelectorAll(".archive-card").forEach(card => {

                card.addEventListener("mouseenter", () => {

                    audioContext.resume();

                    const className = Array.from(card.classList)
                        .find(c => c.startsWith("class-"));

                    if (!className) return;

                    const type = className.replace("class-", "");

                    activate(type);
                });

                card.addEventListener("mouseleave", deactivate);

            });

            window.addEventListener("load", () => {
                audioContext.resume();
            });

        });
    </script>

    @vite('resources/js/site.js')
    @vite('resources/js/noir-bg.js')

</body>

</html>

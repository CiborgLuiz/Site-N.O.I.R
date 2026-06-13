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
    <title>N.O.I.R - Sistema</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @vite('resources/css/home.css')
    @vite('resources/css/system.css')
</head>

<body
    class="system-page noir-loading"
    style="--noir-logo-image: url('/logo/logo.png');"
>
    @include('partials.site-loader')

    <canvas id="noir-bg"></canvas>

    <audio id="xp-sound" preload="auto">
        <source src="{{ asset('sounds/windows-xp-startup.mp3') }}" type="audio/mpeg">
    </audio>

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

    <section class="system-wrapper">
        <div class="monitor-frame">
            <div id="boot-screen" class="boot-screen">
                <div class="boot-text" id="boot-text"></div>
            </div>
            <div
                id="desktop"
                class="desktop hidden"
                style="--system-wallpaper-image: url('{{ asset('images/windows-xp-wallpaper.jpg') }}');"
            >
                <a class="desktop-icon" href="{{ url('/sistema/terminal') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-terminal" viewBox="0 0 16 16">
                        <path
                            d="M6 9a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3A.5.5 0 0 1 6 9M3.854 4.146a.5.5 0 1 0-.708.708L4.793 6.5 3.146 8.146a.5.5 0 1 0 .708.708l2-2a.5.5 0 0 0 0-.708z" />
                        <path
                            d="M2 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1z" />
                    </svg>
                    <span>Terminal</span>
                </a>

                @foreach ($folders as $folder)
                    <div class="desktop-icon" onclick="openFolder({{ $folder->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-folder-fill" viewBox="0 0 16 16">
                            <path
                                d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a2 2 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3m-8.322.12q.322-.119.684-.12h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981z" />
                        </svg>
                        <span>{{ $folder->name }}</span>
                    </div>
                @endforeach

                <div id="windows"></div>
            </div>

        </div>
    </section>

    @vite('resources/js/site.js')
    @vite('resources/js/noir-bg.js')

    <script>
        const bootLines = [
            "Iniciando N.O.I.R OS...",
            "Verificando integridade do núcleo...",
            "Carregando módulos temporais...",
            "Sincronizando registros anômalos...",
            "Acesso autorizado."
        ];

        const bootText = document.getElementById("boot-text");
        const bootScreen = document.getElementById("boot-screen");
        const desktop = document.getElementById("desktop");
        const xpSound = document.getElementById("xp-sound");

        let lineIndex = 0;
        let bootStarted = false;

        function typeLine(text, callback) {
            let i = 0;
            const p = document.createElement("p");
            bootText.appendChild(p);

            const interval = setInterval(() => {
                p.textContent += text.charAt(i);
                i++;
                if (i >= text.length) {
                    clearInterval(interval);
                    setTimeout(callback, 400);
                }
            }, 25);
        }

        function runBoot() {
            if (lineIndex < bootLines.length) {
                typeLine(bootLines[lineIndex], () => {
                    lineIndex++;
                    runBoot();
                });
            } else {
                setTimeout(finishBoot, 800);
            }
        }

        function finishBoot() {
            bootScreen.style.opacity = "0";
            setTimeout(() => {
                bootScreen.style.display = "none";
                desktop.classList.remove("hidden");
                desktop.classList.add("fade-in");
                playStartupSound();
            }, 600);
        }

        function playStartupSound() {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            const ctx = new AudioContext();
            const source = ctx.createMediaElementSource(xpSound);
            const gain = ctx.createGain();

            source.connect(gain);
            gain.connect(ctx.destination);

            gain.gain.value = 0;
            xpSound.play().then(() => {
                gain.gain.linearRampToValueAtTime(0.6, ctx.currentTime + 1.5);
            }).catch(err => {
                console.warn("Autoplay bloqueado:", err);
            });
        }
        function beginBoot() {
            if (bootStarted || !document.body.classList.contains("noir-loaded")) {
                return;
            }

            bootStarted = true;
            runBoot();
        }

        document.addEventListener("noir:loader-complete", beginBoot, {
            once: true
        });

        document.addEventListener("click", function unlock() {
            beginBoot();

            if (bootStarted) {
                document.removeEventListener("click", unlock);
            }
        });

        function openFolder(folderId) {
            fetch(`/sistema/pasta/${folderId}`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('windows')
                        .insertAdjacentHTML('beforeend', html);
                    enableDrag();
                });
        }

        function openFile(type, name, content, path) {

            let body = '';

            if (type === 'txt') {
                body = `<pre class="txt-viewer">${escapeHtml(content)}</pre>`;
            }

            if (type === 'png' || type === 'jpg') {
                body = `<img src="${escapeHtml(path)}" class="img-viewer">`;
            }

            if (type === 'mp3') {
                body = `<audio controls autoplay src="${escapeHtml(path)}"></audio>`;
            }

            if (type === 'mp4') {
                body = `
            <iframe src="${escapeHtml(content)}" 
                width="100%" height="260"
                allowfullscreen></iframe>
        `;
            }

            const win = document.createElement("div");
            win.className = "window draggable fade-window";
            win.style.top = "120px";
            win.style.left = "120px";
            win.innerHTML = `
        <div class="window-header">
            <span>${escapeHtml(name)}</span>
            <button onclick="this.closest('.window').remove()">✕</button>
        </div>
        <div class="window-body">${body}</div>
    `;

            document.getElementById('windows').appendChild(win);
            enableDrag();
        }

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, char => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;',
            }[char]));
        }

        function enableDrag() {
            document.querySelectorAll('.draggable').forEach(win => {

                const header = win.querySelector('.window-header');
                let offsetX = 0;
                let offsetY = 0;
                let dragging = false;

                header.addEventListener('mousedown', e => {
                    dragging = true;
                    offsetX = e.clientX - win.offsetLeft;
                    offsetY = e.clientY - win.offsetTop;
                    win.style.zIndex = Date.now();
                });

                document.addEventListener('mousemove', e => {
                    if (!dragging) return;
                    win.style.left = (e.clientX - offsetX) + 'px';
                    win.style.top = (e.clientY - offsetY) + 'px';
                });

                document.addEventListener('mouseup', () => {
                    dragging = false;
                });

            });
        }
    </script>

</body>

</html>

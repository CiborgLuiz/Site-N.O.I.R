<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>N.O.I.R - Sistema</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @vite('resources/css/home.css')
    @vite('resources/css/system.css')
</head>

<body class="system-page">

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
            <div id="desktop" class="desktop hidden">

                @foreach ($folders as $folder)
                    <div class="desktop-icon" onclick="openFolder({{ $folder->id }})">
                        <img src="{{ asset('images/folder.png') }}">
                        <span>{{ $folder->name }}</span>
                    </div>
                @endforeach

                <div id="windows"></div>
            </div>

        </div>
    </section>

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
        document.addEventListener("click", function unlock() {
            runBoot();
            document.removeEventListener("click", unlock);
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
                body = `<pre class="txt-viewer">${content}</pre>`;
            }

            if (type === 'png' || type === 'jpg') {
                body = `<img src="${path}" class="img-viewer">`;
            }

            if (type === 'mp3') {
                body = `<audio controls autoplay src="${path}"></audio>`;
            }

            if (type === 'mp4') {
                body = `
            <iframe src="${content}" 
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
            <span>${name}</span>
            <button onclick="this.closest('.window').remove()">✕</button>
        </div>
        <div class="window-body">${body}</div>
    `;

            document.getElementById('windows').appendChild(win);
            enableDrag();
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

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>N.O.I.R — Sistema</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS base (HOME + SISTEMA) -->
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/system.css') }}">
</head>
<body>

<!-- FUNDO INTERATIVO -->
<canvas id="noir-bg"></canvas>

<!-- NAVBAR (IGUAL HOME) -->
<header class="navbar">
    <div class="nav-container">
        <div class="logo">N.O.I.R</div>
        <nav>
            <ul class="nav-links">
                <li><a href="/home">Início</a></li>
                <li><a href="/organizacao">A Organização</a></li>
                <li><a href="/protocolos">Protocolos</a></li>
                <li><a href="/arquivos">Arquivos</a></li>
                <li><a href="/sistema" class="nav-accent">Sistema</a></li>
            </ul>
        </nav>
    </div>
</header>

<!-- ÁREA DO SISTEMA -->
<section class="system-wrapper">

    <!-- MOLDURA DO MONITOR -->
    <div class="monitor-frame">

        <!-- BOOT SCREEN -->
        <div id="boot-screen" class="boot-screen">
            <div class="boot-text">
                <p>Iniciando N.O.I.R OS...</p>
                <p>Verificando integridade do núcleo</p>
                <p>Carregando módulos temporais</p>
            </div>
        </div>

        <!-- DESKTOP -->
        <div id="desktop" class="desktop hidden">

            <!-- ÍCONES DE PASTAS (BANCO DE DADOS) -->
            @foreach($folders as $folder)
                <div class="desktop-icon" onclick="openFolder({{ $folder->id }})">
                    <img src="{{ asset('images/folder.png') }}" alt="Pasta">
                    <span>{{ $folder->name }}</span>
                </div>
            @endforeach

            <!-- JANELAS ABERTAS -->
            <div id="windows"></div>

        </div>

    </div>

</section>

<!-- JS FUNDO -->
<script src="{{ asset('js/noir-bg.js') }}"></script>

<!-- JS DO SISTEMA -->
<script>
/* =========================
   BOOT DO SISTEMA
========================= */
setTimeout(() => {
    const boot = document.getElementById('boot-screen');
    const desktop = document.getElementById('desktop');

    boot.style.display = 'none';
    desktop.classList.remove('hidden');
}, 3000);

/* =========================
   ABRIR PASTA
========================= */
function openFolder(folderId) {
    fetch(`/sistema/pasta/${folderId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('windows')
                .insertAdjacentHTML('beforeend', html);

            const windowEl = document.getElementById(`folder-${folderId}`);
            makeDraggable(windowEl);
        });
}

/* =========================
   FECHAR JANELA
========================= */
function closeWindow(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

/* =========================
   ABRIR ARQUIVO
========================= */
function openFile(type, content, name = 'Arquivo') {
    const win = document.createElement('div');
    win.className = 'window';
    win.innerHTML = `
        <div class="window-header">
            ${name}
            <button onclick="this.closest('.window').remove()">✖</button>
        </div>
        <div class="window-body">
            ${renderFile(type, content)}
        </div>
    `;

    document.getElementById('windows').appendChild(win);
    makeDraggable(win);
}

/* =========================
   RENDERIZAÇÃO POR TIPO
========================= */
function renderFile(type, content) {
    switch(type) {
        case 'txt':
            return `<pre class="txt-viewer">${content}</pre>`;

        case 'png':
            return `<img src="${content}" class="img-viewer">`;

        case 'mp3':
            return `<audio controls src="${content}"></audio>`;

        case 'mp4':
            return `
                <iframe 
                    width="100%" 
                    height="240" 
                    src="${content}" 
                    frameborder="0"
                    allowfullscreen>
                </iframe>
            `;
        default:
            return `<p>Formato não suportado</p>`;
    }
}

/* =========================
   JANELAS ARRASTÁVEIS
========================= */
function makeDraggable(windowEl) {
    const header = windowEl.querySelector('.window-header');
    let offsetX = 0;
    let offsetY = 0;
    let isDragging = false;

    header.addEventListener('mousedown', e => {
        isDragging = true;
        offsetX = e.clientX - windowEl.offsetLeft;
        offsetY = e.clientY - windowEl.offsetTop;
        windowEl.style.zIndex = Date.now();
    });

    document.addEventListener('mousemove', e => {
        if (!isDragging) return;
        windowEl.style.left = (e.clientX - offsetX) + 'px';
        windowEl.style.top = (e.clientY - offsetY) + 'px';
    });

    document.addEventListener('mouseup', () => {
        isDragging = false;
    });
}
</script>

</body>
</html>

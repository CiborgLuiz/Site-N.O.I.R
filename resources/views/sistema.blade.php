<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>N.O.I.R — Sistema</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/system.css') }}">
</head>
<body>

<canvas id="noir-bg"></canvas>

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

<section class="system-wrapper">
    <div class="monitor-frame">

        <div id="boot-screen" class="boot-screen">
            <div class="boot-text">
                <p>Iniciando N.O.I.R OS...</p>
                <p>Verificando integridade do núcleo</p>
                <p>Carregando módulos temporais</p>
            </div>
        </div>

        <div id="desktop" class="desktop hidden">

            @foreach($folders as $folder)
                <div class="desktop-icon" onclick="openFolder({{ $folder->id }})">
                    <img src="{{ asset('images/folder.png') }}">
                    <span>{{ $folder->name }}</span>
                </div>
            @endforeach

            <div id="windows"></div>
        </div>

    </div>
</section>

<script src="{{ asset('js/noir-bg.js') }}"></script>

<script>
/* BOOT */
setTimeout(() => {
    document.getElementById('boot-screen').style.display = 'none';
    document.getElementById('desktop').classList.remove('hidden');
}, 3000);

/* ABRIR PASTA */
function openFolder(folderId) {
    fetch(`/sistema/pasta/${folderId}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('windows').insertAdjacentHTML('beforeend', html);
            enableDrag();
        });
}

/* ABRIR ARQUIVO */
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

    const win = `
        <div class="window draggable">
            <div class="window-header">
                <span>${name}</span>
                <button onclick="this.closest('.window').remove()">✕</button>
            </div>
            <div class="window-body">${body}</div>
        </div>
    `;

    document.getElementById('windows').insertAdjacentHTML('beforeend', win);
    enableDrag();
}

/* DRAG WINDOWS */
function enableDrag() {
    document.querySelectorAll('.draggable').forEach(win => {
        const header = win.querySelector('.window-header');
        let offsetX = 0, offsetY = 0, dragging = false;

        header.onmousedown = e => {
            dragging = true;
            offsetX = e.clientX - win.offsetLeft;
            offsetY = e.clientY - win.offsetTop;
            win.style.zIndex = Date.now();
        };

        document.onmousemove = e => {
            if (!dragging) return;
            win.style.left = (e.clientX - offsetX) + 'px';
            win.style.top = (e.clientY - offsetY) + 'px';
        };

        document.onmouseup = () => dragging = false;
    });
}
</script>

</body>
</html>

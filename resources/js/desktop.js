function openFolder(id) {
    fetch(`/folder/${id}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('windows').innerHTML += html;
            makeDraggable(document.getElementById(`folder-${id}`));
        });
}

function closeWindow(id) {
    document.getElementById(id).remove();
}

function openFile(type, content) {
    let win = document.createElement('div');
    win.className = 'window';
    win.innerHTML = `
        <div class="window-header">
            Arquivo
            <button onclick="this.parentElement.parentElement.remove()">X</button>
        </div>
        <div class="window-body">
            ${renderFile(type, content)}
        </div>
    `;
    document.getElementById('windows').appendChild(win);
    makeDraggable(win);
}

function renderFile(type, content) {
    switch(type) {
        case 'txt': return `<pre>${content}</pre>`;
        case 'png': return `<img src="${content}" width="100%">`;
        case 'mp3': return `<audio controls src="${content}"></audio>`;
        case 'mp4': return `<iframe width="100%" height="200" src="${content}" allowfullscreen></iframe>`;
    }
}

function makeDraggable(el) {
    let header = el.querySelector('.window-header');
    let offsetX, offsetY;

    header.onmousedown = e => {
        offsetX = e.clientX - el.offsetLeft;
        offsetY = e.clientY - el.offsetTop;
        document.onmousemove = e => {
            el.style.left = (e.clientX - offsetX) + 'px';
            el.style.top = (e.clientY - offsetY) + 'px';
        };
        document.onmouseup = () => document.onmousemove = null;
    };
}

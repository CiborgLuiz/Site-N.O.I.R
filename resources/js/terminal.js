(() => {
    const canvas = document.getElementById("matrix-bg");
    const ctx = canvas.getContext("2d");
    const chars = "NOIR01█▓▒░";
    const fontFamily = "\"JetBrains Mono\", \"Fira Code\", \"SFMono-Regular\", Consolas, \"Liberation Mono\", Menlo, monospace";
    let width = 0;
    let height = 0;
    let columns = 0;
    let drops = [];
    let lastTime = 0;
    const fontSize = 16;

    function resizeCanvas() {
        const dpr = window.devicePixelRatio || 1;
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = width * dpr;
        canvas.height = height * dpr;
        canvas.style.width = `${width}px`;
        canvas.style.height = `${height}px`;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        columns = Math.floor(width / fontSize) + 1;
        drops = Array.from({ length: columns }, () => Math.floor(Math.random() * (height / fontSize)));
    }

    function drawMatrix(time) {
        if (time - lastTime < 33) {
            requestAnimationFrame(drawMatrix);
            return;
        }

        lastTime = time;
        ctx.fillStyle = "rgba(0, 0, 0, 0.08)";
        ctx.fillRect(0, 0, width, height);

        ctx.font = `${fontSize}px ${fontFamily}`;
        ctx.fillStyle = "rgba(57, 255, 136, 0.65)";
        ctx.shadowColor = "rgba(57, 255, 136, 0.4)";
        ctx.shadowBlur = 8;

        for (let i = 0; i < drops.length; i += 1) {
            const char = chars.charAt(Math.floor(Math.random() * chars.length));
            const x = i * fontSize;
            const y = drops[i] * fontSize;
            ctx.fillText(char, x, y);

            if (y > height && Math.random() > 0.975) {
                drops[i] = 0;
            }

            drops[i] += 1;
        }

        ctx.shadowBlur = 0;
        requestAnimationFrame(drawMatrix);
    }

    resizeCanvas();
    window.addEventListener("resize", resizeCanvas);
    requestAnimationFrame(drawMatrix);

    const output = document.getElementById("terminal-output");
    const inputText = document.getElementById("input-text");
    const hiddenInput = document.getElementById("hidden-input");
    const terminalWindow = document.getElementById("terminal-window");
    const terminalScreen = document.getElementById("terminal-screen");

    let buffer = "";
    const history = [];
    let historyIndex = -1;

    let commands = {
        help: () => [
            "Comandos disponíveis:",
            "help, clear"
        ],

        clear: () => "__CLEAR__"
    };

    fetch("/api/terminal-commands")
        .then((response) => response.json())
        .then((databaseCommands) => {

            databaseCommands.forEach((command) => {

                commands[command.command.toLowerCase()] = () => {

                    return command.response
                        .split("\n");

                };

            });

        })
        .catch(() => { });

    function renderBuffer() {
        inputText.textContent = buffer;
    }

    function printLine(text, className = "") {
        const line = document.createElement("div");
        line.className = `line${className ? " " + className : ""}`;
        line.textContent = text;
        output.appendChild(line);
        output.scrollTop = output.scrollHeight;
    }

    function handleCommand() {
        const raw = buffer;
        const command = buffer.trim().toLowerCase();

        printLine(`> ${raw}`, "command");

        if (command.length === 0) {
            buffer = "";
            renderBuffer();
            return;
        }

        history.push(raw);
        historyIndex = history.length;

        if (commands[command]) {
            const result = commands[command]();
            if (result === "__CLEAR__") {
                output.innerHTML = "";
            } else if (Array.isArray(result)) {
                result.forEach((line) => printLine(line));
            } else if (typeof result === "string") {
                printLine(result);
            }
        } else {
            printLine("Comando não reconhecido.");
        }

        buffer = "";
        renderBuffer();
    }

    function triggerGlitch() {
        document.body.classList.add("glitch-active");
        window.setTimeout(() => {
            document.body.classList.remove("glitch-active");
        }, 900);
    }

    function focusInput() {
        hiddenInput.focus();
    }

    terminalScreen.addEventListener("click", focusInput);
    terminalWindow.addEventListener("click", focusInput);

    hiddenInput.addEventListener("keydown", (event) => {
        if (event.key === "Enter") {
            event.preventDefault();
            handleCommand();
            return;
        }

        if (event.key === "Backspace") {
            event.preventDefault();
            buffer = buffer.slice(0, -1);
            renderBuffer();
            return;
        }

        if (event.key === "ArrowUp") {
            event.preventDefault();
            if (!history.length) {
                return;
            }
            historyIndex = Math.max(0, historyIndex - 1);
            buffer = history[historyIndex] || "";
            renderBuffer();
            return;
        }

        if (event.key === "ArrowDown") {
            event.preventDefault();
            if (!history.length) {
                return;
            }
            historyIndex = Math.min(history.length, historyIndex + 1);
            buffer = history[historyIndex] || "";
            renderBuffer();
            return;
        }

        if (event.ctrlKey && event.key.toLowerCase() === "l") {
            event.preventDefault();
            output.innerHTML = "";
            return;
        }

        if (event.key === "Tab") {
            event.preventDefault();
            return;
        }

        if (event.key.length === 1 && !event.altKey && !event.ctrlKey && !event.metaKey) {
            event.preventDefault();
            buffer += event.key;
            renderBuffer();
        }
    });

    hiddenInput.addEventListener("paste", (event) => {
        event.preventDefault();
        const pasted = (event.clipboardData || window.clipboardData).getData("text");
        if (!pasted) {
            return;
        }
        buffer += pasted.replace(/[\r\n]+/g, " ");
        renderBuffer();
    });

    focusInput();
})();

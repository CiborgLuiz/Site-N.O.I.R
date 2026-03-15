<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>N.O.I.R - Terminal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

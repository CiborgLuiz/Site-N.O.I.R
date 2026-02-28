<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>N.O.I.R - Protocolos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/home.css')
    <link rel="icon" href="{{ asset('favicon.ico') }}">
</head>

<body>
    <canvas id="noir-bg"></canvas>
    <header class="navbar">
        <div class="nav-container">
            <div class="logo glitch-text">N.O.I.R</div>
            <nav>
                <ul class="nav-links">
                    <li><a href="home">Início</a></li>
                    <li><a href="organizacao">A Organização</a></li>
                    <li><a href="protocolos" class="nav-accent">Protocolos</a></li>
                    <li><a href="arquivos">Arquivos</a></li>
                    <li><a href="sistema">Acessar Sistema</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <section class="hero">
        <h1>PROTOCOLOS & CLASSIFICAÇÃO</h1>
        <p class="hero-subtitle">
            CONTENÇÃO • RISCO • EXISTÊNCIA
        </p>
        <div class="divider"></div>
        <p class="hero-description">
            Toda anomalia recebe uma classificação.  
            Toda classificação determina o destino.
        </p>
    </section>
    <section class="section">
        <div class="two-columns">
            <div>
                <h2>Sistema de Classificação N.O.I.R</h2>
                <p>
                    A N.O.I.R classifica entidades, objetos e rupturas dimensionais
                    com base em previsibilidade, dificuldade de contenção
                    e impacto existencial.
                </p>
                <p>
                    A classificação não mede apenas o perigo -
                    mede a capacidade da humanidade continuar existindo.
                </p>
            </div>

            <div class="status-box">
                <p><strong>VERSÃO:</strong> 1.2</p>
                <p><strong>AUTORIZAÇÃO:</strong> DIRETORIA CENTRAL</p>
                <p><strong>ACESSO:</strong> MONITORADO</p>
                <p><strong>STATUS GLOBAL:</strong> ESTÁVEL</p>
            </div>
        </div>
    </section>
    <section class="section section-dark">
        <h2>Classes de Entidades</h2>

        <div class="cards class-grid">

            <div class="card class-neutralizado">
                <h3>NEUTRALIZADO</h3>
                <p>Entidades completamente desativadas.</p>
                <p>Arquivadas apenas para registro histórico.</p>
            </div>

            <div class="card class-seguro">
                <h3>SEGURO</h3>
                <p>Comportamento previsível.</p>
                <p>Contenção simples e estável.</p>
            </div>

            <div class="card class-totin">
                <h3>TOTIN</h3>
                <p>Anomalias utilizadas para conter outras anomalias.</p>
                <p>Existência restrita à Diretoria.</p>
            </div>

            <div class="card class-isop">
                <h3>ÍSOP</h3>
                <p>Imprevisíveis ou parcialmente compreendidos.</p>
                <p>Monitoramento constante obrigatório.</p>
            </div>

            <div class="card class-denus">
                <h3>DENUS</h3>
                <p>Dificuldade extrema de contenção.</p>
                <p>Potencial de devastação regional ou global.</p>
            </div>

            <div class="card class-setis">
                <h3>SETIS</h3>
                <p>Impossíveis de conter.</p>
                <p>Ameaça existencial à realidade.</p>
            </div>

        </div>
    </section>

    <section class="section">
        <h2>Diretrizes Operacionais</h2>

        <div class="cards">

            <div class="card">
                <h3>PROTOCOLO DE ISOLAMENTO</h3>
                <p>Evacuação imediata da área afetada.</p>
                <p>Bloqueio de comunicações civis.</p>
            </div>

            <div class="card">
                <h3>PROTOCOLO DE SILENCIAMENTO</h3>
                <p>Vazamentos devem ser tratados como ficção.</p>
                <p>Manipulação midiática autorizada.</p>
            </div>

            <div class="card">
                <h3>PROTOCOLO DE REGISTRO</h3>
                <p>Toda anomalia deve ser documentada.</p>
                <p>Mesmo após neutralização.</p>
            </div>

            <div class="card">
                <h3>PROTOCOLO DE ESCALONAMENTO</h3>
                <p>DENUS ou SETIS exigem contato imediato com a Diretoria Central.</p>
            </div>

        </div>
    </section>

    <section class="section section-warning">
        <h2>Protocolo Existencial</h2>
        <p>
            Caso uma entidade SETIS atinja estado irreversível,
            a prioridade deixa de ser contenção.
        </p>
        <p>
            A realidade pode ser sacrificada para preservar o restante.
        </p>

        <a href="sistema" class="btn">Solicitar Autorização</a>
    </section>

    <footer class="footer">
        <p>© N.O.I.R - Diretoria Central</p>
        <p>Fundada por Phillip Müller - 1947</p>
        <p class="version">v1.0-operational</p>
    </footer>

    <div id="setis-alert">
        ⚠ NÍVEL DE RISCO MÁXIMO
    </div>

    <audio id="setis-audio" preload="auto" loop>
        <source src="{{ asset('sounds/interferencia.mp3') }}" type="audio/mpeg">
    </audio>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const setisCards = document.querySelectorAll(".class-setis");
            const audioElement = document.getElementById("setis-audio");

            let audioContext;
            let sourceNode;
            let gainNode;
            let distortionNode;
            let isInitialized = false;

            function createDistortion(amount) {
                const k = typeof amount === 'number' ? amount : 50;
                const n_samples = 44100;
                const curve = new Float32Array(n_samples);
                const deg = Math.PI / 180;

                for (let i = 0; i < n_samples; ++i) {
                    const x = i * 2 / n_samples - 1;
                    curve[i] = (3 + k) * x * 20 * deg / (Math.PI + k * Math.abs(x));
                }
                return curve;
            }

            function initAudio() {
                if (isInitialized) return;

                audioContext = new(window.AudioContext || window.webkitAudioContext)();
                sourceNode = audioContext.createMediaElementSource(audioElement);
                gainNode = audioContext.createGain();
                distortionNode = audioContext.createWaveShaper();

                distortionNode.curve = createDistortion(Math.random() * 40 + 10);
                distortionNode.oversample = '4x';

                sourceNode.connect(distortionNode);
                distortionNode.connect(gainNode);
                gainNode.connect(audioContext.destination);

                gainNode.gain.value = 0;

                isInitialized = true;
            }

            function digitalClick() {
                const clickOsc = audioContext.createOscillator();
                const clickGain = audioContext.createGain();

                clickOsc.type = "square";
                clickOsc.frequency.value = 1000;

                clickGain.gain.setValueAtTime(0.4, audioContext.currentTime);
                clickGain.gain.exponentialRampToValueAtTime(0.001, audioContext.currentTime + 0.05);

                clickOsc.connect(clickGain);
                clickGain.connect(audioContext.destination);

                clickOsc.start();
                clickOsc.stop(audioContext.currentTime + 0.05);
            }

            function startSetisAudio() {
                initAudio();
                digitalClick();

                audioElement.playbackRate = 0.97 + (Math.random() * 0.06);
                distortionNode.curve = createDistortion(Math.random() * 60 + 20);

                audioElement.play().catch(() => {});

                gainNode.gain.cancelScheduledValues(audioContext.currentTime);
                gainNode.gain.setValueAtTime(0, audioContext.currentTime);
                gainNode.gain.linearRampToValueAtTime(0.18, audioContext.currentTime + 2);
            }

            function stopSetisAudio() {
                if (!isInitialized) return;

                gainNode.gain.cancelScheduledValues(audioContext.currentTime);
                gainNode.gain.linearRampToValueAtTime(0, audioContext.currentTime + 0.5);

                setTimeout(() => {
                    audioElement.pause();
                    audioElement.currentTime = 0;
                }, 600);
            }

            document.addEventListener("click", function unlock() {
                if (!isInitialized) {
                    initAudio();
                    audioContext.resume();
                }
                document.removeEventListener("click", unlock);
            });

            setisCards.forEach(card => {
                card.addEventListener("mouseenter", () => {
                    document.body.classList.add("setis-active");
                    startSetisAudio();
                });

                card.addEventListener("mouseleave", () => {
                    document.body.classList.remove("setis-active");
                    stopSetisAudio();
                });
            });

        });
    </script>

    @vite('resources/js/noir-bg.js')

</body>
</html>
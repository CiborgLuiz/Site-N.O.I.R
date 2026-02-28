<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>N.O.I.R — Arquivos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/arquivos.css')
    <link rel="icon" href="{{ asset('favicon.ico') }}">
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
                    <li><a href="/arquivos" class="nav-accent">Arquivos</a></li>
                    <li><a href="/sistema">Acessar Sistema</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero hero-small">
        <h1>ARQUIVOS</h1>
        <p class="hero-subtitle">
            BASE DE DADOS CONFIDENCIAL
        </p>
        <div class="divider"></div>
        <p class="hero-description">
            Classificação ativa por nível de risco.
        </p>
    </section>

    <section class="section section-dark">

        @php
            $order = [
                'neutralizado' => 1,
                'seguro' => 2,
                'totin' => 3,
                'ísop' => 4,
                'isop' => 4,
                'denus' => 5,
                'setis' => 6,
            ];

            $sortedArchives = $archives->sortBy(function ($archive) use ($order) {
                return $order[strtolower($archive->classification)] ?? 99;
            });
        @endphp

        <div class="archive-grid">

            @foreach ($sortedArchives as $archive)
                @php
                    $classSlug = strtolower($archive->classification);
                @endphp

                <div class="archive-card class-{{ $classSlug }}">

                    <div class="archive-image">
                        <img src="{{ asset($archive->image_path) }}" alt="{{ $archive->name }}">
                    </div>

                    <div class="archive-info">
                        <h3>{{ $archive->name }}</h3>

                        <p class="archive-id">
                            ID: {{ $archive->identifier }}
                        </p>

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
                clickOsc.frequency.value = 1200;

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

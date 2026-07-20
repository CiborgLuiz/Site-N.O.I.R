@extends('layouts.app')

@section('page_title', 'Protocolos')
@section('head_css')
    @vite(['resources/css/home.css', 'resources/css/protocolos-effects.css'])
@endsection

@section('content')
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

    <div id="setis-alert">
        ⚠ NÍVEL DE RISCO MÁXIMO
    </div>

    <audio id="audio-neutralizado" src="{{ asset('sounds/neutralizado.mp3') }}" preload="auto" loop></audio>
    <audio id="audio-seguro" src="{{ asset('sounds/seguro.mp3') }}" preload="auto" loop></audio>
    <audio id="audio-totin" src="{{ asset('sounds/totin.mp3') }}" preload="auto" loop></audio>
    <audio id="audio-isop" src="{{ asset('sounds/isop.mp3') }}" preload="auto" loop></audio>
    <audio id="audio-denus" src="{{ asset('sounds/denus.mp3') }}" preload="auto" loop></audio>
    <audio id="audio-setis" src="{{ asset('sounds/setis.mp3') }}" preload="auto" loop></audio>
@endsection

@section('scripts')
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
                if (!audioEl) return null;

                const source = audioContext.createMediaElementSource(audioEl);
                const gain = audioContext.createGain();

                gain.gain.value = 0;

                source.connect(gain);
                gain.connect(audioContext.destination);

                nodes[type] = { audioEl, gain };
                return nodes[type];
            }

            function activate(type) {
                if (!audioMap[type]) return;
                if (activeNode) deactivate();

                document.body.classList.remove(
                    "neutralizado-active", "seguro-active", "totin-active",
                    "isop-active", "denus-active", "setis-active"
                );

                document.body.classList.add(type + "-active");

                const node = setupAudio(type);
                if (!node) return;

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

                if (deactivateTimer) clearTimeout(deactivateTimer);
                deactivateTimer = setTimeout(() => {
                    node.audioEl.pause();
                    node.audioEl.currentTime = 0;
                }, 600);

                document.body.classList.remove(
                    "neutralizado-active", "seguro-active", "totin-active",
                    "isop-active", "denus-active", "setis-active"
                );
            }

            document.querySelectorAll(".card").forEach(card => {
                card.addEventListener("mouseenter", () => {
                    audioContext.resume();
                    const className = Array.from(card.classList)
                        .find(c => c.startsWith("class-"));
                    if (!className) return;
                    const type = className.replace("class-", "").normalize("NFD").replace(
                        /[\u0300-\u036f]/g, "");
                    activate(type);
                });
                card.addEventListener("mouseleave", deactivate);
            });
        });
    </script>
@endsection

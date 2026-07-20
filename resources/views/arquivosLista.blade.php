@extends('layouts.app')

@section('page_title', 'Arquivos')
@section('head_css')
    @vite('resources/css/arquivos.css')
@endsection

@section('content')
    <section class="hero hero-small">
        <h1>ARQUIVOS</h1>
        <p class="hero-subtitle">BASE DE DADOS CONFIDENCIAL</p>
        <div class="divider"></div>
        <p class="hero-description">
            Classificação ativa por nível de risco.
        </p>
    </section>

    <section class="section section-dark">
        @php
            function normalizeSlug($text)
            {
                $text = strtolower($text);
                $text = str_replace(['í'], ['i'], $text);
                return $text;
            }

            $sortedArchives = $archives->sortBy(function ($archive) {
                preg_match('/(\d+)/', $archive->identifier, $matches);
                return isset($matches[1]) ? intval($matches[1]) : PHP_INT_MAX;
            });
        @endphp

        <div class="archive-controls">
            <input type="text" id="archive-search" class="archive-search"
                placeholder="Pesquisar por nome, ID ou classificação...">

            <select id="archive-filter" class="archive-filter">
                <option value="">Todas as classificações</option>
                <option value="neutralizado">Neutralizado</option>
                <option value="seguro">Seguro</option>
                <option value="totin">Totin</option>
                <option value="isop">Isop</option>
                <option value="denus">Denus</option>
                <option value="setis">Setis</option>
            </select>
        </div>

        <div id="archive-count" class="archive-count">
            {{ count($sortedArchives) }} arquivos encontrados
        </div>

        <div class="archive-grid">
            @foreach ($sortedArchives as $archive)
                @php
                    $classSlug = normalizeSlug($archive->classification);
                @endphp

                <div class="archive-card class-{{ $classSlug }}" data-name="{{ strtolower($archive->name) }}"
                    data-id="{{ strtolower($archive->identifier) }}" data-class="{{ strtolower($classSlug) }}">
                    <div class="archive-image">
                        <img src="{{ $archive->image_url }}" alt="{{ $archive->name }}">
                    </div>

                    <div class="archive-info">
                        <h3>{{ $archive->name }}</h3>
                        <p class="archive-id">ID: {{ $archive->identifier }}</p>
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

    <div id="setis-alert">⚠ NÍVEL DE RISCO MÁXIMO</div>

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

            document.querySelectorAll(".archive-card").forEach(card => {
                card.addEventListener("mouseenter", () => {
                    audioContext.resume();
                    const className = Array.from(card.classList)
                        .find(c => c.startsWith("class-"));
                    if (!className) return;
                    const type = className.replace("class-", "");
                    activate(type);
                });
                card.addEventListener("mouseleave", deactivate);
            });

            window.addEventListener("load", () => {
                audioContext.resume();
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const searchInput = document.getElementById("archive-search");
            const filterSelect = document.getElementById("archive-filter");
            const countElement = document.getElementById("archive-count");

            function updateArchives() {
                const search = searchInput.value.toLowerCase().trim();
                const filter = filterSelect.value.toLowerCase();

                let visible = 0;

                document.querySelectorAll(".archive-card").forEach(card => {
                    const name = card.dataset.name;
                    const id = card.dataset.id;
                    const classification = card.dataset.class;

                    const matchesSearch =
                        name.includes(search) ||
                        id.includes(search) ||
                        classification.includes(search);

                    const matchesFilter = !filter || classification === filter;

                    if (matchesSearch && matchesFilter) {
                        card.style.display = "";
                        visible++;
                    } else {
                        card.style.display = "none";
                    }
                });

                countElement.textContent =
                    `${visible} arquivo${visible !== 1 ? 's' : ''} encontrado${visible !== 1 ? 's' : ''}`;
            }

            searchInput.addEventListener("input", updateArchives);
            filterSelect.addEventListener("change", updateArchives);
        });
    </script>
@endsection

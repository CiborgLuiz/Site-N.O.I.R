<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>N.O.I.R — Arquivos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/arquivos.css')
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
            Apenas pessoal autorizado.
        </p>
    </section>

    <section class="section section-dark">

        <div class="archive-grid">

            @foreach($archives as $archive)
            <div class="archive-card">

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
        <p>© N.O.I.R — Arquivos Internos</p>
        <p class="version">classificação: sigilo máximo</p>
    </footer>

    <script src="{{ asset('js/noir-bg.js') }}"></script>
</body>
</html>

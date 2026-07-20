@extends('layouts.app')

@section('page_title', 'Sistema')
@section('body_class', 'system-page')
@section('head_css')
    @vite(['resources/css/home.css', 'resources/css/system.css'])
@endsection

@section('content')
    <audio id="xp-sound" preload="auto">
        <source src="{{ asset('sounds/windows-xp-startup.mp3') }}" type="audio/mpeg">
    </audio>

    <section class="system-wrapper">
        <div class="system-mobile-mode">
            <div class="system-mobile-header">
                <h2>N.O.I.R OS</h2>
                <p>Sistema Operacional</p>
            </div>
            <div class="system-apps-grid">
                <a href="{{ url('/sistema/terminal') }}" class="system-app-card">
                    <div class="system-app-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M6 9a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3A.5.5 0 0 1 6 9M3.854 4.146a.5.5 0 1 0-.708.708L4.793 6.5 3.146 8.146a.5.5 0 1 0 .708.708l2-2a.5.5 0 0 0 0-.708z"/>
                            <path d="M2 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1z"/>
                        </svg>
                    </div>
                    <span class="system-app-name">Terminal</span>
                    <span class="system-app-desc">NoirShell v1.0</span>
                </a>
                <a href="{{ url('/protocolos') }}" class="system-app-card">
                    <div class="system-app-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M4 0h5.5L14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm5.5 1V3H14L9.5 1z"/>
                        </svg>
                    </div>
                    <span class="system-app-name">Protocolos</span>
                    <span class="system-app-desc">Classificação e diretrizes</span>
                </a>
                <a href="{{ url('/arquivos') }}" class="system-app-card">
                    <div class="system-app-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a2 2 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3m-8.322.12q.322-.119.684-.12h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981z"/>
                        </svg>
                    </div>
                    <span class="system-app-name">Arquivos</span>
                    <span class="system-app-desc">Banco de dados anômalo</span>
                </a>
                <a href="{{ url('/organizacao') }}" class="system-app-card">
                    <div class="system-app-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H2s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                        </svg>
                    </div>
                    <span class="system-app-name">Organização</span>
                    <span class="system-app-desc">Estrutura operacional</span>
                </a>
                <a href="{{ url('/home') }}" class="system-app-card">
                    <div class="system-app-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 2 7.5V14a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5V7.5a.5.5 0 0 0-.146-.354z"/>
                        </svg>
                    </div>
                    <span class="system-app-name">Início</span>
                    <span class="system-app-desc">Portal principal N.O.I.R</span>
                </a>
            </div>
        </div>

        <div class="monitor-frame system-desktop-mode">
            <div id="boot-screen" class="boot-screen">
                <div class="boot-text" id="boot-text"></div>
            </div>
            <div id="desktop" class="desktop hidden">
                <div class="system-wallpaper-canvas">
                    @include('partials.tesseract-widget', ['theme' => 'system'])
                </div>
                <a class="desktop-icon" href="{{ url('/sistema/terminal') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-terminal" viewBox="0 0 16 16">
                        <path d="M6 9a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3A.5.5 0 0 1 6 9M3.854 4.146a.5.5 0 1 0-.708.708L4.793 6.5 3.146 8.146a.5.5 0 1 0 .708.708l2-2a.5.5 0 0 0 0-.708z" />
                        <path d="M2 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1z" />
                    </svg>
                    <span>Terminal</span>
                </a>

                @foreach ($folders as $folder)
                    <div class="desktop-icon" onclick="openFolder({{ $folder->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-folder-fill" viewBox="0 0 16 16">
                            <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a2 2 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3m-8.322.12q.322-.119.684-.12h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981z" />
                        </svg>
                        <span>{{ $folder->name }}</span>
                    </div>
                @endforeach

                <div id="windows"></div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @vite('resources/js/tesseract-widget.js')
    <script>
        window.wallpaperCommand = function(args) {
            const w = window.TESSERACT_WIDGET;
            if (!w) return 'Widget não inicializado';
            if (args[0] === 'theme' && args[1]) {
                w.setTheme(args[1]);
                return 'Tema: ' + args[1];
            }
            if (args[0] === 'intensity' && args[1] !== undefined) {
                w.setIntensity(parseFloat(args[1]));
                return 'Intensidade: ' + args[1];
            }
            return 'Uso: wallpaper theme [noir|system|breach|retro] | wallpaper intensity [0-1]';
        };
    </script>
@endsection

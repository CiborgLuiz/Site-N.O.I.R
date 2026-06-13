<!DOCTYPE html>
<html lang="pt-BR">
<head>
    
    <!-- Básico -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->

    <meta name="description" content="@yield('description', 'N.O.I.R é uma experiência única de Minecraft com mistérios, entidades, anomalias e eventos que desafiam a realidade.')">

    <meta name="keywords" content="Minecraft, N.O.I.R, SMP, Servidor Minecraft, Horror, Mistério, Survival, Modpack">
    <meta name="author" content="Equipe N.O.I.R SMP">

    <!-- Open Graph (Discord, WhatsApp, Facebook, etc) -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="N.O.I.R SMP">
    <meta property="og:title" content="@yield('og_title', 'N.O.I.R SMP')">
    <meta property="og:description" content="@yield('og_description', 'Uma experiência de Minecraft onde a realidade nem sempre é o que parece.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/noir-preview.png') }}">

    <!-- Twitter/X -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'N.O.I.R SMP')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Uma experiência de Minecraft onde a realidade nem sempre é o que parece.')">
    <meta name="twitter:image" content="{{ asset('images/noir-preview.png') }}">

    <!-- Cor do navegador -->
    <meta name="theme-color" content="#4B0082">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonte -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Estilo Global dos Links -->
    <style>
        a {
            color: #9d6cff;
            text-decoration: none;
            transition: all .25s ease;
        }

        a:hover {
            color: #c3a6ff;
            text-shadow: 0 0 8px rgba(157,108,255,.6);
        }

        a:active {
            color: #ffffff;
        }

        a.special-link {
            display: inline-block;
            padding: 6px 12px;
            border: 1px solid rgba(157,108,255,.3);
            border-radius: 8px;
            backdrop-filter: blur(10px);
            transition: all .25s ease;
        }

        a.special-link:hover {
            background: rgba(157,108,255,.15);
            border-color: #9d6cff;
            transform: translateY(-2px);
        }
    </style>
    @stack('head')
    <title>N.O.I.R - Admin</title>
    @vite('resources/css/admin.css')
</head>
<body
    class="admin-page noir-loading"
    style="--noir-logo-image: url('{{ asset('images/logo.png') }}');"
>
    @include('partials.site-loader')

    <canvas id="noir-bg"></canvas>

    <main class="admin-auth-shell">
        <section class="admin-auth-panel">
            <div class="admin-mark">
                <span class="logo">N.O.I.R</span>
                <span>ACESSO ADMINISTRATIVO</span>
            </div>

            <h1>/admin</h1>
            <p class="admin-muted">Área secreta da diretoria e administradores autorizados.</p>

            @if (session('status'))
                <div class="admin-flash">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="admin-form">
                @csrf

                <label>
                    Email
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email') <span class="admin-error">{{ $message }}</span> @enderror
                </label>

                <label>
                    Senha
                    <input type="password" name="password" required>
                    @error('password') <span class="admin-error">{{ $message }}</span> @enderror
                </label>

                <button type="submit" class="admin-button">Entrar</button>
            </form>

            @if ($canRegister)
                <a href="{{ route('admin.register.form') }}" class="admin-link">Criar conta com chave única</a>
            @endif
        </section>
    </main>

    @vite('resources/js/site.js')
    @vite('resources/js/noir-bg.js')
</body>
</html>

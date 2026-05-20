<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>N.O.I.R - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
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

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>N.O.I.R - Registrar Admin</title>
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
                <span>CHAVE DE USO UNICO</span>
            </div>

            <h1>Registrar admin</h1>
            <p class="admin-muted">A chave recebida define o cargo e perde validade depois do cadastro.</p>

            <form method="POST" action="{{ route('admin.register') }}" class="admin-form">
                @csrf

                <label>
                    Chave
                    <input type="text" name="invite_key" value="{{ old('invite_key') }}" required>
                    @error('invite_key') <span class="admin-error">{{ $message }}</span> @enderror
                </label>

                <label>
                    Nome
                    <input type="text" name="name" value="{{ old('name') }}" required>
                    @error('name') <span class="admin-error">{{ $message }}</span> @enderror
                </label>

                <label>
                    Email
                    <input type="email" name="email" value="{{ old('email') }}" required>
                    @error('email') <span class="admin-error">{{ $message }}</span> @enderror
                </label>

                <label>
                    Senha
                    <input type="password" name="password" required>
                    @error('password') <span class="admin-error">{{ $message }}</span> @enderror
                </label>

                <label>
                    Confirmar senha
                    <input type="password" name="password_confirmation" required>
                </label>

                <button type="submit" class="admin-button">Criar conta</button>
            </form>

            <a href="{{ route('admin.index') }}" class="admin-link">Voltar ao acesso</a>
        </section>
    </main>

    @vite('resources/js/site.js')
    @vite('resources/js/noir-bg.js')
</body>
</html>

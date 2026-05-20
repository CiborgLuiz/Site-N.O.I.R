<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>N.O.I.R - Setup Admin</title>
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
                <span>PROTOCOLO DE DONO</span>
            </div>

            <h1>Inicializar /admin</h1>
            <p class="admin-muted">Nenhuma conta de dono existe. Esta criação fecha o setup inicial.</p>

            <form method="POST" action="{{ route('admin.setup') }}" class="admin-form">
                @csrf

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

                <button type="submit" class="admin-button">Criar conta de dono</button>
            </form>
        </section>
    </main>

    @vite('resources/js/site.js')
    @vite('resources/js/noir-bg.js')
</body>
</html>
